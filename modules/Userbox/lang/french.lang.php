<?php
if (!defined("INDEX_CHECK"))
{
	exit('You can\'t run this file alone.');
}
define("_POSTMESS","Poster un message");
define("_AUTHOR","Auteur");
define("_USERFOR","Pour");
define("_SUBJECT","Sujet");
define("_USERMESS","Message");
define("_SEND","Envoyer");
define("_BACK","Retour");
define("_EMPTYFIELD","Assurez vous que tous les champs soient remplis");
define("_UNKNOWMEMBER","Désolé, le membre est inconnu.");
define("_MESSSEND","Message envoyé avec succès.");
define("_PRIVATEMESS","Messages Privés");
define("_OF","De");
define("_THE","le");
define("_WROTE","a ecrit");
define("_REPLY","Répondre");
define("_DEL","Supprimer");
define("_NOSELECTMESS","Aucun message sélectionné");
define("_MESSDEL","Message supprimé avec succès.");
define("_DELETEMESS","Vous êtes sur le point de supprimer le message de");
define("_DELETEMESSAGES","Vous êtes sur le point de supprimer les messages");
define("_MESSAGESDEL","Messages supprimés avec succès.");
define("_CONFIRM","Continuer ?");
define("_BY","Par");
define("_CLEARSUCCES","effacé avec succès.");
define("_DELCONFIRM","Confirmer");
define("_CANCEL","Annuler");
define("_DELBOX","Sup");
define("_FROM","Provenant de");
define("_DATE","Date");
define("_SEEDETAILUSER","Voir les détails de l'auteur");
define("_READMESS","Lire message");
define("_STATUS","Status");
define("_READ","Lu");
define("_NOTREAD","Non lu");
define("_CHECKALL","Tout Cocher");
define("_UNCHECKALL","Tout Décocher");
define("_NOMESSPV","Vous n'avez pas de message...");
define("_SENDNEWMESS","Nouveau");
define("_NOFLOOD","Vous avez déja posté un email il y'a moins de " . $nuked['post_flood'] . " secondes,<br />veuillez patienter avant de renvoyer un autre email...");

/* Patche Stive */
define("_NEW","Nouveau");
define("_ARCHIVES","Archives");
define("_PREF","Préférences");
define("_DELALL","Supprimer tous");
define("_OUTBOX","Boîte d'envoi");
define("_ATE","Archives - Effacer");
define("_ATE2","Lire - Effacer");
define("_RESPTO","Répondre a");
define("_INDEX","Index");
define("_MESSDELALL","Tous les Message supprimé avec succès.");
define("_ARCHMSG","Archiver ce message");
define("_TRANSFMSG","Transférer le message");
define("_DELMSG","Effacer le message");
define("_SENDTO","Envoyer à");
define("_NOSENDMSG","&nbsp;&nbsp;&nbsp;Ne plus m'envoyer de message privé :  ");
define("_MAILMP","M'avertir par émail d'un message privé :  ");
define("_FROM1","Provient de ");
define("_NOMPSEND","Utilisateurs ne veut pas être contacté");
define("_MPMAIL","<p>Bonjour,</p> <p>Vous avez reçu un nouveau message privé de la part de ");
define("_MPMAIL2","</p><p>vous pouvez dès à présent le lire <a href=\"" . $nuked['url'] . "/index.php?file=Userbox\" title=\"Message privé\">ici</a></p>");
define("_MPFULL","Votre boîte aux lettres est pleine, merci de bien vouloir la vidée");

define("_ADMINUSERBOX","Administration Message privé");
define("_ADMINUSERBOXMAXMP","Nombre de message privé maximum");
define("_ADMINUSERBOXBG1","Background Titre");
define("_ADMINUSERBOXBG2","Background 1");
define("_ADMINUSERBOXBG3","Background 2");
define("_ADMINUSERBOXMOZ","Coin arrondi");
define("_REGISTER","Enregistrer");
define("_PREFOK","Préférence sauvegarder");
define("_ADMINUSERBOXCOLOR0","Couleur du texte");
define("_ADMINUSERBOXCOLOR1","Couleur des liens");
?>