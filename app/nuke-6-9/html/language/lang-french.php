<?php

/**************************************************************************/
/* PHP-NUKE: Advanced Content Management System                           */
/* ============================================                           */
/*                                                                        */
/* This is the language module with all the system messages               */
/*                                                                        */
/* If you made a translation, please go to the site and send to me        */
/* the translated file. Please keep the original text order by modules,   */
/* and just one message per line, also double check your translation!     */
/*                                                                        */
/* You need to change the second quoted phrase, not the capital one!      */
/*                                                                        */
/* If you need to use double quotes (") remember to add a backslash (\),  */
/* so your entry will look like: This is \"double quoted\" text.          */
/* And, if you use HTML code, please double check it.                     */
/**************************************************************************/

define("_CHARSET","ISO-8859-1");
define("_SEARCH","Recherche");
define("_LOGIN"," Identification ");
define("_WRITES","a &eacute;crit :");
define("_POSTEDON","Post&eacute; le");
define("_NICKNAME","Surnom/Pseudo");
define("_PASSWORD","Mot de Passe");
define("_WELCOMETO","Bienvenue sur");
define("_EDIT","Editer");
define("_DELETE","Supprimer");
define("_POSTEDBY","Transmis par");
define("_READS","lectures");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Retour</a> ]");
define("_COMMENTS","commentaires");
define("_PASTARTICLES","Articles pr&eacute;c&eacute;dents");
define("_OLDERARTICLES","Archives");
define("_BY","par");
define("_ON","le");
define("_LOGOUT","Sortie");
define("_WAITINGCONT","Contenu en attente");
define("_SUBMISSIONS","Propositions");
define("_WREVIEWS","Comptes rendus en attente");
define("_WLINKS","Liens en attente");
define("_EPHEMERIDS","Eph&eacute;m&eacute;rides");
define("_ONEDAY","Un Jour comme Aujourd'hui...");
define("_ASREGISTERED","Vous n'avez pas encore de compte?<br><a href=\"modules.php?name=Your_Account\">Enregistrez vous !</a><br>En tant que membre enregistr&eacute;, vous b&eacute;n&eacute;ficierez de privil&egrave;ges tels que: changer le th&egrave;me de l'interface, modifier la disposition des commentaires, signer vos interventions, ...");
define("_MENUFOR","Menu de");
define("_NOBIGSTORY","Il n'y a pas encore d'article-phare aujourd'hui.");
define("_BIGSTORY","Aujourd'hui, l'article le plus lu est:");
define("_SURVEY","Sondage");
define("_POLLS","Sondages");
define("_PCOMMENTS","Commentaires:");
define("_RESULTS","R&eacute;sultats");
define("_HREADMORE","suite...");
define("_CURRENTLY","Il y a pour le moment");
define("_GUESTS","invit&eacute;(s) et");
define("_MEMBERS","membre(s) en ligne.");
define("_YOUARELOGGED","Vous &ecirc;tes connect&eacute; en tant que");
define("_YOUHAVE","Vous avez");
define("_PRIVATEMSG","message(s) priv&eacute;(s).");
define("_YOUAREANON","Vous &ecirc;tes un visiteur anonyme. Vous pouvez vous enregistrer gratuitement en cliquant <a href=\"modules.php?name=Your_Account\">ici</a>.");
define("_NOTE","Note:");
define("_ADMIN","Admin:");
define("_WERECEIVED","Nous avons re&ccedil;us");
define("_PAGESVIEWS","pages vues depuis");
define("_TOPIC","Sujet");
define("_UDOWNLOADS","T&eacute;l&eacute;chargements");
define("_VOTE","Vote");
define("_VOTES","Votes");
define("_MVIEWADMIN","Visualisation: Administrateurs seulement");
define("_MVIEWUSERS","Visualisation: Utilisateurs enregistr&eacute;s seulement");
define("_MVIEWANON","Visualisation: Utilisateurs anonymes seulement");
define("_MVIEWALL","Visualisation: Tous les visiteurs");
define("_EXPIRELESSHOUR","Expiration: Moins d'une heure");
define("_EXPIREIN","Expiration dans");
define("_HTTPREFERERS","R&eacute;f&eacute;rants HTTP");
define("_UNLIMITED","Illimit&eacute;es");
define("_HOURS","heures");
define("_RSSPROBLEM","La manchette de ce site n'est pas disponible pour le moment.");
define("_SELECTLANGUAGE","Selectionnez la langue");
define("_SELECTGUILANG","Selectionnez la langue de l'interface:");
define("_NONE","Aucun");
define("_BLOCKPROBLEM","<center>Il y a un probleme avec ce bloc.</center>");
define("_BLOCKPROBLEM2","<center>Il n'y a rien dans ce block.</center>");
define("_ALLCATEGORIES","Toutes les catégories");
define("_MODULENOTACTIVE","Désolé, ce module n'est pas activé");
define("_NOACTIVEMODULES","Modules inactifs");
define("_FORADMINTESTS","(Pour des tests administratifs)");
define("_BBFORUMS","Forums");
define("_ACCESSDENIED","Accès refusé");
define("_RESTRICTEDAREA","Vous essayez d'accéder à un espace réservé.");
define("_MODULEUSERS","Nous sommes désolé mais cette section de notre site est pour les <i>utilisateurs enregistrés seulement</i><br><br>Vous pouvez vous enregistrer gratuitement en cliquant <a href=\"modules.php?name=Your_Account&op=new_user\">ici</a>, puis vous pouvez<br>accéder à cette section sans réstriction. Merci.<br><br>");
define("_MODULESADMINS","Nous sommes désolé mais cette section de notre site est réservée aux <i>administrateurs seulement</i><br><br>");
define("_HOME","Home");
define("_HOMEPROBLEM","There is a big problem here: we have not a Homepage!!!");
define("_ADDAHOME","Add a Module in your Home");
define("_HOMEPROBLEMUSER","There is a problem right now on the Homepage. Please check it back later.");
define("_MORENEWS","More in News Section");
define("_DATESTRING","%d %B %Y &agrave; %H:%M:%S %Z ");
define("_DATESTRING2","%A, %d %B");
define("_CHAT","Chat");
define("_REGISTERED","Registered");
define("_CHATGUESTS","Guests");
define("_USERSTALKINGNOW","Users Talking Now");
define("_ENTERTOCHAT","Enter To Chat");
define("_CHATROOMS","Available Rooms");
define("_SECURITYCODE","Security Code");
define("_TYPESECCODE","Type Security Code");
define("_ASSOTOPIC","Associated Topics");

/*****************************************************/
/* Function to translate Datestrings                 */
/*****************************************************/

function translate($phrase) {
    switch($phrase) {
	case "xdatestring":	$tmp = "%A, %B %d @ %T %Z"; break;
	case "linksdatestring":	$tmp = "%d-%b-%Y"; break;
	case "xdatestring2":	$tmp = "%A, %B %d"; break;
	default:		$tmp = "$phrase"; break;
    }
    return $tmp;
}

?>