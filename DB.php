<?php
class DB {
  private $db;

  function __construct() {
    try {
      $this->db = new SQLite3('/calendar/calendar.sqlite');
    }
    catch (Exception $exception) {
      throw new Exception($exception);
    }
  }

  function _($qry,$params="") {
    if (!$this->db) {
      throw new Exception("Could not access database.");
      return;
    }

    $statement = $this->db->prepare($qry);

    if ($params!="") {
      $i = 1;
      foreach ($params as $param) {
        $statement->bindValue($i, $param);
        $i++;
      }
    }

    $result = $statement->execute();
  }

  function getAll($qry,$params="") {
    if (!$this->db) {
      throw new Exception("Could not access database.");
      return;
    }

    $statement = $this->db->prepare($qry);

    if ($params!="") {
      $i = 1;
      foreach ($params as $param) {
        $statement->bindValue($i, $param);
        $i++;
      }
    }

    $result = $statement->execute();

    $rows = array();
    $count = 0;

    while($res = $result->fetchArray(SQLITE3_ASSOC)){
      $rows[$count++] = $res;
    }

    $result->finalize();

    $this->db->close();

    return $rows;
  }
}
/*$db = new DB();
$res = $db->getAll("select * from namedays where id=?;", ["12_01"]);
var_dump($res);*/
?>
