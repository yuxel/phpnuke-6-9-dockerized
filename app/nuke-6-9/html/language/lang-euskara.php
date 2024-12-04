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
define("_SEARCH","Bilatu");
define("_LOGIN","Saioa hasi");
define("_WRITES"," sistema-kideak idatzi zuen");
define("_POSTEDON","Bidalia");
define("_NICKNAME","Ezizena");
define("_PASSWORD","Pasahitza");
define("_WELCOMETO","Ongi etorri");
define("_EDIT","Editatu");
define("_DELETE","Ezabatu");
define("_POSTEDBY","Nork bidalia");
define("_READS","Irakurketa");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Bueltatu</a> ]");
define("_COMMENTS","komentario");
define("_PASTARTICLES","Gaurkotasun gabeko Artikuloak");
define("_OLDERARTICLES","Artikulo zaharrak");
define("_BY","nork");
define("_ON","noiz");
define("_LOGOUT","Irten");
define("_WAITINGCONT","Edukiak itxaroten");
define("_SUBMISSIONS","Notizi bidalketak");
define("_WREVIEWS","Berrikuspenak");
define("_WLINKS","Loturak");
define("_EPHEMERIDS","Efemerideak");
define("_ONEDAY","Gaurko egun berdinean...");
define("_ASREGISTERED","Oraindik ez daukazu kontu bat sisteman? Egin klik <a href=\"modules.php?name=Your_Account\">hemen</a> eta lortu kontu bat doainik. Erabiltzaile erregistratu bezala, Sistemako atal guztietara sartu ahal izango zara.");
define("_MENUFOR","Menua:");
define("_NOBIGSTORY","Gaur ez dago Historia Berezirik.");
define("_BIGSTORY","Gaur gehien irakurri den historia:");
define("_SURVEY","Inkesta");
define("_POLLS","Bozketak");
define("_PCOMMENTS","Komentarioak:");
define("_RESULTS","Emaitzak");
define("_HREADMORE","Gehiago irakurri . . .");
define("_CURRENTLY","Une honetan");
define("_GUESTS","gonbidatu eta");
define("_MEMBERS","kide konektatuta.");
define("_YOUARELOGGED","Honela konektatuta zaude:");
define("_YOUHAVE","Begiratu zure buzoia. Zenbat mezu:");
define("_PRIVATEMSG","guztira.");
define("_YOUAREANON","Erabiltzaile anonimoa zara. Erregistratu <b><a href=\"modules.php?name=Your_Account&op=new_user\">hemen</a></b>.");
define("_NOTE","Oharra:");
define("_ADMIN","Administratzailea:");
define("_WERECEIVED","Sistemak jaso izan duen bisita zenbakia:");
define("_PAGESVIEWS","fetxa honetatik hasita:");
define("_TOPIC","Gaia");
define("_UDOWNLOADS","Deskargak");
define("_VOTE","bozkatu");
define("_VOTES","botoak");
define("_MVIEWADMIN","Ikusi: Bakarrik administratzaileak");
define("_MVIEWUSERS","Ikusi: Bakarrik Sistema-kideak");
define("_MVIEWANON","Ikusi: Bakarrik Erabiltzaile anonimoak");
define("_MVIEWALL","Ikusi: Erabiltzaile guztiak");
define("_EXPIRELESSHOUR","Kaduzitatea: ordu bete baino gutxiago");
define("_EXPIREIN","Kadukatzen da:");
define("_HTTPREFERERS","HTTP erreferentziak");
define("_UNLIMITED","Inoiz");
define("_HOURS","ordu");
define("_RSSPROBLEM","Arazo bat dago Toki honen Titularrekin");
define("_SELECTLANGUAGE","Hizkuntza aukeratu");
define("_SELECTGUILANG","Menuetarako erabili nahi duzun hizkuntza aukeratu");
define("_NONE","Bat ere ez");
define("_BLOCKPROBLEM","<center>Arazo bat dago bloke honekin");
define("_BLOCKPROBLEM2","<center>Bloke honetan ez dago edukirik.</center>");
define("_MODULENOTACTIVE","Sentitzen dut, modulo hau ez dago aktibatuta!");
define("_NOACTIVEMODULES","Aktibo ez dauden moduloak");
define("_FORADMINTESTS","(Administratzaileek testeatzeko)");
define("_BBFORUMS","Foroak");
define("_HOME","Home");
define("_HOMEPROBLEM","There is a big problem here: we have not a Homepage!!!");
define("_ADDAHOME","Add a Module in your Home");
define("_HOMEPROBLEMUSER","There is a problem right now on the Homepage. Please check it back later.");
define("_MORENEWS","More in News Section");
define("_ALLCATEGORIES","All Categories");
define("_DATESTRING","%B %d %A, ordua %T");
define("_DATESTRING2","%B %d, %A");
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