<?php
function include_jquery() {
global $nuked;
echo " <script type=\"text/javascript\">
       <!-- 
       if ( typeof jQuery == 'undefined' )
       {
       document.write( '<script type=\"text/javascript\" src=\"modules/Donation/js/jquery-1.8.3.min.js\"></script>' );
       }
       document.write('<link href=\"index.php?file=Donation&amp;nuked_nude&amp;op=css\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />');
       -->
       </script>\n";
        $sql = mysql_query("SELECT name FROM " . $nuked['prefix'] . "_donations_objet");
        $nb = mysql_num_rows($sql);
		
        if($nb == 0) {
        $insert = "INSERT INTO ". $nuked['prefix'] ."_donations_objet (name, target) VALUES('" . $nuked['name'] . "', '100')";
        mysql_query($insert);
        }
}
function include_script() {
  $file = 'modules/Donation/js/script.js';
  $path_js = $file['modules/Donation/js/script.js'];
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
class Donations {
  private function sql($data) {
	  global $nuked;
	 	  
	  $arr = array('prefix' => $nuked['prefix'], 'name' => $nuked['name']);
	  	  
	  return $arr[$data];
  }
  public function insert($data){
	 $sql = "INSERT INTO " . Donations::sql('prefix') . "_donations_received (" . $data['name'] . ") VALUES (" . $data['value'] . ")";
    $req = mysql_query($sql) or die(mysql_error());
  }
  public function delete($data){
	 $sql = "DELETE FROM " . Donations::sql('prefix') . "_donations_received WHERE id = '" . $data . "'";
     $req = mysql_query($sql) or die(mysql_error());
  }
  public function update($data){
		  if(isset($data['id']) && !empty($data['id'])){
	 $sql = "UPDATE " . Donations::sql('prefix') . "_donations_received SET id_users = '" .$data['id_users'] . "', received = '" .$data['received'] . "',  objet = '" .$data['objet'] . "',  date = '" .$data['date'] . "', devise = '" .$data['devise'] . "' WHERE id= '" . $data['id'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  }
  public function valid($data){
		  if(isset($data['id']) && !empty($data['id'])){
	 $sql = "UPDATE " . Donations::sql('prefix') . "_donations_received SET valid = '" .$data['valid'] . "' WHERE id= '" . $data['id'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  }
  public function user($data){
    $sql = "SELECT pseudo FROM " . Donations::sql("prefix") . "_users WHERE id = '" . $data . "'";
    $req = mysql_query($sql) or die(mysql_error());	
    list($pseudo) = mysql_fetch_array($req);
    return $pseudo;
  }
  public function iduser($data){
    $sql = "SELECT id FROM " . Donations::sql("prefix") . "_users WHERE pseudo = '" . $data . "'";
    $req = mysql_query($sql) or die(mysql_error());	
    list($id) = mysql_fetch_array($req);
    return $id;
  } 
  public function userlist(){
    $sql = "SELECT id, pseudo FROM " . Donations::sql("prefix") . "_users";
    $req = mysql_query($sql) or die(mysql_error());	
    $d = array();
    while ($data = mysql_fetch_assoc($req)) {
      $d[] = $data;
    }
    return $d;
  }
  public function config($data = array()){
    $sql = "SELECT name, value FROM " . Donations::sql("prefix") . "_donations";
    $req = mysql_query($sql) or die(mysql_error());
    while ($row = mysql_fetch_array($req)) $config[$row['name']] = htmlentities($row['value'], ENT_NOQUOTES);
    return $config;
  }
  public function objet($data = array()){
    $sql = "SELECT name, target FROM " . Donations::sql("prefix") . "_donations_objet";
    $req = mysql_query($sql) or die(mysql_error());
    $d = array();
    while ($data = mysql_fetch_assoc($req)) {
      $d[] = $data;
    }
    return $d;
  }
  public function addobj($data){
	  if(isset($data['name']) && !empty($data['name'])){
		  $sql = "INSERT INTO " . Donations::sql('prefix') . "_donations_objet (name, target) VALUES ('" . $data['name'] . "', '" . $data['value'] . "')";
		  $req = mysql_query($sql) or die(mysql_error());
	  }
  }
  public function delobj($data){
	  if(isset($data['name']) && !empty($data['name'])){
		  $sql = "DELETE FROM " . Donations::sql('prefix') . "_donations_objet WHERE name = '" . $data['name'] . "'";
		  $req = mysql_query($sql) or die(mysql_error());
	  }
  }
  public function editobj($data){
		  if(isset($data['name']) && !empty($data['name'])){
		  $sql = "UPDATE " . Donations::sql('prefix') . "_donations_objet SET target = '" .$data['value'] . "' WHERE name= '" . $data['name'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  } 
  public function amount($fields){
    $sql = "SELECT SUM($fields) FROM " . Donations::sql("prefix") . "_donations_objet";
    $req = mysql_query($sql) or die(mysql_error());
    $data = mysql_fetch_array($req);
    return $data;
  }
  
  public function sum($data){
    $sql = "SELECT SUM(received) FROM " . Donations::sql("prefix") . "_donations_received " . $data['conditions'] . "";
    $req = mysql_query($sql) or die(mysql_error());
    $data = mysql_fetch_array($req);
    return $data;
  }
  public function last($data = array()){
    $conditions = '1=1';
    $fields ='*';
    $limit = '';
    $order = 'id DESC';
    if(isset($data['conditions'])) { $conditions = $data['conditions']; }
    if(isset($data['fields'])) { $fields = $data['fields']; }
    if(isset($data['limit'])) { $limit = "LIMIT ".$data['limit']; }
    if(isset($data['order'])) { $order = $data['order']; }
    $sql = "SELECT $fields FROM " . Donations::sql('prefix') . "_donations_received WHERE $conditions ORDER BY $order $limit";
    $req = mysql_query($sql) or die(mysql_error());
    $d = array();
    while($data = mysql_fetch_assoc($req)) {
    $d[] = $data;
    }
    return $d;
  }
  public function save($data){
		  if(isset($data['name']) && !empty($data['name'])){
		  $sql = "UPDATE " . Donations::sql('prefix') . "_donations SET value = '" .$data['value'] . "' WHERE name= '" . $data['name'] . "'";
	  }
	  mysql_query($sql) or die(mysql_error());
  }
  public function id($data){
		  $array1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		  $array2 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
		  for ($a = 1; $a <= $data['let']; $a++)
    {
						$nbre_1 = rand(0, 25);
						$caractere = $array1[$nbre_1];
						$let1 .= $caractere;
    }
		  for ($b = 1; $b <= $data['num']; $b++)
    {
						$nbre_1 = rand(0, 9);
						$num = $array2[$nbre_1];
						$num2 .= $num;
    }
		  return $num2 .'-'. $let1;
		}
}
?>