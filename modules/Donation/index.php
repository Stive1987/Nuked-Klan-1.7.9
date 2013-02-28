<?php 
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
// Module Donation cree par Stive                                           //
// www.palacewar.eu & nk-create.be                                          //
// Redistribuation interdite                                                //
// Last changes 09/02/2013                                                  //
// retrait ligne vide + comment                                             //
// -------------------------------------------------------------------------//
defined('INDEX_CHECK') or die('<div style="text-align: center;">Vous ne pouvez pas ouvrir cette page directement</div>');
translate('modules/Donation/lang/' . $language . '.lang.php');
$visiteur = ($user) ? $user[1] : 0;
$ModName = basename(dirname(__FILE__));
$level_access = nivo_mod($ModName);
if ($visiteur >= $level_access && $level_access > -1) {
	 include_once "modules/Donation/function.php";
    function index() {
	   global $nuked, $user, $language;
	   include_jquery();
	   include_script();
	   $donations = new Donations();   
echo " <div id=\"module-donations\">\n"
   . " <div id=\"module-donations-top\">\n"
   . " <div id=\"title\">" . _TITLEINDEXDON . "</div></div>\n"
   . " <div id=\"module-donations-content\"><p align=\"center\">" . _STITLEINDEXDON . "</p></div>\n";
echo " <div id=\"module-donations-index\">\n"
   . " <div id=\"titre\">\n"
   . " <div id=\"date\">" . _DONATIONDATE . "</div>\n"
   . " <div id=\"amount\">" . _DONATIONAMOUNT . "</div>\n"
   . " <div id=\"objet\">" . _DONATIONOBJ . "</div>\n"
   //. " <div id=\"pdf\">" . _DONATIONPDF . "</div>\n"
   . " </div>\n";
	   $list = $donations -> last(array(
	   "order" => "date ASC",
	   "fields" => " received, objet, date, devise , valid, transaction",
				"conditions" => "id_users = '" . $user[0] . "' AND valid = '1' " 
	   ));
	   foreach($list as $c) {
					$c['date'] = nkDate($c['date']);
					($c['devise'] == 'EUR') ? $c['devise'] = '&euro;' : $c['devise'] = $c['devise'];
					($c['devise'] == 'CAD' or $c['devise'] == 'US') ? $c['devise'] = '$':'';
echo " <div class=\"titre\">\n"
   . " <div class=\"date\">" . $c['date'] . "</div>\n"
   . " <div class=\"amount\">" . $c['received'] . " " . $c['devise'] . "</div>\n"
   . " <div class=\"objet\">" . $c['objet'] . "</div>\n"
			//. " <div class=\"pdf\"><a href=\"#\" onclick=\"javascript:window.open('index.php?file=Dons&amp;nuked_nude&amp;op=pdf&amp;id=" . base64_encode($c['transaction']) . "','projet','toolbar=yes,location=no,directories=no,scrollbars=yes,resizable=yes')\"><img style=\"border: 0;\" src=\"images/pdf.gif\" alt=\"\" title=\"" . _PDF . "\" /></a></div>\n"
   . " </div>\n";
					}
echo " </div>\n";
echo " \n"
   . " </div>\n";
$donations = new Donations();   
$cat = $donations -> config();
	}
    function faq() {
       global $user, $nuked, $bgcolor2, $visiteur;
    define('EDITOR_CHECK', 1);
	   include_jquery();
	   include_script();
	   $donations = new Donations();   
	   $config = $donations -> config();
echo " <div id=\"module-donations\">\n"
   . " <div id=\"module-donations-top\">\n"
   . " <div id=\"title\">" . _DONATIONFAQ1 . "</div></div>\n"
   . " <div id=\"module-donations-content\">\n"
   . " <div id=\"msgfaq\">\n";
echo html_entity_decode($config['msgfaq']);
echo " </div>\n";
if($user[1] == '9') {
echo " <div id=\"msgfaqtxt\">\n"
   . " <form method=\"post\" id=\"module-donations-form-msgfaq\">\n"
   . " <input type=\"hidden\" name=\"name\" value=\"msgfaq\" />\n"
   . " <textarea id=\"e_basic\" name=\"value\" cols=\"58\" rows=\"10\">" . $config['msgfaq'] . "</textarea>\n"
   . " <input type=\"submit\" alt=\"Enregistrer\" value=\"Enregistrer\" class=\"module-donations-button\" />\n"
   . " </form>\n"
   . " </div>\n";
}
else {
echo " <div id=\"msgfaqtxt\">" . html_entity_decode($config['msgfaq']) . "</div>\n";
}

echo " </div>\n"
   . " </div>\n";
	}
    function troth() {
        global $nuked, $user;
	   include_jquery();
	   include_script();
	   $donations = new Donations(); 
				$objet = $donations -> objet();  
	   $config = $donations -> config();
				$unique = $donations -> id(array(
	   "let" => "7",
	   "num" => "3"
	   ));
		$arraycount = count($objet);
echo " <div id=\"module-donations\">\n"
   . " <div id=\"module-donations-top\">\n"
   . " <div id=\"title\">" . _ADPROM0 . "</div></div>\n"
   . " <div id=\"module-donations-content\">\n";

if(!empty($config['titcompt']))    { echo " <span class=\"module-donations-troth-one\">" . _ADPROM1 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['titcompt'] . "</span>
											</span>\n"; }

if(!empty($config['postal']))      { echo " <span class=\"module-donations-troth-one\">" . _ADPROM2 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['postal'] . "</span>
                                            </span>\n"; }

if(!empty($config['compte']))      { echo " <span class=\"module-donations-troth-one\">" . _ADPROM3 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['compte'] . "</span>
											</span>\n"; }

if(!empty($config['iban']))        { echo " <span class=\"module-donations-troth-one\">" . _ADPROM4 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['iban'] . "</span>
											</span>\n"; }

if(!empty($config['bic']))         { echo " <span class=\"module-donations-troth-one\">" . _ADPROM5 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['bic'] . "</span>
											</span>\n"; }

if(!empty($config['namebanque']))  { echo " <span class=\"module-donations-troth-one\">" . _ADPROM6 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['namebanque'] . "</span>
											</span>\n"; }
											
if(!empty($config['codebanque']))  { echo " <span class=\"module-donations-troth-one\">" . _ADPROM7 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['codebanque'] . "</span>
											</span>\n"; }
											
if(!empty($config['codeguichet'])) { echo " <span class=\"module-donations-troth-one\">" . _ADPROM8 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['codeguichet'] . "</span>
											</span>\n"; }
											
if(!empty($config['rib']))         { echo " <span class=\"module-donations-troth-one\">" . _ADPROM9 . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $config['rib'] . "</span>
											</span>\n"; }

echo " <form id=\"module-donations-form-index\">\n";
                                     echo " <span class=\"module-donations-troth-one\">" . _PSEUDO . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">" . $user[2] . "</span>
											</span>\n";

if($arraycount != 1)               { echo " <span class=\"module-donations-troth-one\">" . _DONATIONOBJ . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">\n"
   . " <select name=\"item\" id=\"module-donations-item\">\n";
	   foreach($objet as $obj => $valeur) {
echo " <option value=\"" . $valeur['name'] . "\">" . $valeur['name'] . "</option>\n";
	   }
echo " </select>\n"
   . " </span></span>\n";
}
                                     echo " <span class=\"module-donations-troth-one\">" . _DONATIONAMOUNT . " :</span><span class=\"module-donations-troth-two\">
                                            <span style=\"margin-left:15px;\">\n";
if($config['fixe'] == '') {
                                     echo " <input id=\"module-donations-amount\" name=\"amounti\" type=\"text\" value=\"\" />\n";
} else {
                                     echo " <input id=\"module-donations-amount\" name=\"amounti\" type=\"text\" value=\"" . $config['fixe'] . "\" readonly=\"readonly\" />\n";

}
if ($config['affdevise'] == '1') {
echo " <input name=\"currency_code\" id=\"module-donations-currency\" type=\"text\" value=\"" . $config['devise'] . "\" readonly=\"readonly\" />\n";
}
echo " </span></span>\n"
	. " <input type=\"hidden\" name=\"trans\" value=\"" . base64_encode($unique) . "\"/>\n"
   . " <input type=\"submit\" alt=\"troth\" value=\"" . _ADPROM10 . "\" class=\"module-donations-button\" />\n"
   . " </form>\n";

echo " </div>\n"
   . " </div>\n";
	}
	function addtroth($value, $id, $objet) {
        global $nuked, $user;
		if(!empty($value) && !empty($id))
	{
	   $donations = new Donations();
				$config = $donations -> config();
				$date = time();
				
				$donations -> insert(array(
				"name" => "id, id_users, received, objet, date, devise, valid, transaction",
				"value" => "'', '" . $user[0] . "', '" . $value . "', '" . $objet . "', '" . $date . "', '" . $config['devise'] . "', '0', '" . $id = base64_decode($id) . "'"
				));				
		}
		
}
	function validate() {		
		
	   $donations = new Donations();
		$config = $donations -> config();		
	   		
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = trim(urlencode(stripslashes($value)));
$req .= "&$key=$value";
}
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: www.paypal.com:80\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
($config['sandbox'] == '1') ? $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30) : $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
$mc_gross = $_POST['mc_gross'];
$id_user = $_POST['custom'];
$objet = $_POST['option_name1'];
$id = $_POST['option_name2'];
$user = $_POST['item_name'];
$devise = $_POST['mc_currency'];
$date = time();
if (!$fp) {
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
				$donations -> insert(array(
				"name" => "id, id_users, received, objet, date, devise, valid, transaction",
				"value" => "'', '" . $id_user . "', '" . $mc_gross . "', '" . $objet . "', '" . $date . "', '" . $config['devise'] . "', '1', '" . $id . "'"
				));
}
}
fclose ($fp);
}
}
	function jquery($name, $value) {
        global $nuked, $user;
		$donations = new Donations();
		$data = array('name' => $name, 'value' => secu_html(html_entity_decode($value)));
		$donations -> save($data);
		echo html_entity_decode($value);
	}
    function pdf($id) {
       global $nuked, $user;
	   $id = base64_decode($id);
	   $donations = new Donations();   
	   $config = $donations -> config();
				$list = $donations -> last(array(
	   "fields" => " id_users, received, objet, date, devise , valid, transaction",
				"conditions" => "transaction = '" . $id . "'"
	   ));
				
		foreach($list as $c) {
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' : $c['id_users'] = $users = $donations -> user($c['id_users']);
											
    $text .= ' <h1 align="center">Promesse de don' . $list['received'] . '</h1>
               <hr />
               <div align="center">
               <table align="center" border="0" cellpadding="5" cellspacing="5" style="width: 100%;">
															<tr><td>Pseudo :</td><td> ' . $c['id_users'] . '</td></tr>
															<tr><td>IP:</td><td>' . $user[3] . '</td></tr>
															<tr><td>Numero de transaction</td><td>' . $id . '</td></tr>
															<tr><td><br /></td><td><br /></td></tr>
															<tr><td><br /></td><td><br /></td></tr>
															<tr><td colspan="2">Se propose de verser la somme de ' . $c['received'] . ' ' . $c['devise'] . ' a la date du 20/01/2012 sur le</td></tr>
															<tr><td><br /></td><td><br /></td></tr>';
		}
	
if(!empty($config['titcompt']))    { $text .= " <tr><td>Titulaire du compte :</td><td>" . $config['titcompt'] . "</td></tr>\n"; }

if(!empty($config['postal']))      { $text .= "  <tr><td>Adresse Postal :</td><td>" . $config['postal'] . "</td></tr>\n"; }

if(!empty($config['compte']))      { $text .= "  <tr><td>N° de compte :</td><td>" . $config['compte'] . "</td></tr>\n"; }

if(!empty($config['iban']))        { $text .= "  <tr><td>IBAN :</td><td>" . $config['iban'] . "</td></tr>\n"; }

if(!empty($config['bic']))         { $text .= "  <tr><td>SWIFT / BIC :</td><td>" . $config['bic'] . "</td></tr>\n"; }

if(!empty($config['namebanque']))  { $text .= "  <tr><td>Banque :</td><td>" . $config['namebanque'] . "</td></tr>\n"; }
											
if(!empty($config['codebanque']))  { $text .= "  <tr><td>Code Banque :</td><td>" . $config['codebanque'] . "</td></tr>\n"; }
											
if(!empty($config['codeguichet'])) { $text .= "  <tr><td>Code Guichet :</td><td>" . $config['codeguichet'] . "</td></tr>\n"; }
											
if(!empty($config['rib']))         { $text .= "  <tr><td>Clé RIB :</td><td>" . $config['rib'] . "</td></tr>"; }
			
    $text .= '</table><br /><br />
														<table align="center" border="0" cellpadding="5" cellspacing="5" style="width: 100%;">
														<tr>
														<td>D&egrave;s que nous aurons re&ccedil;u votre don, vous serais valid&eacute;e dans notre base de donn&eacute;es<br /><br />' . $nuked['name'] . ' vous remercie</td>
														</tr></table><br />&nbsp;</div>';
        $text = "<br />" . $text;
        $text = str_replace("&quot;", "\"", $text);
        $text = str_replace("&#039;", "'", $text);
        $text = str_replace("&agrave;", "à", $text);
        $text = str_replace("&acirc;", "â", $text);
        $text = str_replace("&eacute;", "é", $text);
        $text = str_replace("&egrave;", "è", $text);
        $text = str_replace("&ecirc;", "ê", $text);
        $text = str_replace("&ucirc;", "û", $text);
        $text = preg_replace('#\r\n\t#', '', $text);
        $text = str_replace('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>', '</page><page>', $text);
        $texte = '<page>'.$text.'</page>';
        $_REQUEST['file'] = $sitename.'_'.$title;
        $_REQUEST['file'] = str_replace(' ','_',$_REQUEST['file']);
        $_REQUEST['file'] .= '.pdf';
        require_once('Includes/html2pdf/html2pdf.class.php');
        try
        {
            $html2pdf = new HTML2PDF('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('dejavusans');
            $html2pdf->writeHTML(utf8_encode($texte), isset($_GET['vuehtml']));
            $html2pdf->Output($title.'.pdf');
        }
        catch(HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    } 
		
    function css() {
        global $nuked, $bgcolor1, $bgcolor2, $bgcolor3;
	header("Content-type: text/css; charset=UTF-8");
	header('Expires: ' . gmdate('D, d M Y H:i:s ',time() + (60 * 60 * 24 * 10)) . ' GMT');
?>
#block-donations {
	overflow: hidden;
}
#block-donations a {
    text-decoration: none;
}
#block-donations img {
    border: 0;
}
#module-donations-bar {
	width: 100%;
	height: 28px;
	overflow: hidden;
	text-align: center;
}
#module-donations-bar > .module-donations-bar {
	width: 90%;
	height: 15px;
	margin: 5px auto;
	border: 1px solid #cccccc;
	padding: 1px;
    -webkit-box-shadow:0 1px 4px #cccccc, 0 0 10px #cccccc inset;
    -moz-box-shadow:0 1px #cccccc, 0 0 10px #cccccc inset;
    box-shadow:0 1px 4px #cccccc, 0 0 10px #cccccc inset;
}
#module-donations-bar > .module-donations-bar:nth-child(n+2) { display: none; }

.module-donations-bar > .module-donations-percentage {
	width: 0%;
	height: 15px;
	display: block;
	line-height: 14px;
	font-family: Verdana;
	font-size: 10px;
	color: #000;
	text-align: right;
	max-width: 100%;
}
#module-donations-target, #module-donations-acquired {
	width: 90%;
	margin: 5px auto;
	overflow: hidden;
}

#module-donations-donor {
	position: relative;
	overflow: hidden;
	height: 25px;
	width: 100%;
	text-align: center;
}
#module-donations-donor > .module-donations-donor-name {
	position: absolute;
	top: 25px;
	height: 25px;
	width: 100%;
}
#module-donations-form {
	width: 100%;
	text-align: center;
}
#module-donations-form > #module-donations-amount {
	width: 55%;
	text-align: right;
	padding: 2px;
	border: 1px solid #cccccc;
}
#module-donations-form > #module-donations-amount-full {
	width: 88%;
	text-align: center;
	padding: 1px;
	border: 1px solid #cccccc;
}
#module-donations-form > #module-donations-currency {
	width: 30%;
	padding: 2px;
	border: 1px solid #cccccc;
    text-align: center;
}
#module-donations-form > #module-donations-logo {
	max-width: 100%;
	margin: 10px 0;
}
#module-donations-form > #module-donations-item {
	width: 90%;
	border: 1px solid #cccccc;
	margin: 5px 0;
	text-align: center;
}
#module-donations-form input[type="image"] {
	margin-top: 5px;
    }
#module-donations {
	margin: 5px auto;
	left:0;
	right: 0;
}
#module-donations-top {
	border: 1px solid <?php echo $bgcolor3 ?>;
}
#module-donations-top > #title {
	background: <?php echo $bgcolor3 ?>;
	/*color: #000000;*/
	font-weight: bold;
	text-align: center;
	padding: 4px 0;
}
#module-donations > #module-donations-content {
	background: <?php echo $bgcolor1 ?>;
	padding: 10px;
   border: 1px solid <?php echo $bgcolor3 ?>;
	/*color: #000000;*/
}
.module-donations-troth-one {
	display:inline-block;font-weight: bold;width:50%;text-align:right;padding: 2px 0;
}
.module-donations-troth-two {
	display:inline-block;width:50%;padding: 2px 0;
}
.module-donations-button {
	display: block;
	background: #FFF;
	border: 1px solid <?php echo $bgcolor3 ?>;
	margin: 5px auto;
	padding: 1px 10px;
	color: #000;
	transition-property: background-color;
	transition-duration: 1s;
}

.module-donations-button:hover {
	background: <?php echo $bgcolor1 ?>;
	color: <?php echo $bgcolor3 ?>;
}

#module-donations-index {
	border: 1px solid <?php echo $bgcolor3 ?>;
	padding: 1px;
	display:table;
   margin: 10px auto;
   width: 99%;
}
#module-donations-index > #titre {
	background: <?php echo $bgcolor3 ?>;
	/*color: #000000;*/
	font-weight: bold;
	text-align: center;
	border-bottom: 1px solid <?php echo $bgcolor3 ?>;
	display: table-header-group;
}
#titre > #date, #titre > #amount, #titre > #objet, #titre > #pdf {
	width: 40%;
	text-align: center;
	border-right: 1px solid <?php echo $bgcolor2 ?>;
	display:table-cell;
}
#titre > #objet {
	width: 20%;
   border-right: 0;
}
#titre > #pdf {
	width: 5%;
}
#module-donations-index > .titre {
	background: <?php echo $bgcolor2 ?>;
	/*color: #000000;*/
	text-align: center;
	border-bottom: 1px solid <?php echo $bgcolor1 ?>;
	display: table-header-group;
}
.titre > .date {
	width: 40%;
	text-align: center;
	border-right: 1px solid <?php echo $bgcolor1 ?>;
	display:table-cell;
	padding: 1px;
}
.titre > .date, .titre > .amount, .titre > .objet, .titre > .pdf {
	width: 40%;
	text-align: center;
	border-right: 1px solid <?php echo $bgcolor2 ?>;
	display:table-cell;
	padding: 1px;
}
.titre > .objet {
	width: 20%;
}
.titre > .pdf {
	width: 5%;
}
.titre > .pdf img {
	vertical-align:middle;
}
#module-donations-index > .titre:nth-child(odd) {
	background: <?php echo $bgcolor1 ?>;
}

#bgoverlay {
	display: none;
	background: #000; 
	position: fixed; left: 0; top: 0; 
	z-index: 10;
	width: 100%; height: 100%;
	opacity: .30;
	z-index: 9999;
}
#confirmBox {
	width: 460px;
	position: fixed;
	left: 50%;
	top: 50%;
	margin: -180px 0 0 -230px;
	box-shadow: rgba(0,0,0, 0.3) 0px 0px 30px;
	-moz-box-shadow: rgba(0,0,0, 0.3) 0px 0px 30px;
	-webkit-box-shadow: rgba(0,0,0, 0.3) 0px 0px 30px;
	z-index: 9999;
	background-color: #eeeeee;
	display: none;
}
#confirmBox .h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	background: #000;
	padding: 0px 25px;
	height: 41px;
	line-height: 41px;
	text-shadow: none;
	color: #FFF;
	letter-spacing: 0.3px;
	margin: 0px 0px 13px;
}
.button-yes, .button-no {
	margin-top: 5px;
	position: relative;
	z-index: 1;
	display: inline-block;
	padding: 5px 0;
	text-align: center;
	cursor: pointer;
	outline: medium none;
	border: 1px solid rgb(105, 164, 77);
	background-color: rgb(155, 198, 82);
	box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.1);
	transition: all 0.2s ease-out 0s;
	font-weight: bold;
	font-size: 11px;
}
.button-no {
	border-color: #a93a25;
	color: #fff;
	background-color: #d74543;
}
.button-yes:hover, .button-yes:focus, .button-yes:active, .button-no:hover, .button-no:focus, .button-no:active {
	background-position: 0 59px;
	color: #ffffff;
}
.button-no:hover, .button-no:focus, .button-no:active {
	color: #000;
}
#confirmBox form {
	margin: 15px;
}
#confirmBox legend {
	display: inline-block;
}
#confirmBox fieldset {
    border: 1px solid #e2dcdc;
    padding:0 5px 10px 5px;
}
#confirmBox input {
	width: 100%;
}
*html #bgoverlay {
	position: absolute;
}
*html #confirmBox {
	position: absolute;
}
<?php
	}
    switch ($_REQUEST['op']) {
		
        case 'faq':
        opentable();
        faq();
        closetable();
        break;
		
        case 'troth':
        opentable();
        troth();
        closetable();
        break;
								
        case 'addtroth':
        addtroth($_REQUEST['value'], $_REQUEST['id'], $_REQUEST['objet']);
        break;
		
        case 'index':
        opentable();
        index();
        closetable();
        break;
		
        case 'jquery':
        jquery($_REQUEST['name'], $_REQUEST['value']);
        break;
		
        case 'validate':
        validate();
        break;
		
        case 'pdf':
        pdf($_REQUEST['id']);
        break;
		
        case 'css':
        css();
        break;
		
        default:
        index();
        break;
    } 
} else if ($level_access == -1) {
    opentable();
    echo '<br /><br /><div style="text-align: center;">' . _MODULEOFF . '<br /><br /><a href="javascript:history.back()"><b>' . _BACK . '</b></a><br /><br /></div>';
    closetable();
} else if ($level_access == 1 && $visiteur == 0) {
    opentable();
    echo '<br /><br /><div style="text-align: center;">' . _USERENTRANCE . '<br /><br /><b><a href="index.php?file=User&amp;op=login_screen">' . _LOGINUSER . '</a> | <a href="index.php?file=User&amp;op=reg_screen">' . _REGISTERUSER . '</a></b><br /><br /></div>';
    closetable();
} else {
    opentable();
    echo '<br /><br /><div style="text-align: center;">' . _NOENTRANCE . '<br /><br /><a href="javascript:history.back()"><b>' . _BACK . '</b></a><br /><br /></div>';
    closetable();
}

?>