<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$pagetitle = "- "._SUBMITNEWS."";

function defaultDisplay() {
    global $AllowableHTML, $prefix, $user, $cookie, $anonymous, $currentlang, $multilingual, $db, $module_name;
    include ('header.php');
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SUBMITNEWS."</b></font><br><br>";
    echo "<font class=\"content\"><i>"._SUBMITADVICE."</i></font></center><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    if (is_user($user)) getusrinfo($user);
    echo "<p><form action=\"modules.php?name=$module_name\" method=\"post\">"
	."<b>"._YOURNAME.":</b> ";
    if (is_user($user)) {
	cookiedecode($user);
	echo "<a href=\"modules.php?name=Your_Account\">$cookie[1]</a> <font class=\"content\">[ <a href=\"modules.php?name=Your_Account&amp;op=logout\">"._LOGOUT."</a> ]</font>";
    } else {
    	echo "$anonymous <font class=\"content\">[ <a href=\"modules.php?name=Your_Account\">"._NEWUSER."</a> ]</font>";
    }
    echo "<br><br>"
        ."<b>"._SUBTITLE."</b> "
        ."("._BEDESCRIPTIVE.")<br>"
        ."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\"><br><font class=\"content\">("._BADTITLES.")</font>"
        ."<br><br>"
        ."<b>"._TOPIC.":</b> <select name=\"topic\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while ($row = $db->sql_fetchrow($result)) {
	$topicid = $row[topicid];
	$topics = $row[topictext];
        if ($topicid == $topic) {
	    $sel = "selected ";
	}
    	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    if ($multilingual == 1) {
	echo "<br><br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
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
		echo "<option value=\"$languageslist[$i]\" ";
	    if($languageslist[$i]==$currentlang) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select>";
    } else {
	echo "<input type=\"hidden\" name=\"alanguage\" value=\"$language\">";
    }
	echo "<br><br>"
        ."<b>"._STORYTEXT.":</b> ("._HTMLISFINE.")<br>"
	."<textarea cols=\"50\" rows=\"12\" name=\"story\"></textarea><br>"
        ."<br><br><b>"._EXTENDEDTEXT.":</b><br>"
	."<textarea cols=\"50\" rows=\"12\" name=\"storyext\"></textarea><br>"
	."("._AREYOUSURE.")</font><br><br>"
        ."<font class=\"content\">"._ALLOWEDHTML."<br>";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br><br><input type=\"submit\" name=\"op\" value=\""._PREVIEW."\">&nbsp;&nbsp;"
	."<select name=\"posttype\">\n"
	."<option value=\"exttrans\">"._EXTRANS."</option>\n"
	."<option value=\"html\" >"._HTMLFORMATED."</option>\n"
	."<option value=\"plaintext\" selected>"._PLAINTEXT."</option>\n"
	."</select>"
	."<br>("._SUBPREVIEW.")</form>";
    CloseTable();
    include ('footer.php');
}

function PreviewStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype) {
    global $user, $cookie, $bgcolor1, $bgcolor2, $anonymous, $prefix, $multilingual, $AllowableHTML, $db, $module_name;
    include ('header.php');
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    $storyext = stripslashes($storyext);
    if ($posttype=="exttrans") {
        $f_story = nl2br(htmlspecialchars($story));
	$f_storyext = nl2br(htmlspecialchars($storyext));
    } elseif ($posttype=="plaintext") {
        $f_story = nl2br($story);
	$f_storyext = nl2br($storyext);
    } else {
	$f_story = $story;
	$f_storyext = $storyext;
    }
    $story2 = "$f_story<br><br>$f_storyext";
    OpenTable();
    echo "<center><font class=\"title\"><b>"._NEWSUBPREVIEW."</b></font>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><i>"._STORYLOOK."</i></center><br><br>";
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>";
    if ($topic=="") {
        $topicimage="AllTopics.gif";
        $warning = "<center><blink><b>"._SELECTTOPIC."</b></blink></center>";
    } else {
        $warning = "";
        $sql = "SELECT topicimage FROM ".$prefix."_topics WHERE topicid='$topic'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$topicimage = $row[topicimage];
    }
    echo "<img src=\"images/topics/$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $story2);
    echo "$warning"
	."</td></tr></table></td></tr></table>"
	."<br><br><center><font class=\"tiny\">"._CHECKSTORY."</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<p><form action=\"modules.php?name=$module_name\" method=\"post\">"
	."<b>"._YOURNAME.":</b> ";
    if (is_user($user)) {
    	cookiedecode($user);
	echo "<a href=\"modules.php?name=Your_Account\">$cookie[1]</a> <font class=\"content\">[ <a href=\"modules.php?name=Your_Account&amp;op=logout\">"._LOGOUT."</a> ]</font>";
    } else {
	echo "$anonymous";
    }
    echo "<br><br><b>"._SUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\" value=\"$subject\">"
	."<br><br><b>"._TOPIC.": </b><select name=\"topic\">";
    $sql = "SELECT topicid, topictext FROM ".$prefix."_topics ORDER BY topictext";
    $result = $db->sql_query($sql);
    echo "<OPTION VALUE=\"\">"._SELECTTOPIC."</option>\n";
    while ($row = $db->sql_fetchrow($result)) {
	$topicid = $row[topicid];
	$topics = $row[topictext];
        if ($topicid == $topic) {
	    $sel = "selected ";
	}
	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    if ($multilingual == 1) {
	echo "<br><br><b>"._LANGUAGE.": </b>"
	    ."<select name=\"alanguage\">";
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
		echo "<option value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$alanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select>";
    }
    echo "<br><br><b>"._STORYTEXT.":</b> ("._HTMLISFINE.")<br>"
        ."<textarea cols=\"50\" rows=\"12\" name=\"story\">$story</textarea><br>"
	."<br><b>"._EXTENDEDTEXT.":</b><br>"
	."<textarea cols=\"50\" rows=\"12\" name=\"storyext\">$storyext</textarea><br>"
	."<font class=\"content\">("._AREYOUSURE.")</font><br><br>"
	.""._ALLOWEDHTML."<br>";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br><br>"
        ."<input type=\"submit\" name=\"op\" value=\""._PREVIEW."\">&nbsp;&nbsp;"
	."<input type=\"submit\" name=\"op\" value=\""._OK."\">&nbsp;&nbsp;"
	."<select name=\"posttype\"><option value=\"exttrans\"";
    if ($posttype=="exttrans") {
        echo " selected";
    }
    echo ">"._EXTRANS."</option>\n"
	."<OPTION value=\"html\"";;
    if ($posttype=="html") {
        echo " selected";
    }
    echo ">"._HTMLFORMATED."</option>\n"
	."<OPTION value=\"plaintext\"";
    if (($posttype!="exttrans") && ($posttype!="html")) {
        echo " selected";
    }
    echo ">"._PLAINTEXT."</option></select>"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function submitStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype) {
    global $user, $EditedMessage, $cookie, $anonymous, $notify, $notify_email, $notify_subject, $notify_message, $notify_from, $prefix, $db;
    if (is_user($user)) {
    	cookiedecode($user);
	$uid = $cookie[0];
	$name = $cookie[1];
    } else {
    	$uid = 1;
	$name = "$anonymous";
    }
    $subject = ereg_replace("\"", "''", $subject);
    $subject = FixQuotes(filter_text($subject, "nohtml"));
    if($posttype=="exttrans") {
    	$story = FixQuotes(nl2br(htmlspecialchars(check_words($story))));
	$storyext = FixQuotes(nl2br(htmlspecialchars(check_words($storyext))));
    } elseif($posttype=="plaintext") {
    	$story = FixQuotes(nl2br(filter_text($story)));
	$storyext = FixQuotes(nl2br(filter_text($storyext)));
    } else {
	$story = FixQuotes(filter_text($story));
	$storyext = FixQuotes(filter_text($storyext));
    }
    $sql = "INSERT INTO ".$prefix."_queue VALUES (NULL, '$uid', '$name', '$subject', '$story', '$storyext', now(), '$topic', '$alanguage')";
    $result = $db->sql_query($sql);
    if(!$result) {
    	echo ""._ERROR."<br>";
	exit();
    }
    if($notify) {
	$notify_message = "$notify_message\n\n\n========================================================\n$subject\n\n\n$story\n\n$storyext\n\n$name";
    	mail($notify_email, $notify_subject, $notify_message, "From: $notify_from\nX-Mailer: PHP/" . phpversion());
    }
    include ('header.php');
    OpenTable();
    $sql = "SELECT * FROM ".$prefix."_queue";
    $result = $db->sql_query($sql);
    $waiting = $db->sql_numrows($result);
    echo "<center><font class=\"title\">"._SUBSENT."</font><br><br>"
	."<font class=\"content\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');
}

switch($op) {

    case ""._PREVIEW."":
	PreviewStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype);
	break;

    case ""._OK."":
	SubmitStory($name, $address, $subject, $story, $storyext, $topic, $alanguage, $posttype);
	break;

    default:
	defaultDisplay();
	break;

}

?>