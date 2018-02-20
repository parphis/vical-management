<?php
require_once 'DB.php';
class Category extends DB {
  private $db;

  function __construct() {
    parent::__construct();
    try {
      $this->db = new DB();
    }
    catch (Exception $exception) {
      throw new Exception($exception);
    }
  }

  public function getCategories() {
    try {
      $res = $this->db->getAll("select id,name,style from category where show='1' order by name;");
    }
    catch (Exception $e) {
      throw new Exception($e);
    }
    $html = "<select id='event-category' class='w3-select w3-border'>";
    foreach ($res as $key => $value) {
      $html .= "<option class='w3-".$value['style']."' value='".$value['id']."'>".$value['name']."</option>";
    }
    $html .= "</select>";
    return $html;
  }

  public function listCategories() {
    try {
      $res = $this->db->getAll("select * from category where show='1' order by name;");
    }
    catch (Exception $e) {
      throw new Exception($e);
    }

    $html = "<ul id='category-list' class='w3-ul'>";
    $html .= "<li onclick='editCategory();'><i class='material-icons'>add_circle</i>Új kategória</li>";
    foreach ($res as $key => $value) {
      $html .= "<li class='w3-".$value['style']."'>";
      $html .= "<span class='category-name'>".$value['name']."</span>";
      $html .= "<i class='material-icons edit' onclick='editCategory(\"".$value['id']."\",\"".$value['name']."\",\"".$value['style']."\")'>mode_edit</i>";
      $html .= "<i class='material-icons remove' onclick='removeCategory(\"".$value['id']."\",\"".$value['name']."\")'>delete</i>";
      $html .= "</li>";
    }
    $html .= "</ul>";
    return $html;
  }

  public function newCategory($json_str) {
    $params = json_decode($json_str);
    if ($params->{'name'}!='') {
      $this->db->_("insert into category ('name', 'style') values(?,?);", [$params->{'name'}, $params->{'style'}]);
    }
    return $this->listCategories();
  }

  public function hideCategory($json_str) {
    $params = json_decode($json_str);
    if ($params->{'id'}!='') {
      $this->db->_("update category set show='0' where id=?;", [$params->{'id'}]);
    }
    return $this->listCategories();
  }

  public function updateCategory($json_str) {
    $params = json_decode($json_str);
    if ($params->{'name'}!='') {
      $this->db->_("update category set name=?, style=? where id=?;", [$params->{'name'}, $params->{'style'}, $params->{'id'}]);
    }
    return $this->listCategories();
  }
}
?>
