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
defined('INDEX_CHECK') or die('<div style="text-align:center;">You cannot open this page directly</div>');

global $language, $user;
translate('modules/Userbox/lang/'.$language.'.lang.php');


function post_message(){
	global $user;
	define('EDITOR_CHECK', 1);
	
		require'modules/Userbox/function.php';
		include_jquery();
		$userbox = new userbox();
		$select_user = $userbox -> select_user();
	
	if (!empty($_REQUEST['for']) && preg_match("`^[a-zA-Z0-9]+$`", $_REQUEST['for'])){
		$pseudo = $userbox -> users(array("check" => "pseudo", "fields" => "pseudo","id" => $_REQUEST['for']));
	}
	
	if (!empty($_REQUEST['titre'])){
		$_REQUEST['titre'] = stripslashes($_REQUEST['titre']);
		if (!preg_match("/\bRe:\b/i", $_REQUEST['titre'])) $title = "Re:" . $_REQUEST['titre'];
		else $title = $_REQUEST['titre'];
	}
	
	if (!empty($_REQUEST['message'])){
		$_REQUEST['message'] = secu_html(html_entity_decode($_REQUEST['message']));
		$_REQUEST['message'] = stripslashes($_REQUEST['message']);
		$reply = '<br /><table style="background:'.$bgcolor3.';" cellpadding="3" cellspacing="1" width="100%" border="0"><tr><td style="background: #FFF;color: #000"><b>'.$pseudo.' :</b><br />'.$_REQUEST['message'].'</td></tr></table><br />';
	}
	
	if (empty($_REQUEST['titre'])){	
		$cle = md5(date(rand(), true));
	} else { $cle = $_REQUEST['idunique']; }
	$_REQUEST['message'] = secu_html(html_entity_decode($_REQUEST['message']));
		
	echo '<form method="post" action="index.php?file=Userbox&amp;op=send_message">
		  <div id="module_userbox" class="userbox_bg_2">
			<span class="title userbox_bg_1">'._POSTMESS.'</span>
			<span class="subtitle userbox_bg_2">';
			if (empty($_REQUEST['titre'])){	
	    echo '<span class="for"><p>'._USERFOR.' :</p>';
			  echo '<select name="user_for" >';
			  foreach($select_user as $a) {
				  echo '<option value="' . $a['id'] . '">' . $a['pseudo'] . '</option>';
			  }
			  echo '</select>';
	    echo '</span>
			  <span class="subject"><p>'._SUBJECT.' :</p><input type="text" name="titre" size="30" value="'.$title.'" /></span>';
			} else {
	    echo '<span class="for"><p>'. _RESPTO .' :</p>' . $user[2] . '</span>
		      <input type="hidden" name="user_for" value="'.$_REQUEST['for'].'" />
		      <span class="subject"><p>'._SUBJECT.' :</p>' . html_entity_decode($title) . '</span>
			  <input type="hidden" name="titre" value="'.htmlentities($title).'" />';
			}
	  echo '</span>
			' . $_REQUEST['message'] . '
			<textarea id="e_basic" name="message" cols="65" rows="10"></textarea>
			<div style="text-align: center;">
              <input type="hidden" name="oldmessage" value="' .  htmlentities($_REQUEST['message']) . '" />
			  <input class="button userbox_bg_3 userbox_border_1" type="submit" name="send" value="'._SEND.'" />
			  <input class="button userbox_bg_3 userbox_border_1" type="button" value="'._CANCEL.'" onclick="javascript:history.back()" />
			</div>
		  </div>
		  <input type="hidden" name="idunique" value="' . $cle . '" />
		  </form>';
}

function send_message($titre, $user_for, $message, $idunique){
	global $user, $nuked;
	
		require'modules/Userbox/function.php';
		include_jquery();
		$userbox = new userbox();
	
	if (empty($titre) || empty($user_for) || empty($message)){
		    opentable();
            echo '<div style="display:block;margin:20px 5px;" id="pref_ok">'._EMPTYFIELD.'</div>';
			redirect('index.php?file=Userbox', 2);
			closetable();
			footer();
			exit();
	}else{
		
		if (!empty($user_for) && ctype_alnum($user_for)) {
			
			$nb = $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $user_for));
		}
		else $nb = 0;
		
		if (empty($nb)){
			echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'._UNKNOWMEMBER . '</div>';
			redirect('index.php?file=Userbox', 2);
		}else{
			$flood = mysql_query("SELECT date FROM " . USERBOX_TABLE . " WHERE user_from = '" . $user[0] . "' ORDER BY date DESC LIMIT 0, 1");
			list($flood_date) = mysql_fetch_array($flood);
			$anti_flood = $flood_date + $nuked['post_flood'];
			$date = time();
			
			if ($date < $anti_flood){
				opentable();
				echo '<div style="display:block;margin:20px 5px;" id="pref_ok">'._NOFLOOD.'</div>';
				redirect('index.php?file=Userbox', 2);
				closetable();
				footer();
				exit();
			}
			
			if ($check_conf_ump = $userbox -> check_conf(array("fields" => 'ump',"id" => $user_for)) == 1) {
				
				echo '<div style="display:block;margin:20px 5px;" id="pref_ok">' ._NOMPSEND. '</div>';
				redirect('index.php?file=Userbox', 2);
				closetable();
				footer();
				exit();
			} else {
			
			$message = secu_html(html_entity_decode($message));
			//$titre = mysql_real_escape_string(stripslashes($titre));
			$message = mysql_real_escape_string(stripslashes($message));
			$user_for = mysql_real_escape_string(stripslashes($user_for));
			
			$message = '<div class="module_userbox_table_tr_show_c">' . $message . '</div>';
			
			if (!empty($_REQUEST['oldmessage'])) {
				$message =  $_REQUEST['oldmessage'] . $message; 
				$userbox -> delete(array(
				"conditions" => "idunique  = '$idunique'",
				));
			}
			
			$userbox -> insert(array(
			"name" => "`mid` , `user_from` , `user_for` , `titre` , `message` , `date` , `status`, `idunique`, `archive`",
			"value" => "'' , '{$user[0]}' , '$user_for' , '$titre' , '$message' , '$date' , '0', '$idunique', '0'"
			));
			
			$userbox -> insert_send(array(
			"name" => "`mid` , `user_from` , `user_for` , `titre` , `message` , `date` , `idunique`",
			"value" => "'' , '{$user[0]}' , '$user_for' , '$titre' , '$message' , '$date' , '$idunique'"
			));
			
			sendmail($titre, $pseudo = $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $user_for)), $mail = $userbox -> users(array("check" => "id", "fields" => "mail","id" => $user_for)), $check = $userbox -> users(array("check" => "mail", "fields" => "umail","id" => $mail)));
			
			echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'._MESSSEND.'</div>';
			redirect("index.php?file=Userbox", 2);
			}
		}
	}
}

function sendmail($titre, $pseudo, $mail, $check) {						
	if (!empty($pseudo) && !empty($mail) && $check == 1) {
         $headers = 'MIME-Version: 1.0'."\r\n";
         $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
         $subject = '[' . _PRIVATEMESS . '] Sujet : ' . $titre;
         $message = '<html>';
         $message .= '<head><title>[' . _PRIVATEMESS . '] Sujet : ' . $titre . '</title></head>';
         $message .= '<body>';
         $message .= _MPMAIL;
         $message .= $pseudo;
         $message .= _MPMAIL2;
         $message .= '</body>';
         $message .= '</html>';
         mail($mail, $subject, $message, $headers);
	}
}

function sendbox(){
		global $user, $nuked, $bgcolor1, $bgcolor2, $bgcolor3;
		require'modules/Userbox/function.php';
		include_jquery();
		include_script();
		
		$userbox = new userbox();
		$listing = $userbox -> listing_send(array(
		"order" => "date DESC",
		"conditions" => "user_from = '".$user[0]."'",
		"fields" => " mid, titre, user_for, date"
		));
echo'
    <div id="module_userbox" class="userbox_bg_1">
      <span class="title userbox_bg_1">' . _PRIVATEMESS . '</span>
      <span class="subtitle userbox_bg_2">
      	<a href="index.php?file=Userbox" class="button userbox_bg_3 userbox_border_1">' . _INDEX . '</a>
      	<a href="index.php?file=Userbox&amp;op=post_message" class="button userbox_bg_3 userbox_border_1">' . _NEW . '</a>
      	<a href="index.php?file=Userbox&amp;op=del_all&a=1&b=0" class="button userbox_bg_3 userbox_border_1">' . _DELALL . '</a>
      	<a href="index.php?file=Userbox&amp;op=sendbox" class="button userbox_bg_3 userbox_border_1">' . _OUTBOX . '</a>
		<a href="index.php?file=Userbox&amp;op=pref" class="button userbox_bg_3 userbox_border_1">' . _PREF . '</a>
</span>
      
      <div id="module_userbox_table" class="userbox_bg_2 userbox_border_1">';
		if(empty($listing)) {
			echo '<span class="userbox_bg_3" style="width:100%;text-align:center;display:table-cell;">' . _NOMESSPV . '</span></div>';
		} else {
 echo '	<div class="module_userbox_table_tr userbox_bg_1">
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._SENDTO.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_2 userbox_border_1">'._SUBJECT.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._DATE.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_3 userbox_border_1">' . _ATE2 . '</span>
      	</div>';
		
		foreach($listing as $a) {
			$a['date'] = nkDate($a['date']);
			
			$etat = ($a['status'] == 1) ? _READ : _NOTREAD;
			
			if(strlen($a['titre']) >= 50) $a['titre'] = substr($a['titre'], 0, 47)."...";
						

  echo '<div class="module_userbox_table_tr" data-id="'.$row['.mid.'].'">
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1"><a class="userbox_color_link" href="#" title="#">
		  '.$userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $a['user_for'])).'</a></span>
          <span class="module_userbox_table_td module_userbox_table_th_2 userbox_border_1">'. html_entity_decode($a['titre']) .'</span>
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1">'.$a['date'].'</span>
          <span class="module_userbox_table_td module_userbox_table_th_3">
		    <a href="index.php?file=Userbox&amp;op=show_message_send&amp;mid=' . $a['mid'] . '&amp;a=1" title="Lire le message">
          		<img src="modules/Userbox/images/green.png" alt="Lire le message" title="Lire le message" />
			</a>
          	<a href="index.php?file=Userbox&amp;op=del_all&a=4&b=' . $a['mid'] . '" title="' . _DELMSG . '">
			  <img src="modules/Userbox/images/delete.png" alt="' . _DELMSG . '" title="' . _DELMSG . '" />
			</a>
          </span>
      	</div>';
		}
		}
echo '</div>
    </div>
	<div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=Userbox">'._BACK.'</a> ]</div>
	<div id="userbox_c"></div>';
}

function show_message_send($mid, $a){
	global $user, $nuked, $bgcolor2, $bgcolor3;
		require'modules/Userbox/function.php';
		include_jquery();
		include_script();
		$userbox = new userbox();
		
	if($a == 0) {
		
		$listing = $userbox -> listing(array(
		"conditions" => "mid = '" . $mid . "' AND user_for = '" . $user[0] . "'",
		"fields" => "titre, message, user_from, date, idunique"
		));

		foreach($listing as $a) {

			if (!empty($listing)) {
				$a['date'] = nkDate($a['date']);
				if(strlen($a['titre']) >= 50) $a['titre'] = substr($a['titre'], 0, 47)."...";
				$users = $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $a['user_from']));
					
	echo '<div id="module_userbox" class="userbox_bg_2">
			<span class="title userbox_bg_1">'._PRIVATEMESS.'</span>
			<span class="subtitle userbox_bg_2">
			  <span class="for"><p>'._FROM1.' :</p>
			  <a href="index.php?file=Members&amp;op=detail&amp;autor='.urlencode($users).'"><strong>'.$users.'</strong></a>
			  <span style="float: right;padding-right: 10px;">'._THE.'&nbsp;'.$a['date'].'</span>
			  </span>
			  <span class="subject"><p>'._SUBJECT.' :</p>'.html_entity_decode($a['titre']).'</span>
			</span>
            
			<div class="module_userbox_table_tr_show">
				' . html_entity_decode($a['message']) . '
			</div>
		  </div>
		  <div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=Userbox&op=sendbox">'._BACK.'</a> ]</div>
		  <div id="userbox_c"></div>';
			} else { echo '<p style="text-align: center">' . _NOENTRANCE . '</p>'; }
		}
	} else {
		$listing = $userbox -> listing_send(array(
		"conditions" => "mid = '" . $mid . "' AND user_from = '" . $user[0] . "'",
		"fields" => "titre, message, user_for, date, idunique"
		));

		foreach($listing as $a) {

			if (!empty($listing)) {
				
				$a['date'] = nkDate($a['date']);
				if(strlen($a['titre']) >= 50) $a['titre'] = substr($a['titre'], 0, 47)."...";
				$users = $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $a['user_for']));
					
	echo '<div id="module_userbox" class="userbox_bg_2">
			<span class="title userbox_bg_1">'._PRIVATEMESS.'</span>
			<span class="subtitle userbox_bg_2">
			  <span class="for"><p>'._SENDTO.' :</p>
			  <a href="index.php?file=Members&amp;op=detail&amp;autor='.urlencode($users).'"><strong>'.$users.'</strong></a>
			  <span style="float: right;padding-right: 10px;">'._THE.'&nbsp;'.$a['date'].'</span>
			  </span>
			  <span class="subject"><p>'._SUBJECT.' :</p>'.html_entity_decode($a['titre']).'</span>
			</span>
            
			<div class="module_userbox_table_tr_show">
				' . html_entity_decode($a['message']) . '
			</div>
		  </div>
		  <div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=Userbox&op=sendbox">'._BACK.'</a> ]</div>
		  <div id="userbox_c"></div>';
			} else { echo '<div style="display:block;margin: 20px auto;" id="pref_ok">' . _NOENTRANCE . '</div>'; }
		}
	}

} 

function show_message($mid){
	global $user, $nuked, $bgcolor2, $bgcolor3;
	
		require_once'modules/Userbox/function.php';
		include_jquery();
		$userbox = new userbox();
	
	echo '<script type="text/javascript">function del_mess(pseudo, id){if (confirm(\''._DELETEMESS.' \'+pseudo+\' ! '._CONFIRM.'\')){document.location.href = \'index.php?file=Userbox&op=del_message&mid=\'+id;}}</script>';
	
	$sql = mysql_query("UPDATE " . USERBOX_TABLE . " SET status = 1 WHERE mid = '$mid' AND user_for = '{$user[0]}'");
		
	$sql2 = mysql_query("SELECT titre, message, user_from, date, idunique FROM " . USERBOX_TABLE . " WHERE mid = '" . $_REQUEST['mid'] . "' AND user_for = '" . $user[0] . "'");
	$row = mysql_fetch_assoc($sql2);

    if ($row > 1) {
        
        $row['date'] = nkDate($row['date']);
        
        if(strlen($row['titre']) >= 50) $row['titre'] = substr($row['titre'], 0, 47)."...";
		
		$pseudo = $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $row['user_from']));	
	
	echo '<div id="module_userbox" class="userbox_bg_2">
			<span class="title userbox_bg_1">'._PRIVATEMESS.'</span>
			<span class="subtitle userbox_bg_2">
			  <span class="for"><p>'._OF.' :</p>
			  <a href="index.php?file=Members&amp;op=detail&amp;autor='.urlencode($pseudo).'"><b>'.$pseudo.'</b></a>
			  <span style="float: right;padding-right: 10px;">'._THE.'&nbsp;'.$row['date'].'</span>
			  </span>
			  <span class="subject"><p>'._SUBJECT.' :</p>'.html_entity_decode($row['titre']).'</span>
			</span>
            
			<div class="module_userbox_table_tr_show">
				' . html_entity_decode($row['message']) . '
			</div>
			<form method="post" action="index.php?file=Userbox&amp;op=post_message">
                <input type="hidden" name="message" value="' . htmlentities($row['message']) . '" />
                <input type="hidden" name="idunique" value="'. $row['idunique'] . '" />
                <input type="hidden" name="for" value="'. $row['user_from'] . '" />
                <input type="hidden" name="titre" value="'.htmlentities($row['titre']).'" />
			<div style="text-align: center;">
			  <input class="button userbox_bg_3 userbox_border_1" type="submit" value="'._REPLY.'" />
			  <input class="button userbox_bg_3 userbox_border_1" type="button" value="'._DEL.'" onclick="javascript:del_mess(\''.mysql_real_escape_string(stripslashes($pseudo)).'\', \''.$mid.'\');" />
			</div>
		  </form>
		  
		  </div>';	
    }
    else {
        echo '<div style="display:block;margin: 20px auto;" id="pref_ok">' . _NOENTRANCE . '</div>';
    }

}

function del_message($mid){
	global $user, $nuked;
	
	$sql = mysql_query("SELECT mid FROM " . USERBOX_TABLE . " WHERE  mid = '$mid' AND user_for = '{$user[0]}'");
	$nbr = mysql_num_rows($sql);
	
	if($nbr > 0){
		$sql = mysql_query("DELETE FROM " . USERBOX_TABLE . " WHERE mid = '$mid' AND user_for = '{$user[0]}'");
		$MessConf = _MESSDEL;
	}
	else $MessConf = 'Failed...';

	echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'.$MessConf.'</div>';
	redirect('index.php?file=Userbox', 2);
}

function del_message_send($mid){
	global $user, $nuked;
	
	$sql = mysql_query("SELECT mid FROM " . USERBOX_TABLE_SEND . " WHERE  mid = '$mid' AND user_for = '{$user[0]}'");
	$nbr = mysql_num_rows($sql);
	
	if($nbr > 0){
		$sql = mysql_query("DELETE FROM " . USERBOX_TABLE_SEND . " WHERE mid = '$mid' AND user_for = '{$user[0]}'");
		$MessConf = _MESSDEL;
	}
	else $MessConf = 'Failed...';

	echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'.$MessConf.'</div>';
	redirect('index.php?file=Userbox', 2);
}

function del_message_form($mid, $del_oui){
	global $user, $nuked;
	
	if ($del_oui == 'ok'){
		
		$sql = mysql_query("SELECT mid FROM " . USERBOX_TABLE . " WHERE user_for = '{$user[0]}' ORDER BY mid");
		$nb_mess = mysql_num_rows($sql);
		$get_mid = 0;
		
		while ($nb_mess > $get_mid && $nb_mess <> ""){
			$titi = $mid[$get_mid];
			$get_mid++;
			
			if($titi){
				$del = mysql_query("DELETE FROM " . USERBOX_TABLE . " WHERE mid = '{$titi}'");
			}
		}
		echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'._MESSAGESDEL.'</div>';
		redirect('index.php?file=Userbox', 2);
		
	}else{
		
		if (!$mid){
			echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'._NOSELECTMESS.'</div>';
			redirect('index.php?file=Userbox', 2);
			closetable();
			footer();
			exit();
		}
		
		$sql = mysql_query("SELECT mid FROM " . USERBOX_TABLE . " WHERE user_for = '{$user[0]}' ORDER BY mid");
		$nb_mess = mysql_num_rows($sql);
		
		echo '<form method="post" action="index.php?file=Userbox&amp;op=del_message_form&amp;del_oui=ok">
                <div style="text-align:center;"><br /><br /><b>'._DELETEMESSAGES.' :</b><br /><br />';
		
		$get_mid = 0;
		while ($nb_mess > $get_mid && $nb_mess <> ""){
			$titi = $mid[$get_mid];
			$get_mid++;
			
			if ($titi){
				$sql_mess = mysql_query("SELECT user_from, date FROM " . USERBOX_TABLE . " WHERE user_for = '{$user[0]}' AND mid = '{$titi}'");
				$row = mysql_fetch_assoc($sql_mess);
				$row['date'] = nkDate($row['date']);
				
				$pseudo = $userbox -> users(array("check" => "pseudo", "fields" => "pseudo","id" => $row['user_from']));
				
				echo '<b><big>·</big></b>&nbsp;'._OF.'&nbsp;'.$pseudo.' ( '.$row['date'].' )<br />
				<input type="hidden" name="mid[]" value="'.$titi.'" />';
			}
		}
		
		echo '<br /><br /><input type="submit" value="'._DELCONFIRM.'" />
                &nbsp;<input type="button" value="'._CANCEL.'" onclick="document.location=\'index.php?file=Userbox\'" /></div></form><br />';
	}
}

function index(){
		global $user, $nuked, $bgcolor1, $bgcolor2, $bgcolor3;
		require'modules/Userbox/function.php';
		include_jquery();
		include_script();
		
		$userbox = new userbox();
		$listing = $userbox -> listing(array(
		"order" => "date DESC",
		"conditions" => "user_for = '".$user[0]."' AND archive = '0' ",
		"fields" => " mid, titre, user_from, date, status"
		));
		$config = $userbox -> config();
		
		$brpct = round((count($listing)/$config['max'])*100);	
echo'
    <div id="module_userbox" class="userbox_bg_1">
      <span class="title userbox_bg_1">' . _PRIVATEMESS . '</span>
      <span class="subtitle userbox_bg_2">
      	<a href="index.php?file=Userbox&amp;op=post_message" class="button userbox_bg_3 userbox_border_1">' . _NEW . '</a>
      	<a href="index.php?file=Userbox&amp;op=del_all&a=0&b=0" class="button userbox_bg_3 userbox_border_1">' . _DELALL . '</a>
      	<a href="index.php?file=Userbox&amp;op=sendbox" class="button userbox_bg_3 userbox_border_1">' . _OUTBOX . '</a>
      	<a href="index.php?file=Userbox&amp;op=archive" class="button userbox_bg_3 userbox_border_1">' . _ARCHIVES . '</a>
      	<a href="index.php?file=Userbox&amp;op=pref" class="button userbox_bg_3 userbox_border_1">' . _PREF . '</a>
      </span>
      
      <div id="module_userbox_table" class="userbox_bg_2 userbox_border_1">
      	<div class="module_userbox_table_tr userbox_bg_1">';
		if(empty($listing)) {
			echo '<span class="userbox_bg_3" style="width:100%;text-align:center;display:table-cell;">' . _NOMESSPV . '</span></div>';
		} else {
    echo' <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._FROM.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_2 userbox_border_1">'._SUBJECT.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._DATE.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._STATUS.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_3 userbox_border_1">' . _ATE . '</span>
      	</div>';
		
		foreach($listing as $a) {
			$a['date'] = nkDate($a['date']);
			
			$etat = ($a['status'] == 1) ? _READ : _NOTREAD;
			
			if(strlen($row['titre']) >= 50) $row['titre'] = substr($row['titre'], 0, 47)."...";
			
		

  echo '<div class="module_userbox_table_tr" data-id="'.$row['.mid.'].'">
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1"><a class="userbox_color_link" href="#" title="#">
		  '. $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $a['user_from'])) .'</a></span>
          <span class="module_userbox_table_td module_userbox_table_th_2 userbox_border_1">'. html_entity_decode($a['titre']) .'</span>
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1">'.$a['date'].'</span>
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1" style="text-align:left;">
		    <a href="index.php?file=Userbox&amp;op=show_message&amp;mid=' . $a['mid'] . '" title="Lire le message">
          		<img src="modules/Userbox/images/green.png" alt="Nouveau Message" title="Nouveau Message" />&nbsp;&nbsp;'.$etat.'
			</a>
          </span>
          <span class="module_userbox_table_td module_userbox_table_th_3">
          	<a class="ajax_archive" href="#' . $a['mid'] . '"><img src="modules/Userbox/images/archive.png" alt="' . _ARCHMSG . '" title="' . _ARCHMSG . '" /></a>
          	<a href="index.php?file=Userbox&amp;op=del_all&a=3&b=' . $a['mid'] . '" title="' . _DELMSG . '"><img src="modules/Userbox/images/delete.png" alt="' . _DELMSG . '" title="' . _DELMSG . '" /></a>
          </span>
      	</div>';
		}
		}

echo '</div>
    </div>
	
    <div class="module_userbox_progress-bar">
    	<span style="width: '. $brpct . '%">'. $brpct . '%&nbsp;&nbsp;</span>
    </div>';
	if($brpct >= 100) {
		echo '<div style="display:block;margin: 20px auto;" id="pref_ok">' . _MPFULL . '</div>';
	}
echo ' <div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=User">'._BACK.'</a> ]</div>
	   <div id="userbox_c"></div>';
} 

function archive(){
		global $user, $nuked, $bgcolor1, $bgcolor2, $bgcolor3;
		require'modules/Userbox/function.php';
		include_jquery();
		include_script();
		$userbox = new userbox();
		$listing = $userbox -> listing(array(
		"order" => "date DESC",
		"conditions" => "user_for = '".$user[0]."' AND archive = '1' ",
		"fields" => " mid, titre, user_from, date, status"
		));
echo'
    <div id="module_userbox" class="userbox_bg_1">
      <span class="title userbox_bg_1">' . _PRIVATEMESS . '</span>
      <span class="subtitle userbox_bg_2">
      	<a href="index.php?file=Userbox" class="button userbox_bg_3 userbox_border_1">' . _INDEX . '</a>
      	<a href="index.php?file=Userbox&amp;op=del_all&a=0&b=1" class="button userbox_bg_3 userbox_border_1">' . _DELALL . '</a>
      	<a href="index.php?file=Userbox&amp;op=sendbox" class="button userbox_bg_3 userbox_border_1">' . _OUTBOX . '</a>      	<a href="index.php?file=Userbox&amp;op=pref" class="button userbox_bg_3 userbox_border_1">' . _PREF . '</a>
</span>
      
      <div id="module_userbox_table" class="userbox_bg_2 userbox_border_1">';
		if(empty($listing)) {
			echo '<span class="userbox_bg_3" style="width:100%;text-align:center;display:table-cell;">' . _NOMESSPV . '</span></div>';
		} else {
 echo '	<div class="module_userbox_table_tr userbox_bg_1">
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._FROM.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_2 userbox_border_1">'._SUBJECT.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_1 userbox_border_1">'._DATE.'</span>
          <span class="module_userbox_table_th module_userbox_table_th_3 userbox_border_1">' . _ATE2 . '</span>
      	</div>';
		
		foreach($listing as $a) {
			$a['date'] = nkDate($a['date']);
			
			$etat = ($a['status'] == 1) ? _READ : _NOTREAD;
			
			if(strlen($row['titre']) >= 50) $row['titre'] = substr($row['titre'], 0, 47)."...";

  echo '<div class="module_userbox_table_tr" data-id="'.$row['.mid.'].'">
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1"><a class="userbox_color_link" href="#" title="#">
		  ' . $userbox -> users(array("check" => "id", "fields" => "pseudo","id" => $a['user_from'])) . '</a></span>
          <span class="module_userbox_table_td module_userbox_table_th_2 userbox_border_1">'. html_entity_decode($a['titre']) .'</span>
          <span class="module_userbox_table_td module_userbox_table_th_1 userbox_border_1">'.$a['date'].'</span>
          <span class="module_userbox_table_td module_userbox_table_th_3">
		    <a href="index.php?file=Userbox&amp;op=show_message_send&amp;mid=' . $a['mid'] . '&a=0" title="Lire le message">
          		<img src="modules/Userbox/images/green.png" alt="Lire le message" title="Lire le message" />
			</a>
          	<a href="index.php?file=Userbox&amp;op=del_all&a=5&b=' . $a['mid'] . '" title="' . _DELMSG . '">
			  <img src="modules/Userbox/images/delete.png" alt="' . _DELMSG . '" title="' . _DELMSG . '" />
			</a>
          </span>
      	</div>';
		}
		}
echo '</div>
    </div>
	<div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=Userbox">'._BACK.'</a> ]</div>
	<div id="userbox_c"></div>';
} 

function del_all($a, $b) {
	global $user, $nuked;
	
		require'modules/Userbox/function.php';
		include_jquery();
		$userbox = new userbox();
		
		if($a == 0) {
			$userbox -> delete(array(
			"conditions" => "archive = '$b' AND user_for = '{$user[0]}'",
			));
			$MessConf = _MESSDELALL;
		} elseif($a == 1) {
			$userbox -> delete_send(array(
			"conditions" => "user_from = '{$user[0]}'",
			));
			$MessConf = _MESSDELALL;
		} elseif($a == 3) {
			$userbox -> delete(array(
			"conditions" => "archive = '0' AND mid = '$b' AND user_for = '{$user[0]}'",
			));
			$MessConf = _MESSDEL;
		} elseif($a == 4) {
			$userbox -> delete_send(array(
			"conditions" => "mid = '$b' AND user_from = '{$user[0]}'",
			));
			$MessConf = _MESSDEL;
		} elseif($a == 5) {
			$userbox -> delete(array(
			"conditions" => "archive = '1' AND mid = '$b' AND user_for = '{$user[0]}'",
			));
			$MessConf = _MESSDEL;
		}

		echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'.$MessConf.'</div>';
		redirect('index.php?file=Userbox', 2);
			
	}

function css() {
    global $nuked;
	header("Content-type: text/css; charset=UTF-8");
	header('Expires: ' . gmdate('D, d M Y H:i:s ',time() + (60 * 60 * 24 * 10)) . ' GMT');
	require'modules/Userbox/function.php';
	$userbox = new userbox();	
	$config = $userbox -> config();
?>
.userbox_bg_1 {
	background: #<?php echo $config['bg1']; ?> !important; /* Background Titre */
}
.userbox_bg_2 {
	background: #<?php echo $config['bg2']; ?> !important; /* Background Cat 1 */
}
.userbox_bg_3 {
	background: #<?php echo $config['bg3']; ?> !important; /* Background Cat 2 */
}
.userbox_border_1 {
	border-color: #<?php echo $config['bg1']; ?> !important;
}
.userbox_border_2 {
	border-color: #<?php echo $config['bg2']; ?> !important;
}
.userbox_border_3 {
	border-color: #<?php echo $config['bg3']; ?> !important;
}
/* Couleur a modifier */
#module_userbox {
	padding: 2px;
	-webkit-border-radius: <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px 0px 0px;
	border-radius: <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px 0px 0px;
    color:#<?php echo $config['color']; ?>; /* Couleur du texte */
}
#module_userbox a {
    color:#<?php echo $config['colorlink']; ?>; /* Couleur du texte */
	text-decoration: none;
}
#module_userbox > .title {
	display: block;
	text-align: center;
	padding: 2px;
	-webkit-border-radius: <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px 0px 0px;
	border-radius: <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px 0px 0px;
	font-weight: bold;
}
#module_userbox > .subtitle {
	display: block;
	text-align: center;
	padding: 1px;
}
.button {
	display: inline-block;
	padding: 1px;
	text-shadow: 0px 1px 0px #<?php echo $config['bg3']; ?>;
	-webkit-transition: all 200ms linear;
	-moz-transition: all 200ms linear;
	-ms-transition: all 200ms linear;
	-o-transition: all 200ms linear;
	transition: all 200ms linear;
	-webkit-border-radius: 0px 0px <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px;
	border-radius: 0px 0px <?php echo $config['radius']; ?>px <?php echo $config['radius']; ?>px;
	width: 150px;
	cursor: pointer;
	border: 1px solid;
	margin-bottom: 1px;
    color:#<?php echo $config['color']; ?>; /* Couleur du texte */
}
.button:hover {
	text-shadow: 0px 1px 1px #<?php echo $config['color']; ?>;
	-webkit-border-radius: 0px 0px 2px 2px;
	border-radius: 0px 0px 2px 2px;
	width: 155px;
}
#module_userbox_table {
	display: table;
	width: 100%;
	margin-top: 2px;
	border-top: 1px solid;
}
.module_userbox_table_tr {
	display: table-row;
	background: #<?php echo $config['bg2']; ?>; /* 2 */
	height: 22px;
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	-webkit-transition: all 250ms ease-in;
	-moz-transition: all 250ms ease-in;
	-ms-transition: all 250ms ease-in;
	-o-transition: all 250ms ease-in;
	transition: all 250ms ease-in;
}
.module_userbox_table_tr:nth-child(odd) {
	background: #<?php echo $config['bg3']; ?>; /* 3 */
}
.module_userbox_table_tr:hover, .module_userbox_table_tr:nth-child(odd):hover {
	cursor: pointer;
	background: #<?php echo $config['bg1']; ?>; /* 1 */
}
.module_userbox_table_tr:hover:nth-child(1) { cursor: auto; }
.module_userbox_table_tr img {
	width: 16px;
	height: 16px;
	vertical-align: middle;
	margin-bottom: 4px;
	border: 0;
	margin-left: 10px;
}
.module_userbox_table_th {
	display: table-cell;
	text-align: center;
	border-right: 1px solid;
	padding: 1px 0;
	font-weight: 600;
}
.module_userbox_table_td {
	display: table-cell;
	text-align: center;
	border-right: 1px solid;
}
.module_userbox_table_td:last-child, .module_userbox_table_th:last-child {
	border-right: 0;
}
.module_userbox_table_th_1 {
	width: 15%;
	line-height: 22px;
}
.module_userbox_table_th_2 {
	width: 30%;
	line-height: 22px;
}
.module_userbox_table_th_3 {
	width: 20%;
	line-height: 22px;
}
.module_userbox_progress-bar {
	margin: 10px;
	height: 10px;
	padding: 1px;
	left: 0; right: 0;
	background: #<?php echo $config['bg1']; ?>;
	font-size: 9px;
	line-height: 10px;
}
.module_userbox_progress-bar span {
	display: inline-block;
	height: 100%;
    max-width: 100%;
	background: #5d5d5d;
	text-align: right;
	background-color: #fecf23;
	background: -webkit-gradient(linear, left top, left bottom, from(#fecf23), to(#fd9215));
	background: -webkit-linear-gradient(top, #fecf23, #fd9215);
	background: -moz-linear-gradient(top, #fecf23, #fd9215);
	background: -ms-linear-gradient(top, #fecf23, #fd9215);
	background: -o-linear-gradient(top, #fecf23, #fd9215);
	background: linear-gradient(top, #fecf23, #fd9215);
}
.subtitle > .for, .subtitle > .subject {
	display: block;
	width: 100%;
	margin: 2px;
	text-align: left;
}
.subtitle > .for p , .subtitle > .subject p {
	display: inline-block;
	width: 80px;
	text-align: right;
	margin: 0 10px 0 20px;
	font-weight: bold;
}
.for > select, .subject > input {
	display: inline-block;
	width: 30%;
	padding: 1px 2px;
   -webkit-appearance: none;
	overflow: hidden;
	background: #e3e3e3;
	border: 1px solid #<?php echo $config['bg1']; ?>;
	text-shadow: 0px 1px 0px #<?php echo $config['bg3']; ?>;
	-webkit-transition: all 200ms linear;
	-moz-transition: all 200ms linear;
	-ms-transition: all 200ms linear;
	-o-transition: all 200ms linear;
	transition: all 200ms linear;
    color:#<?php echo $config['color']; ?>; /* Couleur du texte */
	cursor: pointer;
}
.subject > input {
	width: -moz-calc(30% - 5px);
	width: -webkit-calc(30% -5px);
	width: calc(30% - 5px);
}
.for > select:hover {
	text-shadow: 0px 1px 1px #<?php echo $config['color']; ?>;
	width: -moz-calc(100% - 112px);
	width: -webkit-calc(100% - 112px);
	width: calc(100% - 112px);
    overflow: hidden;
}
.subject > input:hover {
	text-shadow: 0px 1px 1px #<?php echo $config['color']; ?>;
	width: -moz-calc(100% - 118px);
	width: -webkit-calc(100% - 118px);
	width: calc(100% - 118px);
    overflow: hidden;
}
#cke_e_basic {
    width: 100% !important;
    margin: 2px;
}

.module_userbox_table_tr_show {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
}
.module_userbox_table_tr_show_c {
    padding: 20px;
    clear: both;
    margin-top: 10px;
	background: #<?php echo $config['bg1']; ?>; /* 2 */
}
.module_userbox_table_tr_show_c:nth-child(odd) {
	background: #<?php echo $config['bg3']; ?>; /* 3 */
}
.userbox_pref_1 {
margin;0 auto;text-align:center;display:inline-block;font-weight: bold;
	width: -moz-calc(100% - 25px);
	width: -webkit-calc(100% - 25px);
	width: calc(100% - 25px);
}
#pref_ok { background:#cbd9e0; margin: 10px auto; border:1px solid #92a5af; color:#5e6365; font-weight:bold; line-height: 25px; text-align: center; width: 95%; height: 25px; -webkit-border-radius: 5px 5px 5px 5px; border-radius: 5px 5px 5px 5px; display: none; }
.form_userbox { border: 1px solid rgb(204, 204, 204);border-left:0;border-right:0;padding: 10px; }

<?php
}
function pref() {
		global $user, $nuked, $bgcolor1, $bgcolor2, $bgcolor3;
		require'modules/Userbox/function.php';
		include_jquery();
		include_script();
		$userbox = new userbox();
		if ($check_conf_ump = $userbox -> check_conf(array("fields" => 'ump',"id" => $user[0])) == 1) { $ump = 'checked="checked"'; }
		if ($check_conf_umail = $userbox -> check_conf(array("fields" => 'umail',"id" => $user[0])) == 1) { $umail = 'checked="checked"'; }
		
echo'
    <div id="module_userbox" class="userbox_bg_1">
      <span class="title userbox_bg_1">' . _PRIVATEMESS . '</span>
      <span class="subtitle userbox_bg_2">
      	<a href="index.php?file=Userbox" class="button userbox_bg_3 userbox_border_1">' . _INDEX . '</a>
      	<a href="index.php?file=Userbox&amp;op=post_message" class="button userbox_bg_3 userbox_border_1">' . _NEW . '</a>
      	<a href="index.php?file=Userbox&amp;op=sendbox" class="button userbox_bg_3 userbox_border_1">' . _OUTBOX . '</a>
      </span>
	  <div class="userbox_bg_3" style="margin-top:1px;padding:25px;">
	  	<span class="userbox_pref_1">' . _NOSENDMSG . '<input name="ump" type="checkbox" ' . $ump . ' /></span>
	  	<span class="userbox_pref_1">' . _MAILMP . '<input name="umail" type="checkbox" ' . $umail . ' /></span>
		<div id="pref_ok">" . _PREFOK . "</div>
	  </div>
    </div>
	
	<div style="margin-top:20px;text-align:center;font-weight: bold;">[ <a href="index.php?file=Userbox">'._BACK.'</a> ]</div>
	<div id="userbox_c"></div>';
}


function jquery($value, $id) {
	global $nuked, $user, $language, $visiteur;
	
	require'modules/Userbox/function.php';
	include_jquery();
	$userbox = new userbox();
	
		$listing = $userbox -> listing(array(
		"conditions" => "user_for = '".$user[0]."' AND mid = '" . $id . "'",
		"fields" => "mid"
		));

		if(!empty($listing)) {
			if($value == 'archives') {
				$userbox -> update(array(
				"set" => "archive  = '1'",
				"id" => $id
				));
			}
		}
		if($value == 'pref') {
		$check = explode("|", $id);
			$userbox -> update_mp(array(
			"set" => "" . $check[0] . " = " . $check[1],
			"id" => $user[0]
			));
		}
		
		if ($value == 'admin' && $visiteur >= admin_mod('Userbox') && admin_mod('Userbox') > -1) {
			$check = explode("|", $id);
			$userbox -> save_config(array(
			"name" => $check[0],
			"value" => $check[1]
			));
		}

}

if($user){
	if(isset($_REQUEST['op'])){
		switch ($_REQUEST['op']){
						
            case 'del_all':
			opentable();
			del_all($_REQUEST['a'], $_REQUEST['b']);
			closetable();
			break;
			
            case 'sendbox':
			opentable();
			sendbox();
			closetable();
			break;	
			
			case"pref":
			opentable();
			pref();
			closetable();
			break;
			
			case"css":
			css();
			break;
			
			case"jquery":
			jquery($_REQUEST['value'], $_REQUEST['id']);
			break;
			
			case"archive":
			opentable();
			archive();
			closetable();
			break;
            
			case 'post_message':
			opentable();
			post_message();
			closetable();
			break;

            case 'send_message':
			opentable();
			send_message($_REQUEST['titre'], $_REQUEST['user_for'], $_REQUEST['message'], $_REQUEST['idunique']);
			closetable();
			break;
			
            case 'show_message_send':
			opentable();
			show_message_send($_REQUEST['mid'], $_REQUEST['a']);
			closetable();
			break;

            case 'show_message':
			opentable();
			show_message($_REQUEST['mid']);
			closetable();
			break;

            case 'del_message':
			opentable();
			del_message($_REQUEST['mid']);
			closetable();
			break;
			
            case 'del_message_send':
			opentable();
			del_message_send($_REQUEST['mid']);
			closetable();
			break;

            case 'del_message_form':
			opentable();
			del_message_form($_REQUEST['mid'], $_REQUEST['del_oui']);
			closetable();
			break;

            default:
			opentable();
			index();
			closetable();
			break;
        } 
    }
}else{
    opentable();
    echo '<div style="display:block;margin: 20px auto;" id="pref_ok">'._USERENTRANCE.'</div>';
	redirect('index.php?file=User&amp;op=reg_screen', 2);
    closetable();
}
?>