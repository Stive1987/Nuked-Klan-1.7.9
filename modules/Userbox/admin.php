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
defined("INDEX_CHECK") or die ("<div style=\"text-align: center;\">You cannot open this page directly</div>");

translate("modules/Userbox/lang/" . $language . ".lang.php");

$visiteur = $user ? $user[1] : 0;

include 'modules/Admin/design.php';
require'modules/Userbox/function.php';
include_jquery();
include_script();
$ModName = basename(dirname(__FILE__));
$level_admin = admin_mod($ModName);
if ($visiteur >= $level_admin && $level_admin > -1) {

	function index() {
		global $nuked, $language, $visiteur;
		$userbox = new userbox();	
		$config = $userbox -> config();
		
        echo " <div class=\"content-box\">\n"
	  	   . " <div class=\"content-box-header\"><h3>" . _ADMINUSERBOX . "</h3></div>\n"
	  	   . " <div class=\"tab-content\" id=\"tab2\">\n"
	  	   . " <form id=\"form_userbox\">\n"
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXMAXMP . "</legend>
    	       	<input value=\"" . $config['max'] . "\" name=\"max\" type=\"text\" maxlength=\"3\" />
               </fieldset>\n"
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXBG1 . "</legend>
  	           	<input value=\"" . $config['bg1'] . "\" name=\"bg1\" type=\"text\" maxlength=\"6\" />
  	           </fieldset>\n"
  
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXBG2 . "</legend>
  	           	<input value=\"" . $config['bg2'] . "\" name=\"bg2\" type=\"text\" maxlength=\"6\" />
  	           </fieldset>\n"
  
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXBG3 . "</legend>
  	           	<input value=\"" . $config['bg3'] . "\" name=\"bg3\" type=\"text\" maxlength=\"6\" />
  	           </fieldset>\n"
  
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXMOZ . "</legend>
  	           	<select name=\"radius\" size=\"1\">
  	           		<option value=\"1\">1 px</option>
  	           		<option value=\"2\">2 px</option>
  	           		<option value=\"3\">3 px</option>
  	           		<option value=\"4\">4 px</option>
  	           		<option value=\"5\">5 px</option>
  	           		<option value=\"6\">6 px</option>
  	           		<option value=\"7\">7 px</option>
  	           		<option value=\"8\">8 px</option>
  	           		<option value=\"9\">9 px</option>
  	           		<option value=\"10\">10 px</option>
  	           	</select>
  	           </fieldset>\n"
			   
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXCOLOR0 . "</legend>
  	           	<input value=\"" . $config['color'] . "\" name=\"color\" type=\"text\" maxlength=\"6\" />
  	           </fieldset>\n"
			   
	  	   . " <fieldset class=\"form_userbox\">
  	           	<legend>" . _ADMINUSERBOXCOLOR1 . "</legend>
  	           	<input value=\"" . $config['colorlink'] . "\" name=\"colorlink\" type=\"text\" maxlength=\"6\" />
  	           </fieldset>\n"
 
	  	   . " </form>\n";
		   
        echo " <div id=\"pref_ok\">" . _PREFOK . "</div>\n"
	  	   . " </div><div id=\"userbox_c\"></div>\n";
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