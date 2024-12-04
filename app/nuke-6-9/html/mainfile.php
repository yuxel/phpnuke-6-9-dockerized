<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$phpver = phpversion();
if ($phpver >= '4.0.4pl1' && strstr($HTTP_USER_AGENT,'compatible')) {
    if (extension_loaded('zlib')) {
	ob_end_clean();
	ob_start('ob_gzhandler');
    }
} else if ($phpver > '4.0') {
    if (strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	if (extension_loaded('zlib')) {
	    $do_gzip_compress = TRUE;
	    ob_start();
	    ob_implicit_flush(0);
	    //header('Content-Encoding: gzip');
	}
    }
}

$phpver = explode(".", $phpver);
$phpver = "$phpver[0]$phpver[1]";
if ($phpver >= 41) {
    $PHP_SELF = $_SERVER['PHP_SELF'];
}

if (!ini_get("register_globals")) {
    import_request_variables('GPC');
}

foreach ($_GET as $secvalue) {
    if ((eregi("<[^>]*script*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*object*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*iframe*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*applet*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*meta*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*style*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*form*\"?[^>]*>", $secvalue)) ||
	(eregi("<[^>]*img*\"?[^>]*>", $secvalue)) ||
	(eregi("\([^>]*\"?[^)]*\)", $secvalue)) ||
	(eregi("\"", $secvalue))) {
	die ("I don't like you...");
    }
}

foreach ($_POST as $secvalue) {
    if ((eregi("<[^>]*script*\"?[^>]*>", $secvalue)) ||	(eregi("<[^>]*style*\"?[^>]*>", $secvalue))) {
        Header("Location: index.php");
        die();
    }
}

if (eregi("mainfile.php",$PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if ($forum_admin == 1) {
    require_once("../../../config.php");
    require_once("../../../db/db.php");
} elseif ($inside_mod == 1) {
    require_once("../../config.php");
    require_once("../../db/db.php");
} else {
    require_once("config.php");
    require_once("db/db.php");
    /* FOLLOWING TWO LINES ARE DEPRECATED BUT ARE HERE FOR OLD MODULES COMPATIBILITY */
    /* PLEASE START USING THE NEW SQL ABSTRACTION LAYER. SEE MODULES DOC FOR DETAILS */
    require_once("includes/sql_layer.php");
    $dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
}

$mainfile = 1;
$sql = "SELECT sitename, nukeurl, site_logo, slogan, startdate, adminmail, anonpost, Default_Theme, foot1, foot2, foot3, commentlimit, anonymous, minpass, pollcomm, articlecomm, broadcast_msg, my_headlines, top, storyhome, user_news, oldnum, ultramode, banners, backend_title, backend_language, language, locale, multilingual, useflags, notify, notify_email, notify_subject, notify_message, notify_from, footermsgtxt, email_send, attachmentdir, attachments, attachments_view, download_dir, defaultpopserver, singleaccount, singleaccountname, numaccounts, imgpath, filter_forward, moderate, admingraphic, httpref, httprefmax, CensorMode, CensorReplace, copyright, Version_Num FROM ".$prefix."_config";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$sitename = $row[sitename];
$nukeurl = $row[nukeurl];
$site_logo = $row[site_logo];
$slogan = $row[slogan];
$startdate = $row[startdate];
$adminmail = $row[adminmail];
$anonpost = $row[anonpost];
$Default_Theme = $row[Default_Theme];
$foot1 = $row[foot1];
$foot2 = $row[foot2];
$foot3 = $row[foot3];
$commentlimit = $row[commentlimit];
$anonymous = $row[anonymous];
$minpass = $row[minpass];
$pollcomm = $row[pollcomm];
$articlecomm = $row[articlecomm];
$broadcast_msg = $row[broadcast_msg];
$my_headlines = $row[my_headlines];
$top = $row[top];
$storyhome = $row[storyhome];
$user_news = $row[user_news];
$oldnum = $row[oldnum];
$ultramode = $row[ultramode];
$banners = $row[banners];
$backend_title = $row[backend_title];
$backend_language = $row[backend_language];
$language = $row[language];
$locale = $row[locale];
$multilingual = $row[multilingual];
$useflags = $row[useflags];
$notify = $row[notify];
$notify_email = $row[notify_email];
$notify_subject = $row[notify_subject];
$notify_message = $row[notify_message];
$notify_from = $row[notify_from];
$footermsgtxt = $row[footermsgtxt];
$email_send = $row[email_send];
$attachmentdir = $row[attachmentdir];
$attachments = $row[attachments];
$attachments_view = $row[attachments_view];
$download_dir = $row[download_dir];
$defaultpopserver = $row[defaultpopserver];
$singleaccount = $row[singleaccount];
$singleaccountname = $row[singleaccountname];
$numaccounts = $row[numaccounts];
$imgpath = $row[imgpath];
$filter_forward = $row[filter_forward];
$moderate = $row[moderate];
$admingraphic = $row[admingraphic];
$httpref = $row[httpref];
$httprefmax = $row[httprefmax];
$CensorMode = $row[CensorMode];
$CensorReplace = $row[CensorReplace];
$copyright = $row[copyright];
$Version_Num = $row[Version_Num];
$domain = eregi_replace("http://", "", $nukeurl);
$tipath = "images/topics/";
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;

if ($forum_admin != 1) {
    if (isset($newlang) AND !eregi("\.","$newlang")) {
	if (file_exists("language/lang-$newlang.php")) {
	    setcookie("lang",$newlang,time()+31536000);
	    include("language/lang-$newlang.php");
	    $currentlang = $newlang;
	} else {
	    setcookie("lang",$language,time()+31536000);
	    include("language/lang-$language.php");
	    $currentlang = $language;
	}
    } elseif (isset($lang)) {
	include("language/lang-$lang.php");
	$currentlang = $lang;
    } else {
	setcookie("lang",$language,time()+31536000);
	include("language/lang-$language.php");
	$currentlang = $language;
    }
}

function get_lang($module) {
    global $currentlang, $language;
    if (file_exists("modules/$module/language/lang-$currentlang.php")) {
	if ($module == admin) {
	    include_once("admin/language/lang-$currentlang.php");
	} else {
	    include_once("modules/$module/language/lang-$currentlang.php");
	}
    } else {
	if ($module == admin) {
	    include_once("admin/language/lang-$currentlang.php");
	} else {
	    include_once("modules/$module/language/lang-$language.php");
	}
    }
}

function is_admin($admin) {
    global $prefix, $db;
    if(!is_array($admin)) {
	$admin = base64_decode($admin);
	$admin = explode(":", $admin);
        $aid = "$admin[0]";
	$pwd = "$admin[1]";
    } else {
        $aid = "$admin[0]";
	$pwd = "$admin[1]";
    }
    if ($aid != "" AND $pwd != "") {
	$sql = "SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$pass = $row[pwd];
	if($pass == $pwd && $pass != "") {
	    return 1;
	}
    }
    return 0;
}

function is_user($user) {
    global $prefix, $db, $user_prefix;
    if(!is_array($user)) {
	$user = base64_decode($user);
	$user = explode(":", $user);
        $uid = "$user[0]";
	$pwd = "$user[2]";
    } else {
        $uid = "$user[0]";
	$pwd = "$user[2]";
    }
    if ($uid != "" AND $pwd != "") {
	$sql = "SELECT user_password FROM ".$user_prefix."_users WHERE user_id='$uid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$pass = $row[user_password];
	if($pass == $pwd && $pass != "") {
	    return 1;
	}
    }
    return 0;
}


function title($text) {
    OpenTable();
    echo "<center><font class=\"title\"><b>$text</b></font></center>";
    CloseTable();
    echo "<br>";
}

function is_active($module) {
    global $prefix, $db;
    $sql = "SELECT active FROM ".$prefix."_modules WHERE title='$module'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $act = $row[active];
    if (!$result OR $act == 0) {
	return 0;
    } else {
	return 1;
    }
}

function render_blocks($side, $blockfile, $title, $content, $bid, $url) {
    if ($url == "") {
	if ($blockfile == "") {
	    if ($side == "c") {
		themecenterbox($title, $content);
	    } elseif ($side == "d") {
		themecenterbox($title, $content);
	    } else {
		themesidebox($title, $content);
	    }
	} else {
	    if ($side == "c") {
		blockfileinc($title, $blockfile, 1);
	    } elseif ($side == "d") {
		blockfileinc($title, $blockfile, 1);
	    } else {
		blockfileinc($title, $blockfile);
	    }
	}
    } else {
	if ($side == "c" OR $side == "d") {
	    headlines($bid,1);
	} else {
    	    headlines($bid);
	}
    }
}

function blocks($side) {
    global $storynum, $prefix, $multilingual, $currentlang, $db, $admin, $user;
    if ($multilingual == 1) {
    	$querylang = "AND (blanguage='$currentlang' OR blanguage='')";
    } else {
    	$querylang = "";
    }
    if (strtolower($side[0]) == "l") {
	$pos = "l";
    } elseif (strtolower($side[0]) == "r") {
	$pos = "r";
    }  elseif (strtolower($side[0]) == "c") {
	$pos = "c";
    } elseif  (strtolower($side[0]) == "d") {
	$pos = "d";
    }
    $side = $pos;
    $sql = "SELECT bid, bkey, title, content, url, blockfile, view FROM ".$prefix."_blocks WHERE bposition='$pos' AND active='1' $querylang ORDER BY weight ASC";
    $result = $db->sql_query($sql);
    while($row = $db->sql_fetchrow($result)) {
	$bid = $row[bid];
	$title = $row[title];
	$content = $row[content];
	$url = $row[url];
	$blockfile = $row[blockfile];
	$view = $row[view];
	if ($row[bkey] == admin) {
	    adminblock();
	} elseif ($row[bkey] == userbox) {
	    userblock();
	} elseif ($row[bkey] == "") {
	    if ($view == 0) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    } elseif ($view == 1 AND is_user($user) || is_admin($admin)) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    } elseif ($view == 2 AND is_admin($admin)) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    } elseif ($view == 3 AND !is_user($user) || is_admin($admin)) {
		render_blocks($side, $blockfile, $title, $content, $bid, $url);
	    }
	}
    }
}

function message_box() {
    global $bgcolor1, $bgcolor2, $user, $admin, $cookie, $textcolor2, $prefix, $multilingual, $currentlang, $db;
    if ($multilingual == 1) {
	$querylang = "AND (mlanguage='$currentlang' OR mlanguage='')";
    } else {
	$querylang = "";
    }
    $sql = "SELECT mid, title, content, date, expire, view FROM ".$prefix."_message WHERE active='1' $querylang";
    $result = $db->sql_query($sql);
    if ($numrows = $db->sql_numrows($result) == 0) {
	return;
    } else {
	while ($row = $db->sql_fetchrow($result)) {
	    $mid = $row[mid];
	    $title = $row[title];
	    $content = $row[content];
	    $mdate = $row[date];
	    $expire = $row[expire];
	    $view = $row[view];
	if ($title != "" && $content != "") {
	    if ($expire == 0) {
		$remain = _UNLIMITED;
	    } else {
		$etime = (($mdate+$expire)-time())/3600;
		$etime = (int)$etime;
		if ($etime < 1) {
		    $remain = _EXPIRELESSHOUR;
		} else {
		    $remain = ""._EXPIREIN." $etime "._HOURS."";
		}
	    }
	    if ($view == 4 AND is_admin($admin)) {
                OpenTable();
                echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n"
		    ."<font class=\"content\">$content</font>"
		    ."<br><br><center><font class=\"content\">[ "._MVIEWADMIN." - $remain - <a href=\"admin.php?op=editmsg&mid=$mid\">"._EDIT."</a> ]</font></center>";
		CloseTable();
		echo "<br>";
	    } elseif ($view == 3 AND is_user($user) || is_admin($admin)) {
                OpenTable();
                echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n"
		    ."<font class=\"content\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font class=\"content\">[ "._MVIEWUSERS." - $remain - <a href=\"admin.php?op=editmsg&mid=$mid\">"._EDIT."</a> ]</font></center>";
		}
    		CloseTable();
		echo "<br>";
	    } elseif ($view == 2 AND !is_user($user) || is_admin($admin)) {
                OpenTable();
                echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n"
		    ."<font class=\"content\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font class=\"content\">[ "._MVIEWANON." - $remain - <a href=\"admin.php?op=editmsg&mid=$mid\">"._EDIT."</a> ]</font></center>";
		}
		CloseTable();
		echo "<br>";
	    } elseif ($view == 1) {
                OpenTable();
                echo "<center><font class=\"option\" color=\"$textcolor2\"><b>$title</b></font></center><br>\n"
		    ."<font class=\"content\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font class=\"content\">[ "._MVIEWALL." - $remain - <a href=\"admin.php?op=editmsg&mid=$mid\">"._EDIT."</a> ]</font></center>";
		}
		CloseTable();
		echo "<br>";
	    }
	    if ($expire != 0) {
	    	$past = time()-$expire;
		if ($mdate < $past) {
		    $db->sql_query("UPDATE ".$prefix."_message SET active='0' WHERE mid='$mid'");
		}
		}
	    }
	}
    }
}

function online() {
    global $user, $cookie, $prefix, $db;
    cookiedecode($user);
    $ip = $_SERVER["REMOTE_ADDR"];
    $uname = $cookie[1];
    if (!isset($uname)) {
        $uname = "$ip";
        $guest = 1;
    }
    $past = time()-1800;
    $sql = "DELETE FROM ".$prefix."_session WHERE time < $past";
    $db->sql_query($sql);
    $sql = "SELECT time FROM ".$prefix."_session WHERE uname='$uname'";
    $result = $db->sql_query($sql);
    $ctime = time();
    if ($row = $db->sql_fetchrow($result)) {
	$sql = "UPDATE ".$prefix."_session SET uname='$uname', time='$ctime', host_addr='$ip', guest='$guest' WHERE uname='$uname'";
	$db->sql_query($sql);
    } else {
	$sql = "INSERT INTO ".$prefix."_session (uname, time, host_addr, guest) VALUES ('$uname', '$ctime', '$ip', '$guest')";
	$db->sql_query($sql);
    }
}

function blockfileinc($title, $blockfile, $side=0) {
    $blockfiletitle = $title;
    $file = @file("blocks/$blockfile");
    if (!$file) {
	$content = _BLOCKPROBLEM;
    } else {
	include("blocks/$blockfile");
    }
    if ($content == "") {
	$content = _BLOCKPROBLEM2;
    }
    if ($side == 1) {
	themecenterbox($blockfiletitle, $content);
    } elseif ($side == 2) {
	themecenterbox($blockfiletitle, $content);
    } else {
	themesidebox($blockfiletitle, $content);
    }
}

function selectlanguage() {
    global $useflags, $currentlang;
    if ($useflags == 1) {
    $title = _SELECTLANGUAGE;
    $content = "<center><font class=\"content\">"._SELECTGUILANG."<br><br>";
    $langdir = dir("language");
    while($func=$langdir->read()) {
	if(substr($func, 0, 5) == "lang-") {
    	    $menulist .= "$func ";
	}
    }
    closedir($langdir->handle);
    $menulist = explode(" ", $menulist);
    sort($menulist);
    for ($i=0; $i < sizeof($menulist); $i++) {
        if($menulist[$i]!="") {
	    $tl = ereg_replace("lang-","",$menulist[$i]);
	    $tl = ereg_replace(".php","",$tl);
	    $altlang = ucfirst($tl);
	    $content .= "<a href=\"index.php?newlang=$tl\"><img src=\"images/language/flag-$tl.png\" border=\"0\" alt=\"$altlang\" title=\"$altlang\" hspace=\"3\" vspace=\"3\"></a> ";
	}
    }
    $content .= "</font></center>";
    themesidebox($title, $content);
	} else {
    $title = _SELECTLANGUAGE;
    $content = "<center><font class=\"content\">"._SELECTGUILANG."<br><br></font>";
    $content .= "<form action=\"index.php\" method=\"get\"><select name=\"newlanguage\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
	    $handle=opendir('language');
	    while ($file = readdir($handle)) {
		if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
	            $langFound = $matches[1];
	            $languageslist .= "$langFound ";
	        }
	    }
	    closedir($handle);
	    $languageslist = explode(" ", $languageslist);
	    sort($languageslist);
	    for ($i=0; $i < sizeof($languageslist); $i++) {
		if($languageslist[$i]!="") {
	$content .= "<option value=\"index.php?newlang=$languageslist[$i]\" ";
		if($languageslist[$i]==$currentlang) $content .= " selected";
	$content .= ">".ucfirst($languageslist[$i])."</option>\n";
		}
    }
    $content .= "</select></form></center>";
    themesidebox($title, $content);
	}
}

function ultramode() {
    global $prefix, $db;
    $ultra = "ultramode.txt";
    $file = fopen("$ultra", "w");
    fwrite($file, "General purpose self-explanatory file with news headlines\n");
    $sql = "SELECT sid, aid, title, time, comments, topic FROM ".$prefix."_stories ORDER BY time DESC LIMIT 0,10";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$sql = "select topictext, topicimage from ".$prefix."_topics where topicid='$row[topic]'";
	$result2 = $db->sql_query($sql);
	$row2 = $db->sql_fetchrow($result2);
	$topictext = $row2[topictext];
	$topicimage = $row2[topicimage];
	$content = "%%\n$row[title]\n/modules.php?name=News&file=article&sid=$row[sid]\n$row[time]\n$row[aid]\n$row2[topictext]\n$row[comments]\n$row2[topicimage]\n";
	fwrite($file, $content);
    }
    fclose($file);
}

function cookiedecode($user) {
    global $cookie, $prefix, $db, $user_prefix;
    $user = base64_decode($user);
    $cookie = explode(":", $user);
    $sql = "SELECT user_password FROM ".$user_prefix."_users WHERE username='$cookie[1]'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $pass = $row[user_password];
    if ($cookie[2] == $pass && $pass != "") {
	return $cookie;
    } else {
	unset($user);
	unset($cookie);
    }
}

function getusrinfo($user) {
    global $userinfo, $user_prefix, $db;
    $user2 = base64_decode($user);
    $user3 = explode(":", $user2);
    $sql = "SELECT * FROM ".$user_prefix."_users WHERE username='$user3[1]' AND user_password='$user3[2]'";
    $result = $db->sql_query($sql);
    if ($db->sql_numrows($result) == 1) {
    	$userinfo = $db->sql_fetchrow($result);
    }
    return $userinfo;
}

function FixQuotes ($what = "") {
    $what = ereg_replace("'","''",$what);
    while (eregi("\\\\'", $what)) {
	$what = ereg_replace("\\\\'","'",$what);
    }
    return $what;
}

/*********************************************************/
/* text filter                                           */
/*********************************************************/

function check_words($Message) {
    global $EditedMessage;
    include("config.php");
    $EditedMessage = $Message;
    if ($CensorMode != 0) {
	if (is_array($CensorList)) {
	    $Replace = $CensorReplace;
	    if ($CensorMode == 1) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
		}
	    } elseif ($CensorMode == 2) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
		}
	    } elseif ($CensorMode == 3) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
		}
	    }
	}
    }
    return ($EditedMessage);
}

function delQuotes($string){
    /* no recursive function to add quote to an HTML tag if needed */
    /* and delete duplicate spaces between attribs. */
    $tmp="";    # string buffer
    $result=""; # result string
    $i=0;
    $attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
    $quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
    $len = strlen($string);
    while ($i<$len) {
	switch($string[$i]) { # What car is it in the buffer ?
	    case "\"": #"       # a quote.
		if ($quote==0) {
		    $quote=1;
		} else {
		    $quote=0;
		    if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
		    $tmp="";
		    $attrib=-1;
		}
		break;
	    case "=":           # an equal - attrib delimiter
		if ($quote==0) {  # Is it found in a string ?
		    $attrib=1;
		    if ($tmp!="") $result.=" $tmp";
		    $tmp="";
		} else $tmp .= '=';
		break;
	    case " ":           # a blank ?
		if ($attrib>0) {  # add it to the string, if one opened.
		    $tmp .= $string[$i];
		}
		break;
	    default:            # Other
		if ($attrib<0)    # If we weren't in an attrib, set attrib to 0
		$attrib=0;
		$tmp .= $string[$i];
		break;
	}
	$i++;
    }
    if (($quote!=0) && ($tmp != "")) {
	if ($attrib==1) $result .= "=";
	/* If it is the value of an atrib, add the '=' */
	$result .= "\"$tmp\"";  /* Add quote if needed (the reason of the function ;-) */
    }
    return $result;
}

function check_html ($str, $strip="") {
    /* The core of this code has been lifted from phpslash */
    /* which is licenced under the GPL. */
    include("config.php");
    if ($strip == "nohtml")
    	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",
                         '<\\1>', $str);
               // Delete all spaces from html tags .
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",
                         '<a href="\\1">', $str); # "
               // Delete all attribs from Anchor, except an href, double quoted.
	$str = eregi_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
	       // Delete all img tags
	$tmp = "";
	while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
		$i = strpos($str,$reg[0]);
		$l = strlen($reg[0]);
		if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
		else $tag = strtolower($reg[1]);
		if ($a = $AllowableHTML[$tag])
			if ($reg[1][0] == "/") $tag = "</$tag>";
			elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
			else {
			  # Place here the double quote fix function.
			  $attrb_list=delQuotes($reg[2]);
			  // A VER
			  $attrb_list = ereg_replace("&","&amp;",$attrb_list);
			  $tag = "<$tag" . $attrb_list . ">";
			} # Attribs in tag allowed
		else $tag = "";
		$tmp .= substr($str,0,$i) . $tag;
		$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	/* Squash PHP tags unconditionally */
	$str = ereg_replace("<\?","",$str);
	return $str;
}

function filter_text($Message, $strip="") {
    global $EditedMessage;
    check_words($Message);
    $EditedMessage=check_html($EditedMessage, $strip);
    return ($EditedMessage);
}

/*********************************************************/
/* formatting stories                                    */
/*********************************************************/

function formatTimestamp($time) {
    global $datetime, $locale;
    setlocale (LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._DATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}

function formatAidHeader($aid) {
    global $prefix, $db;
    $sql = "SELECT url, email FROM ".$prefix."_authors WHERE aid='$aid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $url = $row[url];
    $email = $row[email];
    if (isset($url)) {
	$aid = "<a href=\"$url\">$aid</a>";
    } elseif (isset($email)) {
	$aid = "<a href=\"mailto:$email\">$aid</a>";
    } else {
	$aid = $aid;
    }
    echo "$aid";
}

function get_author($aid) {
    global $prefix, $db;
    $sql = "SELECT url, email FROM ".$prefix."_authors WHERE aid='$aid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    if (isset($row[url])) {
	$aid = "<a href=\"$row[url]\">$aid</a>";
    } elseif (isset($row[email])) {
	$aid = "<a href=\"mailto:$row[email]\">$aid</a>";
    } else {
	$aid = $aid;
    }
    return($aid);
}

function themepreview($title, $hometext, $bodytext="", $notes="") {
    echo "<b>$title</b><br><br>$hometext";
    if ($bodytext != "") {
	echo "<br><br>$bodytext";
    }
    if ($notes != "") {
	echo "<br><br><b>"._NOTE."</b> <i>$notes</i>";
    }
}

function adminblock() {
    global $admin, $prefix, $db;
    if (is_admin($admin)) {
	$sql = "SELECT title, content FROM ".$prefix."_blocks WHERE bkey='admin'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
	    $content = "<font class=\"content\">$row[content]</font>";
	    themesidebox($row[title], $row[content]);
	}
	$title = ""._WAITINGCONT."";
	$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_queue"));
	$content = "<font class=\"content\">";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=submissions\">"._SUBMISSIONS."</a>: $num<br>";
	$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_reviews_add"));
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=reviews\">"._WREVIEWS."</a>: $num<br>";
	$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_newlink"));
	$brokenl = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_modrequest WHERE brokenlink='1'"));
	$modreql = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_links_modrequest WHERE brokenlink='0'"));
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=Links\">"._WLINKS."</a>: $num<br>";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=LinksListModRequests\">"._MODREQLINKS."</a>: $modreql<br>";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=LinksListBrokenLinks\">"._BROKENLINKS."</a>: $brokenl<br>";
	$num = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_newdownload"));
	$brokend = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_modrequest WHERE brokendownload='1'"));
	$modreqd = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_downloads_modrequest WHERE brokendownload='0'"));
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=downloads\">"._UDOWNLOADS."</a>: $num<br>";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=DownloadsListModRequests\">"._MODREQDOWN."</a>: $modreqd<br>";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=DownloadsListBrokenDownloads\">"._BROKENDOWN."</a>: $brokend<br></font>";
	themesidebox($title, $content);
    }
}

function loginbox() {
    global $user;
    if (!is_user($user)) {
	$title = _LOGIN;
	$boxstuff = "<form action=\"modules.php?name=Your_Account\" method=\"post\">";
	$boxstuff .= "<center><font class=\"content\">"._NICKNAME."<br>";
	$boxstuff .= "<input type=\"text\" name=\"username\" size=\"8\" maxlength=\"25\"><br>";
	$boxstuff .= ""._PASSWORD."<br>";
	$boxstuff .= "<input type=\"password\" name=\"user_password\" size=\"8\" maxlength=\"20\"><br>";
	$boxstuff .= "<input type=\"hidden\" name=\"op\" value=\"login\">";
	$boxstuff .= "<input type=\"submit\" value=\""._LOGIN."\"></font></center></form>";
	$boxstuff .= "<center><font class=\"content\">"._ASREGISTERED."</font></center>";
	themesidebox($title, $boxstuff);
    }
}

function userblock() {
    global $user, $cookie, $db, $user_prefix;
    if((is_user($user)) AND ($cookie[8])) {
	$sql = "SELECT ublock FROM ".$user_prefix."_users WHERE user_id='$cookie[0]'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$title = ""._MENUFOR." $cookie[1]";
	themesidebox($title, $row[ublock]);
    }
}

function getTopics($s_sid) {
    global $topicname, $topicimage, $topictext, $prefix, $db;
    $sid = $s_sid;
    $sql = "SELECT topic FROM ".$prefix."_stories WHERE sid='$sid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $sql = "SELECT topicid, topicname, topicimage, topictext FROM ".$prefix."_topics WHERE topicid='$row[topic]'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $topicid = $row[topicid];
    $topicname = $row[topicname];
    $topicimage = $row[topicimage];
    $topictext = $row[topictext];
}

function headlines($bid, $cenbox=0) {
    global $prefix, $db;
    $sql = "SELECT title, content, url, refresh, time FROM ".$prefix."_blocks WHERE bid='$bid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title = $row[title];
    $content = $row[content];
    $url = $row[url];
    $refresh = $row[refresh];
    $otime = $row[otime];
    $past = time()-$refresh;
    if ($otime < $past) {
	$btime = time();
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
	    $content = "";
	    $sql = "UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'";
	    $db->sql_query($sql);
	    $cont = 0;
	    if ($cenbox == 0) {
		themesidebox($title, $content);
	    } else {
		themecenterbox($title, $content);
	    }
	    return;
	}
	if ($fp) {
	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
	    $string	= "";
	    while(!feof($fp)) {
	    	$pagetext = fgets($fp,300);
	    	$string .= chop($pagetext);
	    }
	    fputs($fp,"Connection: close\r\n\r\n");
	    fclose($fp);
	    $items = explode("</item>",$string);
	    $content = "<font class=\"content\">";
	    for ($i=0;$i<10;$i++) {
		$link = ereg_replace(".*<link>","",$items[$i]);
		$link = ereg_replace("</link>.*","",$link);
		$title2 = ereg_replace(".*<title>","",$items[$i]);
		$title2 = ereg_replace("</title>.*","",$title2);
		$title2 = stripslashes($title2);
		if ($items[$i] == "" AND $cont != 1) {
		    $content = "";
		    $sql = "UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'";
		    $db->sql_query($sql);
		    $cont = 0;
		    if ($cenbox == 0) {
			themesidebox($title, $content);
		    } else {
			themecenterbox($title, $content);
		    }
		    return;
		} else {
		    if (strcmp($link,$title2) AND $items[$i] != "") {
			$cont = 1;
			$content .= "<strong><big>&middot;</big></strong><a href=\"$link\" target=\"new\">$title2</a><br>\n";
		    }
		}
	    }

	}
	$sql = "UPDATE ".$prefix."_blocks SET content='$content', time='$btime' WHERE bid='$bid'";
	$db->sql_query($sql);
    }
    $siteurl = ereg_replace("http://","",$url);
    $siteurl = explode("/",$siteurl);
    if (($cont == 1) OR ($content != "")) {
	$content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
    } elseif (($cont == 0) OR ($content == "")) {
	$content = "<font class=\"content\">"._RSSPROBLEM."</font>";
    }
    if ($cenbox == 0) {
	themesidebox($title, $content);
    } else {
	themecenterbox($title, $content);
    }
}

function automated_news() {
    global $prefix, $multilingual, $currentlang, $db;
    if ($multilingual == 1) {
	$querylang = "WHERE (alanguage='$currentlang' OR alanguage='')"; /* the OR is needed to display stories who are posted to ALL languages */
    } else {
	$querylang = "";
    }
    $today = getdate();
    $day = $today[mday];
    if ($day < 10) {
	$day = "0$day";
    }
    $month = $today[mon];
    if ($month < 10) {
	$month = "0$month";
    }
    $year = $today[year];
    $hour = $today[hours];
    $min = $today[minutes];
    $sec = "00";
    $sql = "SELECT anid, time FROM ".$prefix."_autonews $querylang";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$anid = $row[anid];
	$time = $row[time];
	ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $date);
	if (($date[1] <= $year) AND ($date[2] <= $month) AND ($date[3] <= $day)) {
	    if (($date[4] < $hour) AND ($date[5] >= $min) OR ($date[4] <= $hour) AND ($date[5] <= $min)) {
		$sql2 = "SELECT * FROM ".$prefix."_autonews WHERE anid='$anid'";
		$result2 = $db->sql_query($sql2);
		while ($row2 = $db->sql_fetchrow($result2)) {
		    $title = stripslashes(FixQuotes($row2[title]));
		    $hometext = stripslashes(FixQuotes($row2[hometext]));
		    $bodytext = stripslashes(FixQuotes($row2[bodytext]));
		    $notes = stripslashes(FixQuotes($row2[notes]));
		    $sql = "INSERT INTO ".$prefix."_stories VALUES (NULL, '$row2[catid]', '$row2[aid]', '$title', '$row2[time]', '$hometext', '$bodytext', '0', '0', '$row2[topic]', '$row2[informant]', '$notes', '$row2[ihome]', '$row2[alanguage]', '$row2[acomm]', '0', '0', '0', '0', '$row2[associated]')";
		    $db->sql_query($sql);
		    $sql = "DELETE FROM ".$prefix."_autonews WHERE anid='$anid'";
		    $db->sql_query($sql);
		}
	    }
	}
    }
}

function themecenterbox($title, $content) {
    OpenTable();
    echo "<center><font class=\"option\"><b>$title</b></font></center><br>"
	."$content";
    CloseTable();
    echo "<br>";
}

function public_message() {
    global $prefix, $user_prefix, $db, $user, $admin, $p_msg, $cookie, $broadcast_msg;
    if ($broadcast_msg == 1) {
    if (is_user($user)) {
        cookiedecode($user);
	$sql = "SELECT broadcast FROM ".$user_prefix."_users WHERE username='$cookie[1]'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$upref = $row[broadcast];
	if ($upref == 1) {
	    $t_off = "<br><p align=\"right\">[ <a href=\"modules.php?name=Your_Account&amp;op=edithome\"><font color=\"FFFFFF\" size=\"2\">"._TURNOFFMSG."</font></a> ]</font>";
	    $pm_show = 1;
	} else {
	    $pm_show = 0;
	}
    } else {
	$t_off = "";
    }
    if (!is_user($user) OR (is_user($user) AND ($pm_show == 1))) {
	$c_mid = base64_decode($p_msg);
	$sql = "SELECT mid, content, date, who FROM ".$prefix."_public_messages WHERE mid > '$c_mid' ORDER BY date ASC LIMIT 1";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$mid = $row[mid];
	$content = $row[content];
	$tdate = $row[date];
	$who = $row[who];
	if ((!isset($c_mid)) OR ($c_mid = $mid)) {
    	    $public_msg = "<br><table width=\"90%\" border=\"1\" cellspacing=\"2\" cellpadding=\"0\" bgcolor=\"FFFFFF\" align=\"center\"><tr><td>\n";
    	    $public_msg .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\" bgcolor=\"FF0000\"><tr><td>\n";
    	    $public_msg .= "<font color=\"FFFFFF\" size=\"3\"><b>"._BROADCASTFROM." <a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$who\"><font color=\"FFFFFF\" size=\"3\">$who</font></a>: \"$content\"</b>";
	    $public_msg .= "$t_off";
	    $public_msg .= "</td></tr></table>";
    	    $public_msg .= "</td></tr></table>";
	    $ref_date = $tdate+600;
	    $actual_date = time();
	    if ($actual_date >= $ref_date) {
		$public_msg = "";
		$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_public_messages"));
		if ($numrows == 1) {
		    $db->sql_query("DELETE FROM ".$prefix."_public_messages");
		    $mid = 0;
		} else {
		    $db->sql_query("DELETE FROM ".$prefix."_public_messages WHERE mid='$mid'");
		}
	    }
	    if ($mid == 0 OR $mid == "") {
		setcookie("p_msg");
	    } else {
    		$mid = base64_encode($mid);
		setcookie("p_msg",$mid,time()+600);
	    }
	}
    }
    } else {
	$public_msg = "";
    }
    return($public_msg);
}

function get_theme() {
    global $user, $cookie, $Default_Theme;
    if(is_user($user)) {
	$user2 = base64_decode($user);
	$t_cookie = explode(":", $user2);
	if($t_cookie[9]=="") $t_cookie[9]=$Default_Theme;
	if(isset($theme)) $t_cookie[9]=$theme;
	if(!$tfile=@opendir("themes/$t_cookie[9]")) {
	    $ThemeSel = $Default_Theme;
	} else {
	    $ThemeSel = $t_cookie[9];
	}
    } else {
	$ThemeSel = $Default_Theme;
    }
    return($ThemeSel);
}

function removecrlf($str) {
    // Function for Security Fix by Ulf Harnhammar, VSU Security 2002
    // Looks like I don't have so bad track record of security reports as Ulf believes
    // He decided to not contact me, but I'm always here, digging on the net
    return strtr($str, "\015\012", ' ');
}

?>
