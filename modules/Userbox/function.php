<?php
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
// Module Userbox cree par Stive                                           //
// www.palacewar.eu & nk-create.be                                          //
// Redistribuation interdite                                                //
// Last changes 26/02/2013                                                  //
// -------------------------------------------------------------------------//
define('USERBOX_TABLE_SEND', $nuked['prefix'] . '_userbox_send');
define('USERBOX_TABLE_CONFIG', $nuked['prefix'] . '_userbox_config');

function include_jquery() {
		  global $nuked;
echo " <script type=\"text/javascript\">
       <!-- 
       if ( typeof jQuery == 'undefined' )
       {
       document.write( '<script type=\"text/javascript\" src=\"modules/Userbox/js/jquery-1.9.1.min.js\"></script>' );
       }
       document.write('<link href=\"index.php?file=Userbox&amp;nuked_nude&amp;op=css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />');
       -->
       </script>\n";
}
function include_script() {
  $file = 'modules/Userbox/js/script.js';
  $path_js = $file['modules/Userbox/js/script.js'];
  static $path_js = false;
  if (!$path_js) {
  $path_js = true;
  echo " <script type=\"text/javascript\">
         <!-- 
         document.write('<script type=\"text/javascript\" src=\"".$file."\"></script>');
         -->
         </script>\n";
  }
}
class userbox {

  public function listing($data = array()){
    $conditions = '1=1';
    $fields ='*';
    $limit = '';
    $order = 'mid DESC';
    if(isset($data['conditions'])) { $conditions = $data['conditions']; }
    if(isset($data['fields'])) { $fields = $data['fields']; }
    if(isset($data['limit'])) { $limit = "LIMIT ".$data['limit']; }
    if(isset($data['order'])) { $order = $data['order']; }
    $sql = "SELECT $fields FROM " . USERBOX_TABLE . " WHERE $conditions ORDER BY $order $limit";
    $req = mysql_query($sql) or die(mysql_error());
    $d = array();
    while($data = mysql_fetch_assoc($req)) {
    $d[] = $data;
    }
    return $d;
  }
  
  public function listing_send($data = array()){
    $conditions = '1=1';
    $fields ='*';
    $limit = '';
    $order = 'mid DESC';
    if(isset($data['conditions'])) { $conditions = $data['conditions']; }
    if(isset($data['fields'])) { $fields = $data['fields']; }
    if(isset($data['limit'])) { $limit = "LIMIT ".$data['limit']; }
    if(isset($data['order'])) { $order = $data['order']; }
    $sql = "SELECT $fields FROM " . USERBOX_TABLE_SEND . " WHERE $conditions ORDER BY $order $limit";
    $req = mysql_query($sql) or die(mysql_error());
    $d = array();
    while($data = mysql_fetch_assoc($req)) {
    $d[] = $data;
    }
    return $d;
  }
  
  public function config($data = array()){
    $fields ='*';
    $sql = "SELECT $fields FROM " . USERBOX_TABLE_CONFIG;
    $req = mysql_query($sql) or die(mysql_error());
    while ($row = mysql_fetch_array($req)) $config[$row['name']] = htmlentities($row['value'], ENT_NOQUOTES);
    return $config;
  }
  
  public function save_config($data = array()){
    if(isset($data['value']) && !empty($data['value']) && isset($data['name']) && !empty($data['name']) ){
    $sql = "UPDATE " . USERBOX_TABLE_CONFIG . " SET value = '" . $data['value'] . "' WHERE name = '" . $data['name'] . "'";
    }
    mysql_query($sql) or die(mysql_error());
  }

  public function delete($data = array()){
    $conditions = '1=1';
    if(isset($data['conditions'])) { $conditions = $data['conditions']; }
    $sql = "DELETE FROM " . USERBOX_TABLE . " WHERE $conditions";
    $req = mysql_query($sql) or die(mysql_error());
  }
  
  public function delete_send($data = array()){
    $conditions = '1=1';
    if(isset($data['conditions'])) { $conditions = $data['conditions']; }
    $sql = "DELETE FROM " . USERBOX_TABLE_SEND . " WHERE $conditions";
    $req = mysql_query($sql) or die(mysql_error());
  }
  
  public function insert($data = array()){
	$sql = "INSERT INTO " . USERBOX_TABLE . " (" . $data['name'] . ") VALUES (" . $data['value'] . ")";
    $req = mysql_query($sql) or die(mysql_error());
  }
  
  public function insert_send($data = array()){
	$sql = "INSERT INTO " . USERBOX_TABLE_SEND . " (" . $data['name'] . ") VALUES (" . $data['value'] . ")";
    $req = mysql_query($sql) or die(mysql_error());
  }
 
  public function users($data){
    $sql = "SELECT " . $data['fields'] . " FROM " . USER_TABLE . " WHERE " . $data['check'] . " = '" . $data['id'] . "'";
    $req = mysql_query($sql) or die(mysql_error());	
    list($pseudo) = mysql_fetch_array($req);
    return $pseudo;
  }
  
  public function select_user(){ 
    $sql = "SELECT id, pseudo, niveau FROM " . USER_TABLE . " WHERE niveau > 0 ORDER BY niveau DESC, pseudo";
    $req = mysql_query($sql) or die(mysql_error());	
	while($data = mysql_fetch_assoc($req)) { 
    $d[] = $data;
    }
    return $d;
  }
  
  public function update($data){
	 if(isset($data['id']) && !empty($data['id'])){
	 $sql = "UPDATE " . USERBOX_TABLE . " SET " . $data['set'] . " WHERE mid = '" . $data['id'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  }
  
  public function update_mp($data){
	 if(isset($data['id']) && !empty($data['id'])){
	 $sql = "UPDATE " . USER_TABLE . " SET " . $data['set'] . " WHERE id = '" . $data['id'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  }
  
  public function check_conf($data){
	$fields ='*';
	if(isset($data['fields'])) { $fields = $data['fields']; }
	if(isset($data['id'])) { $fields = $data['id']; }
    $sql = "SELECT " . $data['fields'] . " FROM " . USER_TABLE . " WHERE id = '" . $data['id'] . "'";
    $req = mysql_query($sql) or die(mysql_error());	
    list($data) = mysql_fetch_array($req);
    return $data;
  }

}
?>