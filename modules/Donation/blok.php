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
global $nuked, $user, $language, $user;
translate('modules/Donation/lang/' . $language . '.lang.php');
$visiteur = ($user) ? $user[1] : 0;
$sql = mysql_query("SELECT active FROM " . BLOCK_TABLE . " WHERE bid='$bid'");
list($active) = mysql_fetch_array($sql);
if ($active == 1 || $active == 2) {
	   include_once "modules/Donation/function.php";
	   include_jquery();
	   include_script();
	   $donations = new Donations();
	   $config = $donations -> config();
	   $objet = $donations -> objet();
	   $target = $donations -> amount("target");
	   $received = $donations -> sum(array(
	   ));
	   $list = $donations -> last(array(
	   "order" => "date ASC",
	   "fields" => " id_users, received, objet, date, devise , valid"
	   ));
		$unique = $donations -> id(array(
	   "let" => "7",
	   "num" => "3"
	   ));
		$arraycount = count($objet);		
        ($config['devise'] == 'EUR') ? $dev = '&euro;' :  $dev = $config['devise'];
	    ($dev == 'CAD' or $dev == 'US') ? $dev = '$' : '';
echo " <div id=\"block-donations\">\n";
if ($received[0] != 0) {
	if($config['defil'] == '1') {
		echo " <div id=\"module-donations-donor\">\n"
           . " <div class=\"module-donations-donor-name\"><strong>" . _LASTREQUIRE . "</strong></div>\n";
		foreach($list as $c) {
			(empty($c['id_users'])) ? $c['id_users'] = 'Anonyme' : $c['id_users'] = $users = $donations -> user($c['id_users']);
			($c['devise'] == 'EUR') ? $c['devise'] = '&euro;' : $c['devise'] = $c['devise'];
			($c['devise'] == 'CAD' or $c['devise'] == 'US') ? $c['devise'] = '$':'';
			echo " <div class=\"module-donations-donor-name\"><strong>" . $c['id_users'] . "</strong> " . $c['received'] . " " . $c['devise'] . "</div>\n";
		}
echo " </div>\n";
	}
}
if($config['affcible'] == '1') {
	echo " <div id=\"module-donations-target\"><span style=\"float:left;\">" . _DONATIONCIBLED . "</span><span style=\"float:right;\">" . $target[0] . " " . $dev . "</span></div>\n";
}
if($config['affacq'] == '1') {
	($received[0] == 0 or (empty($received[0]))) ? $received[0] = 0 : $received[0] = $received[0];
	echo " <div id=\"module-donations-acquired\"><span style=\"float:left;\">" . _DONATIONDACQ . "</span> <span style=\"float:right;\">" . $received[0] . " " . $dev . "</span></div>\n";
}
if($config['flash'] == '1') {
	($target[0] == '') ?  $target = 0 : $target = $target[0];
	($received[0] == '') ?  $received = 0 : $received = $received[0];
	($target == 0 and $received == 0) ?  $brpct = 0 : $brpct = round(($received/$target)*100);
	echo " <div style=\"text-align:center\">\n"
	   . " <object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" width=\"150\" height=\"110\">\n"
	   . " <param name=\"movie\" value=\"modules/Donation/flash/flash.swf?pourcent=" . $brpct . "\">\n"
	   . " <param name=\"quality\" value=\"high\">\n"
	   . " <param name=\"wmode\" value=\"transparent\">\n"
	   . " <param name=\"salign\" value=\"T\">\n"
	   . " <param name=\"salign\" value=\"center\">\n"
	   . " <param name=\"SCALE\" value=\"autohigh\">\n"
	   . " <!--[if !IE]>-->\n"
	   . " <object type=\"application/x-shockwave-flas\" data=\"modules/Donation/flash/flash.swf?pourcent=" . $brpct . "\" width=\"150\" height=\"110\">\n"
	   . " <param name=\"movie\" value=\"modules/Donation/flash/flash.swf?pourcent=" . $brpct . "\">\n"
	   . " <param name=\"quality\" value=\"high\">\n"
	   . " <param name=\"wmode\" value=\"transparent\">\n"
	   . " <param name=\"salign\" value=\"T\">\n"
	   . " <param name=\"salign\" value=\"center\">\n"
	   . " <param name=\"SCALE\" value=\"autohigh\">\n"
	   . " <!--[if !IE]>--> \n"
	   . " <a href=\"http://www.adobe.com/go/getflash\"> <img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Obtenir Adobe Flash Player\" /></a> \n"
	   . " <!--[if !IE]>-->\n"
	   . " </object>\n"
	   . " <!--<![endif]-->\n"
	   . " </object>\n"
	   . " </div>\n";
}
if($config['barprogr'] == '1') {
	echo " <div id=\"module-donations-bar\">\n";
	foreach($objet as $obj => $valeur) {
	   $receiveds = $donations -> sum(array(
	   "conditions" => "WHERE objet = '" . $valeur['name'] . "'"
	   ));
	   ($valeur['target'] == 0 or $valeur['target'] == '' ) ?  $valeur['target'] = 0 : $valeur['target'] = $valeur['target'];
	   ($receiveds[0] == 0 or $receiveds[0] == '' ) ?  $receiveds[0] = 0 : $receiveds[0] = $receiveds[0];  
	   ($receiveds[0] == 0 or $valeur['target'] == 0 ) ?  $brpct = 0 : $brpct = round(($receiveds[0]/$valeur['target'])*100);

	echo " <div class=\"module-donations-bar\" id=\"module-donations-bar-" . $obj . "\">\n"
   	   . " <div class=\"module-donations-percentage\">" . $brpct . "%</div>\n"
       . " </div>\n";
	}
echo " </div>\n";
}
if($config['sandbox'] == '1') {
	echo " <form id=\"module-donations-form\" action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
} else {
	echo " <form id=\"module-donations-form\" action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\">\n";
}
if ($user[2]) {
	echo " <input type=\"hidden\" name=\"item_name\" value=\"" . $user[2] . "\" />\n"
    . " <input type=\"hidden\" name=\"custom\" value=\"" . $user[0] . "\" />\n";
} else {
	echo " <input type=\"hidden\" name=\"item_name\" value=\"Anonyme\" />\n";
}
	echo " <input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />\n"
       . " <input type=\"hidden\" name=\"business\" value=\"" . $config['email'] . "\" />\n"
       . " <input type=\"hidden\" name=\"tax\" value=\"0\" />\n"
       . " <input type=\"hidden\" name=\"no_shipping\" value=\"1\" />\n"
       . " <input name=\"notify_url\" type=\"hidden\" value=\"" . $nuked['url'] . "/index.php?file=Donation&amp;nuked_nude&amp;op=validate\" />\n"
       . " <input type=\"hidden\" name=\"bn\" value=\"IC_Sample\" />\n"
       . " <input type=\"hidden\" name=\"on1\" value=\"" . base64_encode($unique) . "\" />\n";
if($arraycount != 1) {
	echo " <select name=\"on0\" id=\"module-donations-item\">\n";
	foreach($objet as $obj => $valeur) {
	echo " <option data-id=\"" . $obj . "\" value=\"" . $valeur['name'] . "\">" . $valeur['name'] . "</option>\n";
	}
	echo " </select>\n";
} else {
	echo " <input type=\"hidden\" name=\"on0\" value=\"". $nuked['name']  ."\" />\n";
} 
($config['affdevise'] != '1') ? $amount_full = '-full' : $amount_full = '';
if($config['fixe'] == '') {
	echo " <input id=\"module-donations-amount" . $amount_full . "\" name=\"amount\" type=\"text\" value=\"\" />\n";
} else {
	echo " <input id=\"module-donations-amount" . $amount_full . "\" name=\"amount\" type=\"text\" value=\"" . $config['fixe'] . "\" readonly=\"readonly\" />\n";
}
if ($config['affdevise'] == '1') {
	echo " <input name=\"currency_code\" id=\"module-donations-currency\" type=\"text\" value=\"" . $config['devise'] . "\" readonly=\"readonly\" />\n";
}
else {
	echo " <input name=\"currency_code\" type=\"hidden\" id=\"module-donations-currency\" value=\"" . $config['devise'] . "\" />\n";
}	   
echo " <input type=\"hidden\" name=\"return\" value=\"" . $nuked['url'] . "/index.php?file=Donation\" />\n";

echo " <input id=\"module-donations-logo\" type=\"image\" src=\"" . $config['logo'] . "\" name=\"submit\" alt=\"PayPal - Don\" />\n"
   . " </form>\n";
if ($config['faq'] == '1') {
	echo " <div style=\"font-size:10px;\"><a href=\"index.php?file=Donation&amp;op=faq\" title=\"FAQ Don\">&raquo;" . _DONATIONFAQ . "</a></div>\n";
}
if ($config['promdon'] == '1') {
	echo " <div style=\"font-size:10px;\"><a href=\"index.php?file=Donation&amp;op=troth\" title=\"Promessse Don\">&raquo;" . _DONATIONAUTE . "</a></div>\n";
}
 echo " </div>\n";
}
?>