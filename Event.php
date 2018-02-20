<?php
require_once 'DB.php';
class Event extends DB {
  private $db;
  private $from;
  private $to;
  private $threshold;

  function __construct() {
    parent::__construct();
    try {
      $this->db = new DB();
    }
    catch (Exception $exception) {
      throw new Exception($exception);
    }
    $this->threshold = 120;
    $this->from = new DateTime('today');
    $this->from = $this->from->format('Y-m-d');
    $this->to = new DateTime('today');
    $this->to->add(new DateInterval("P".$this->threshold."D"));
    $this->to = $this->to->format('Y-m-d');
  }

  public function listEvents() {
    try {
      $res = $this->db->getAll(
        "select e.id, e.subject as subject, e.start as start, e.category_id as category_id, c.name as name, c.style as color from event e left join category c on c.id=e.category_id where start>=DATETIME(?) and start<DATETIME(?) and e.show=1 order by start;",
        [$this->from, $this->to]
      );
    }
    catch (Exception $e) {
      throw new Exception($e);
    }

    $html = "<a href='#' onclick='editEvent();'><i class='material-icons'>add_circle</i>Új esemény</a>";
    foreach ($res as $key => $value) {
      $dt = new DateTime($value['start']);
      $html .= "<div class='w3-card-2 w3-margin ".(($value['color']!='')?'w3-'.$value['color']:'')."'>";
      $html .= "<header class='w3-container'><h2 class='event-date'>".$dt->format('Y.m.d')."</h2></header>";
      $html .= "<div class='w3-container'>";
      $html .= "<span class='event-subject'>".$value['subject']."</span><br/>";
      $html .= "<span class='event-category'>".$value['name']."</span>";
      $html .= "</div>";
      $html .= "<footer class='w3-container'>";
      $html .= "<i class='material-icons edit' onclick='editEvent(\"".$value['id']."\",\"".$value['start']."\",\"".$value['subject']."\", \"".$value['category_id']."\")'>mode_edit</i><i class='material-icons remove' onclick='removeEvent(\"".$value['id']."\",\"".$value['subject']."\")'>delete</i>";
      $html .= "</footer></div>";
    }
    return $html;
  }

  public function newEvent($json_str) {
    $params = json_decode($json_str);
    if ($params->{'start'}!='') {
      $start = new DateTime($params->{'start'});
      if ($params->{'end'}!='') {
        $end = new DateTime($params->{'end'});
      }
      else {
        $end = $start;
      }
      if ($start>$end) {
        return $this->listEvents();
      }
      $i = 0;
      do {
        $arr[$i++] = $start->format('Y-m-d 00:00:00');
        if ($start==$end) break;
        $start->modify("+1 day");
      } while ($start<=$end);
      
      for ($i=0; $i<count($arr); $i++) {
        $this->db->_("insert into event ('subject', 'start', 'category_id') values(?,?,?);", 
          [str_replace("\r\n"," ",$params->{'subject'}), $arr[$i], $params->{'category'}]);
      }
    }
    return $this->listEvents();
  }

  public function hideEvent($json_str) {
    $params = json_decode($json_str);
    if ($params->{'id'}!='') {
      $this->db->_("update event set show='0' where id=?;", [$params->{'id'}]);
    }
    return $this->listEvents();
  }

  public function updateEvent($json_str) {
    $params = json_decode($json_str);
    if ($params->{'start'}!='') {
      $start = new DateTime($params->{'start'});
      $end = new DateTime($params->{'end'});

      if ($params->{'end'}!='') { 
        if ($start>$end) {
          return $this->listEvents();
        }
      }
      $i = 0;
      do {
        $arr[$i++] = $start->format('Y-m-d 00:00:00');
        $start->modify("+1 day");
      } while ($start<=$end);
      for ($i=0; $i<count($arr); $i++) {
        $this->db->_("update event set subject=?, start=?, category_id=? where id=?;", 
          [str_replace("\r\n"," ",$params->{'subject'}), $arr[$i], $params->{'category'}, $params->{'id'}]);
      }
    }
    return $this->listEvents();
  }
}
?>
