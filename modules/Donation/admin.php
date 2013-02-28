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
defined("INDEX_CHECK") or die ("<div style=\"text-align: center;\">You cannot open this page directly</div>");
translate("modules/Donation/lang/" . $language . ".lang.php");
$visiteur = $user ? $user[1] : 0;
include 'modules/Admin/design.php';
include_once "modules/Donation/function.php";
include_jquery();
include_script();
$ModName = basename(dirname(__FILE__));
$level_admin = admin_mod($ModName);
if ($visiteur >= $level_admin && $level_admin > -1) {
	function index() {
		global $nuked, $language;
		    $donations = new Donations();   
			$list = $donations -> last(array(
			"order" => "date DESC",
			"fields" => "id, id_users, received, objet, date, devise , valid, transaction",
			"conditions" => "valid = '1'"
		));
        echo " <div class=\"content-box\">\n"
	  	   . " <div class=\"content-box-header\"><h3>" . _ADMINDONATION . "</h3>\n"
	  	   . " <div style=\"text-align:right;\"><a href=\"help/" . $language . "/Donation.php\" rel=\"modal\">\n"
	  	   . " <img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	  	   . " </div></div>\n"
	  	   . " <div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\">\n"
	  	   . " " . _DONATIONINDEX . " | "
	  	   . " <strong><a href=\"index.php?file=Donation&amp;page=admin&amp;op=att\">" . _DONATIONATT . "</a> | "
	  	   . " <a href=\"#\" id=\"donationadd\">" . _DONATIONADD . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=config\">" . _DONATIONCONFIG . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=cat\">" . _DONATIONCAT . "</a>"
	  	   . " </strong></div><div style=\"margin-top:10px;\">\n";
        echo " <table id=\"tabledonate\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
	  	   . " <tr>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _TRANSACTION . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _DATE . "</strong></td>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _NICK . "</strong></td>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _OBJET . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _AMOUNT . "</strong></td>\n"
	  	   . " <td style=\"width: 15%;\" align=\"center\"><strong>" . _EDIT . "</strong></td>\n"
	  	   . " <td style=\"width: 15%;\" align=\"center\"><strong>" . _DEL . "</strong></td></tr>\n";
        foreach($list as $c) {
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' : $c['id_users'] = $users = $donations -> user($c['id_users']);
			($c['devise'] == 'EUR') ? $c['devise'] = '&euro;' : $c['devise'] = $c['devise'];
			($c['devise'] == 'CAD' or $c['devise'] == 'US') ? $c['devise'] = '$' : $c['devise'] = $c['devise'];
			($c['devise'] == 'US') ? $c['devise'] = '$' : $c['devise'] = $c['devise'];
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' :  $c['id_users']  =  $c['id_users'] ;
			 $c['date']= nkDate($c['date']);
        echo " <tr id=\"c" . $c['id'] . "\">\n"
	  	   . " <td>" . $c['transaction'] . "</td>\n"
	  	   . " <td>" . $c['date'] . "</td>\n"
	  	   . " <td>" . $c['id_users'] . "</td>\n"
	  	   . " <td>" . $c['objet'] . "</td>\n"
	  	   . " <td>" . $c['received'] . "&nbsp;" . $c['devise'] . "</td>\n"
	  	   . " <td align=\"center\"><a class=\"edit-donate\" href=\"#" . $c['id'] . "&" . $c['id_users'] . "&" . $c['objet'] . "&" . $c['received'] . "\" title=\"" . _EDITTHISDON . "\">
		                            <img style=\"border: 0;\" src=\"images/edit.gif\" alt=\"\" /></a></td>\n"
	  	   . " <td align=\"center\"><a class=\"del-donate\" href=\"#" . $c['id'] . "&" . $c['objet'] . "&del&" . $c['received'] . "\" title=\"" . _DELTHISDON . "\">
		                            <img style=\"border: 0;\" src=\"images/del.gif\" alt=\"\" /></a></td>\n";
        }		
	}
	function att() {
		global $nuked, $language;
		 $donations = new Donations();   
			$list = $donations -> last(array(
			"order" => "date DESC",
			"fields" => "id, id_users, received, objet, date, devise , valid",
			"conditions" => "valid = '0'"
		));
        echo " <div class=\"content-box\">\n"
	  	   . " <div class=\"content-box-header\"><h3>" . _ADMINDONATION . "</h3>\n"
	  	   . " <div style=\"text-align:right;\"><a href=\"help/" . $language . "/Donation.php\" rel=\"modal\">\n"
	  	   . " <img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	  	   . " </div></div>\n"
	  	   . " <div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\">\n"
	  	   . " <strong><a href=\"index.php?file=Donation&amp;page=admin\">" . _DONATIONINDEX . " |</a></strong> "
	  	   . " " . _DONATIONATT . " | "
	  	   . " <strong><a href=\"#\" id=\"donationadd\">" . _DONATIONADD . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=config\">" . _DONATIONCONFIG . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=cat\">" . _DONATIONCAT . "</a>"
	  	   . " </strong></div><div style=\"margin-top:10px;\">\n";
        echo " <table id=\"tabledonate\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
	  	   . " <tr>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _DATE . "</strong></td>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _NICK . "</strong></td>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _OBJET . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _AMOUNT . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _VALIDER . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _EDIT . "</strong></td>\n"
	  	   . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _DEL . "</strong></td></tr>\n";
        foreach($list as $c) {
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' : $c['id_users'] = $users = $donations -> user($c['id_users']);
			($c['devise'] == 'EUR') ? $c['devise'] = '&euro;' : $c['devise'] = $c['devise'];
			($c['devise'] == 'CAD' or $c['devise'] == 'US') ? $c['devise'] = '$' : $c['devise'] = $c['devise'];
			($c['devise'] == 'US') ? $c['devise'] = '$' : $c['devise'] = $c['devise'];
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' :  $c['id_users']  =  $c['id_users'] ;
			 $c['date']= nkDate($c['date']);
        echo " <tr id=\"c" . $c['id'] . "\">\n"
	  	   . " <td>" . $c['date'] . "</td>\n"
	  	   . " <td>" . $c['id_users'] . "</td>\n"
	  	   . " <td>" . $c['objet'] . "</td>\n"
	  	   . " <td>" . $c['received'] . "&nbsp;" . $c['devise'] . "</td>\n"
	  	   . " <td align=\"center\"><a class=\"valid-donate\" href=\"#" . $c['id'] . "&" . $c['id_users'] . "\" title=\"" . _VALIDTHISDON . "\">
		                            <img style=\"border: 0;\" src=\"modules/Donation/img/valider.png\" alt=\"\" /></a></td>\n"
	  	   . " <td align=\"center\"><a class=\"edit-donate\" href=\"#" . $c['id'] . "&" . $c['id_users'] . "&" . $c['objet'] . "&" . $c['received'] . "\" title=\"" . _EDITTHISDON . "\">
		                            <img style=\"border: 0;\" src=\"images/edit.gif\" alt=\"\" /></a></td>\n"
	  	   . " <td align=\"center\"><a class=\"del-donate\" href=\"#" . $c['id'] . "&" . $c['id_users'] . "&del\" title=\"" . _DELTHISDON . "\">
		                            <img style=\"border: 0;\" src=\"images/del.gif\" alt=\"\" /></a></td>\n";
        }	
	}
	
	function config() {
		global $nuked;
		$donations = new Donations();   
		$config = $donations -> config();
		($config['sandbox'] == '1') ? $sandbox = 'checked=\"checked\"' : '';
		($config['affdevise'] == '1') ? $affdevise = 'checked=\"checked\"' : '';
		($config['affcible'] == '1') ? $affcible = 'checked=\"checked\"' : '';
		($config['affacq'] == '1') ? $affacq = 'checked=\"checked\"' : '';
		($config['flash'] == '1') ? $flash = 'checked=\"checked\"' : '';
		($config['defil'] == '1') ? $defil = 'checked=\"checked\"' : '';
		($config['faq'] == '1') ? $faq = 'checked=\"checked\"' : '';
		($config['barprogr'] == '1') ? $barprogr = 'checked=\"checked\"' : '';
		($config['promdon'] == '1') ? $promdon = 'checked=\"checked\"' : '';
        echo " <div class=\"content-box\">\n"
	  	   . " <div class=\"content-box-header\"><h3>" . _ADMINDONATION . "</h3>\n"
	  	   . " <div style=\"text-align:right;\"><a href=\"help/" . $language . "/Donation.php\" rel=\"modal\">\n"
	  	   . " <img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	  	   . " </div></div>\n"
	  	   . " <div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\">\n"
	  	   . " <strong><a href=\"index.php?file=Donation&amp;page=admin\">" . _DONATIONINDEX . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=att\">" . _DONATIONATT . "</a> | "
	  	   . " <a href=\"#\" id=\"donationadd\">" . _DONATIONADD . "</a> | "
	  	   . " </strong>" . _DONATIONCONFIG . " | "
	  	   . " <strong><a href=\"index.php?file=Donation&amp;page=admin&amp;op=cat\">" . _DONATIONCAT . "</a></strong>"
	  	   . " </div><div style=\"margin-top:10px;\"></div>\n"
		   
		   . " <form id=\"formconfig\">\n"
           . " <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
           . " <tr><td colspan=\"3\"><strong>" . _DONTILE01 . "</strong></td></tr>\n"
           . " <tr><td style=\"width: 30%;\">" . _EMPAY . " :</td><td style=\"width: 5%;\" id=\"email\"></td><td><input type=\"text\" name=\"email\" size=\"50\" value=\"" . $config['email'] . "\" /></td></tr>\n"
           . " <tr><td>" . _SANDBOX . " :</td><td id=\"sandbox\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"sandbox\" value=\"1\" " . $sandbox . " /></td></tr>\n"
           . " <tr><td colspan=\"2\"><strong>" . _DONTILE02 . "</strong></td></tr>\n"
           . " <tr><td>" . _ACFIXE . " :</td><td id=\"fixe\"></td><td><input type=\"text\" name=\"fixe\" maxlength=\"2\" size=\"50\" value=\"" . $config['fixe'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ACDEVIS1 . " :</td><td id=\"devise\"></td><td><input type=\"text\" name=\"devise\" maxlength=\"3\" size=\"50\" value=\"" . $config['devise'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ACDEVIS . " :</td><td id=\"affdevise\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"affdevise\" value=\"1\" " . $affdevise . " /></td></tr>\n"
           . " <tr><td>" . _ACCIBLE . " :</td><td id=\"affcible\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"affcible\" value=\"1\" " . $affcible . " /></td></tr>\n"
           . " <tr><td>" . _ACCCQ . " :</td><td id=\"affacq\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"affacq\" value=\"1\" " . $affacq . " /></td></tr>\n"
           . " <tr><td>" . _ACFLA . " :</td><td id=\"flash\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"flash\" value=\"1\" " . $flash . " /></td></tr>\n"
           . " <tr><td>" . _ACDEFIL . " :</td><td id=\"defil\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"defil\" value=\"1\" " . $defil . " /></td></tr>\n"
           . " <tr><td>" . _ACFAQ . " :</td><td id=\"faq\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"faq\" value=\"1\" " . $faq . " /></td></tr>\n"
           . " <tr><td>" . _ACPROGR . " :</td><td id=\"barprogr\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"barprogr\" value=\"1\" " . $barprogr . " /></td></tr>\n"
           . " <tr><td>" . _ACPROM . " :</td><td id=\"promdon\"></td><td><input class=\"checkbox\" type=\"checkbox\" name=\"promdon\" value=\"1\" " . $promdon . " /></td></tr>\n"
           . " <tr><td>" . _ACLOGO . " :</td><td id=\"logo\"></td><td><input type=\"text\" name=\"logo\" maxlength=\"50\" size=\"50\" value=\"" . $config['logo'] . "\" /></td></tr>\n"
           . " <tr><td colspan=\"2\"><strong>" . _DONTILE03 . "</strong></td></tr>\n"
           . " <tr><td>" . _ADPROM1 . " :</td><td id=\"titcompt\"></td><td><input type=\"text\" name=\"titcompt\" maxlength=\"30\" size=\"50\" value=\"" . $config['titcompt'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM2 . " :</td><td id=\"postal\"></td><td><input type=\"text\" name=\"postal\" maxlength=\"30\" size=\"50\" value=\"" . $config['postal'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM3 . " :</td><td id=\"compte\"></td><td><input type=\"text\" name=\"compte\" maxlength=\"30\" size=\"50\" value=\"" . $config['compte'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM4 . " :</td><td id=\"iban\"></td><td><input type=\"text\" name=\"iban\" maxlength=\"30\" size=\"50\" value=\"" . $config['iban'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM5 . " :</td><td id=\"bic\"></td><td><input type=\"text\" name=\"bic\" maxlength=\"30\" size=\"50\" value=\"" . $config['bic'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM6 . " :</td><td id=\"namebanque\"></td><td><input type=\"text\" name=\"namebanque\" maxlength=\"30\" size=\"50\" value=\"" . $config['namebanque'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM7 . " :</td><td id=\"codebanque\"></td><td><input type=\"text\" name=\"codebanque\" maxlength=\"30\" size=\"50\" value=\"" . $config['codebanque'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM8 . " :</td><td id=\"codeguichet\"></td><td><input type=\"text\" name=\"codeguichet\" maxlength=\"30\" size=\"50\" value=\"" . $config['codeguichet'] . "\" /></td></tr>\n"
           . " <tr><td>" . _ADPROM9 . " :</td><td id=\"rib\"></td><td><input type=\"text\" name=\"rib\" maxlength=\"30\" size=\"50\" value=\"" . $config['rib'] . "\" /></td></tr>\n"
		   
           . " </table><div style=\"text-align: center;margin:10px 0;\"></div>\n"
           . " </form>\n";
	}
	function cat() {
		global $nuked;
		$donations = new Donations();   
		$objet = $donations -> objet();
        echo " <div class=\"content-box\">\n"
	  	   . " <div class=\"content-box-header\"><h3>" . _ADMINDONATION . "</h3>\n"
	  	   . " <div style=\"text-align:right;\"><a href=\"help/" . $language . "/Donation.php\" rel=\"modal\">\n"
	  	   . " <img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	  	   . " </div></div>\n"
	  	   . " <div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\">\n"
	  	   . " <strong><a href=\"index.php?file=Donation&amp;page=admin\">" . _DONATIONINDEX . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=att\">" . _DONATIONATT . "</a> | "
	  	   . " <a href=\"#\" id=\"donationadd\">" . _DONATIONADD . "</a> | "
	  	   . " <a href=\"index.php?file=Donation&amp;page=admin&amp;op=config\">" . _DONATIONCONFIG . "</a> | "
	  	   . " </strong>" . _DONATIONCAT . ""
	  	   . " </div><div style=\"margin-top:10px;\"></div>\n"
           . " <table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
           . " <tr><td colspan=\"3\"><div class=\"notification attention png_bg\"><div>" . _INFOSCAT . "</div></div></td></tr><tr><td>\n"
	  	   . " <tr>\n"
	  	   . " <td style=\"width: 20%;\" align=\"center\"><strong>" . _NAME . "</strong></td><td style=\"width: 5%;\"></td>\n"
	  	   . " <td style=\"width: 65%;\" align=\"center\"><strong>" . _TARGET . "</strong></td>\n"
           . " <td style=\"width: 10%;\" align=\"center\"><strong>" . _DEL . "</strong></td></tr>\n"
		   . " </tr>\n"
		   . " <form id=\"formconfig\">\n";
        if(!empty($objet)) {
        foreach($objet as $obj => $valeur) {
        echo " <tr><td><input type=\"text\" maxlength=\"25\" size=\"50\" value=\"" . $valeur['name'] . "\" disabled /></td><td id=\"" . $valeur['name'] . "\"></td>\n"
	  	   . " <td><input class=\"add-value editj\" type=\"text\" name=\"" . $valeur['name'] . "\" maxlength=\"3\" size=\"50\" value=\"" . $valeur['target'] . "\" /></td>\n"
	  	   . " <td align=\"center\"><a class=\"del-obj\" href=\"#" . $valeur['name'] . "\" title=\"" . _DELTHISOBJ . "\">
		                            <img style=\"border: 0;\" src=\"images/del.gif\" alt=\"\" /></a></td></tr>\n";
        }
		   }
        echo " </form><tr><td><input class=\"add-obj\" type=\"text\" maxlength=\"25\" size=\"50\" name=\"name\"  value=\"\" /></td><td></td>\n"
	  	   . " <td><input class=\"add-value\" type=\"text\" maxlength=\"3\" size=\"50\" value=\"\" /></td>\n"
	  	   . " <td align=\"center\"><a id=\"add-obj\" href=\"#\" title=\"" . _ADDTHISOBJ . "\">
		                            <img style=\"border: 0;\" src=\"modules/Donation/img/plus.png\" alt=\"\" /></a></td></tr>\n";
        echo " </table><div style=\"text-align: center;margin:10px 0;\"></div>\n";
	}

	function jquery() {
		global $nuked;
		$donations = new Donations(); 
		$config = $donations -> config();
		$date = time();
		if($_REQUEST['type'] == 'add' && $_REQUEST['id'] == '') {
			(empty($_REQUEST['objet'])) ? $objet = $nuked['name'] : $objet = $_REQUEST['objet'];
			(empty($_REQUEST['users'])) ? $users = 'Anonyme' : $users = $donations -> iduser($_REQUEST['users']);
				$unique = $donations -> id(array(
	   "let" => "7",
	   "num" => "3"
	   ));
				$donations -> insert(array(
				"name" => "id, id_users, received, objet, date, devise, valid, transaction",
				"value" => "'', '" . $users . "', '" . $_REQUEST['amount'] . "', '" . $objet . "', '" . $date . "', '" . $config['devise'] . "', '1', '" . $unique . "'"
				));
		} else if($_REQUEST['type'] == 'del' && $_REQUEST['id'] != '') {
			$donations -> delete($_REQUEST['id']);
		} else if($_REQUEST['type'] == 'edit' && $_REQUEST['id'] != '') {
			(empty($_REQUEST['objet'])) ? $objet = $nuked['name'] : $objet = $_REQUEST['objet'];
			(empty($_REQUEST['users'])) ? $users = 'Anonyme' : $users = $donations -> iduser($_REQUEST['users']);
			$donations -> update(array(
			"id" => $_REQUEST['id'],
			"id_users" => $users,
			"received" =>  $_REQUEST['amount'],
			"objet" => $objet,
			"date" => $date,
			"devise" => $config['devise']
	   ));
		} else if($_REQUEST['type'] == 'config') {
			$name = $_REQUEST['name']; $value = $_REQUEST['value'];
			
			if($_REQUEST['check'] == 'false') {
			($name == 'sandbox') ? $value = '0':''; ($name == 'affdevise') ? $value = '0':''; ($name == 'affcible') ? $value = '0':'';
			($name == 'affacq') ? $value = '0':''; ($name == 'flash') ? $value = '0':''; ($name == 'defil') ? $value = '0':'';
			($name == 'faq') ? $value = '0':''; ($name == 'barprogr') ? $value = '0':''; ($name == 'promdon') ? $value = '0':'';
			}
			$data = array('name' => $name, 'value' => secu_html(html_entity_decode($value)));
			$donations -> save($data);
		} else if($_REQUEST['type'] == 'valid') {
			$donations -> valid(array(
			"id" => $_REQUEST['id'],
			"valid" => '1'
	   ));
	   } else if($_REQUEST['type'] == 'addobj' && $_REQUEST['name'] != '') {
		   $_REQUEST['name'] = secu_html(html_entity_decode($_REQUEST['name']));
		   $donations -> addobj(array(
			"name" => $_REQUEST['name'],
			"value" => $_REQUEST['value']
	   ));
	   } else if($_REQUEST['type'] == 'delobj' && $_REQUEST['name'] != '') {
		   $_REQUEST['name'] = secu_html(html_entity_decode($_REQUEST['name']));
		   $donations -> delobj(array(
			"name" => $_REQUEST['name']
	   ));
	   } else if($_REQUEST['type'] == 'editobj' && $_REQUEST['name'] != '') {
		   $donations -> editobj(array(
			"name" => $_REQUEST['name'],
			"value" => $_REQUEST['value']
	   ));
	   }
	}
	switch ($_REQUEST['op']) {
		case "jquery":
			jquery($_POST);
			break;
		case "index":
		    admintop();
			index();
			adminfoot();
			break;
		case "cat":
		    admintop();
			cat();
			adminfoot();
			break;
		case "att":
		    admintop();
			att();
			adminfoot();
			break;
		case "config":
		    admintop();
			config();
			adminfoot();
			break;
		default:
			main();
			break;
	}
} else if ($level_admin == -1) {
	echo "<div class=\"notification error png_bg\">\n"
	   . "<div>\n"
	   . "<br /><br /><div style=\"text-align: center;\">" . _MODULEOFF . "<br /><br /><a href=\"javascript:history.back()\"><strong>" . _BACK . "</strong></a></div><br /><br />"
	   . "</div>\n"
	   . "</div>\n";
} else if ($visiteur > 1) {
    echo "<div class=\"notification error png_bg\">\n"
	   . "<div>\n"
	   . "<br /><br /><div style=\"text-align: center;\">" . _NOENTRANCE . "<br /><br /><a href=\"javascript:history.back()\"><strong>" . _BACK . "</strong></a></div><br /><br />"
	   . "</div>\n"
	   . "</div>\n";
} else {
	echo "<div class=\"notification error png_bg\">\n"
	   . "<div>\n"
	   . "<br /><br /><div style=\"text-align: center;\">" . _ZONEADMIN . "<br /><br /><a href=\"javascript:history.back()\"><strong>" . _BACK . "</strong></a></div><br /><br />"
	   . "</div>\n"
	   . "</div>\n";
}
?>