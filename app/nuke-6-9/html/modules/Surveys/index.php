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

$pagetitle = "- "._SURVEYS."";

if(!isset($pollID)) {
    include ('header.php');
    pollList();
    include ('footer.php');
} elseif(isset($forwarder)) {
    pollCollector($pollID, $voteID, $forwarder);
} elseif($op == "results" && $pollID > 0) {
    include ("header.php");
    OpenTable();
    echo "<center><font class=\"title\"><b>"._CURRENTPOLLRESULTS."</b></font></center>";
    CloseTable();
    echo "<br>";
    echo "<table border='0' width='100%'><tr><td width='70%' valign='top'>";
    OpenTable();
    pollResults($pollID);
    CloseTable();
    echo "</td><td>&nbsp;</td><td width='30%' valign='top'>";
    OpenTable();
    echo "<b>"._LAST5POLLS." $sitename</b><br><br>";
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    $resu = sql_query("SELECT pollID, pollTitle, voters FROM ".$prefix."_poll_desc where artid='0' order by timeStamp DESC limit 1,6", $dbi);
    while (list($plid, $pltitle, $plvoters) = sql_fetch_row($resu, $dbi)) {
	if ($pollID == $plid) {
	    echo "<img src='images/arrow.gif' border='0'>&nbsp;$pltitle ($plvoters "._LVOTES.")<br><br>";
	} else {
	    echo "<img src='images/arrow.gif' border='0'>&nbsp;<a href='modules.php?name=$module_name&op=results&pollID=$plid$r_options'>$pltitle</a> ($plvoters "._LVOTES.")<br><br>";
	}
    }
    echo "<a href='modules.php?name=$module_name'><b>"._MOREPOLLS."</b></a>";
    CloseTable();
    echo "</td></tr></table>";
    cookiedecode($user);
    if (($pollcomm) AND ($mode != "nocomments")) {
	echo "<br><br>";
	include("modules/Surveys/comments.php");
    }
    include ("footer.php");
} elseif($voteID > 0) {
    pollCollector($pollID, $voteID);
} elseif($pollID != pollLatest()) {
    include ('header.php');
    OpenTable();
    echo "<center><font class=\"option\"><b>"._SURVEY."</b></font></center>";
    CloseTable();
    echo "<br><br>";
    echo "<table border=\"0\" align=\"center\"><tr><td>";
    pollMain($pollID);
    echo "</td></tr></table>";
    include ('footer.php');
} else {
    include ('header.php');
    OpenTable();
    echo "<center><font class=\"option\"><b>"._CURRENTSURVEY."</b></font></center>";
    CloseTable();
    echo "<br><br><table border=\"0\" align=\"center\"><tr><td>";
    pollNewest();
    echo "</td></tr></table>";
    include ('footer.php');
}

/*********************************************************/
/* Functions                                             */
/*********************************************************/

function pollMain($pollID) {
    global $boxTitle, $boxContent, $pollcomm, $user, $cookie, $prefix, $dbi, $module_name;
    if(!isset($pollID))
	$pollID = 1;
    if(!isset($url))
	$url = sprintf("modules.php?name=$module_name&amp;op=results&amp;pollID=%d", $pollID);
    $boxContent .= "<form action=\"modules.php?name=$module_name\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $result = sql_query("SELECT pollTitle, voters FROM ".$prefix."_poll_desc WHERE pollID='$pollID'", $dbi);
    list($pollTitle, $voters) = sql_fetch_row($result, $dbi);
    $boxTitle = _SURVEY;
    $boxContent .= "<font class=\"content\"><b>$pollTitle</b></font><br><br>\n";
    $boxContent .= "<table border=\"0\" width=\"100%\">";
    for($i = 1; $i <= 12; $i++) {
	$result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'", $dbi);
	$object = sql_fetch_object($result, $dbi);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    if($optionText != "") {
		$boxContent .= "<tr><td valign=\"top\"><input type=\"radio\" name=\"voteID\" value=\"".$i."\"></td><td width=\"100%\"><font class=\"content\">$optionText</font></td></tr>\n";
	    }
	}
    }
    $boxContent .= "</table><br><center><font class=\"content\"><input type=\"submit\" value=\""._VOTE."\"></font><br>";
    if (is_user($user)) {
        cookiedecode($user);
    }
    for($i = 0; $i < 12; $i++) {
	$result = sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'", $dbi);
	$object = sql_fetch_object($result, $dbi);
	$optionCount = $object->optionCount;
	$sum = (int)$sum+$optionCount;
    }
    $boxContent .= "<br><font class=\"content\"><a href=\"modules.php?name=$module_name&amp;op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>"._RESULTS."</b></a><br><a href=\"modules.php?name=$module_name\"><b>"._POLLS."</b></a><br>";

    if ($pollcomm) {
	list($numcom) = sql_fetch_row(sql_query("select count(*) from ".$prefix."_pollcomments where pollID='$pollID'", $dbi), $dbi);
	$boxContent .= "<br>"._VOTES.": <b>$sum</b> <br> "._PCOMMENTS." <b>$numcom</b>\n\n";
    } else {
        $boxContent .= "<br>"._VOTES." <b>$sum</b>\n\n";
    }
    $boxContent .= "</font></center></form>\n\n";
    themesidebox($boxTitle, $boxContent);
}

function pollLatest() {
    global $prefix, $multilingual, $currentlang, $dbi;
    if ($multilingual == 1) {
	$querylang = "WHERE planguage='$currentlang' AND artid='0'";
    } else {
	$querylang = "WHERE artid='0'";
    }
    $result = sql_query("SELECT pollID FROM ".$prefix."_poll_desc $querylang ORDER BY pollID DESC LIMIT 1", $dbi);
    $pollID = sql_fetch_row($result, $dbi);
    return($pollID[0]);
}

function pollNewest() {
    $pollID = pollLatest();
    pollMain($pollID);
}

function pollCollector($pollID, $voteID, $forwarder) {
    global $HTTP_COOKIE_VARS, $prefix, $dbi;
    /* Fix for lamers that like to cheat on polls */
    if (empty($ip)) {
	$ip = $_SERVER["REMOTE_ADDR"];
    }
    $past = time()-1800;
    sql_query("DELETE FROM ".$prefix."_poll_check WHERE time < $past", $dbi);
    $result = sql_query("SELECT ip FROM ".$prefix."_poll_check WHERE (ip='$ip') AND (pollID='$pollID')", $dbi);
    list($ips) = sql_fetch_row($result, $dbi);
    $ctime = time();
    if ($ip == $ips) {
	$voteValid = 0;
    } else {
	sql_query("INSERT INTO ".$prefix."_poll_check (ip, time, pollID) VALUES ('$ip', '$ctime', '$pollID')", $dbi);
	$voteValid = "1";
    }
    /* Fix end */
    /* update database if the vote is valid */
    if($voteValid>0) {
        sql_query("UPDATE ".$prefix."_poll_data SET optionCount=optionCount+1 WHERE pollID='$pollID' AND voteID='$voteID'", $dbi);
        if ($voteID != "") {
	    sql_query("UPDATE ".$prefix."_poll_desc SET voters=voters+1 WHERE pollID='$pollID'", $dbi);
        }
	Header("Location: $forwarder");
    } else {
        Header("Location: $forwarder");
    }
    /* a lot of browsers can't handle it if there's an empty page */
    echo "<html><head></head><body></body></html>";
}

function pollList() {
    global $user, $cookie, $prefix, $multilingual, $currentlang, $admin, $dbi, $module_name;
    if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
    if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
    if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }
    if ($multilingual == 1) {
        $querylang = "WHERE planguage='$currentlang' AND artid='0'";
    } else {
        $querylang = "WHERE artid='0'";
    }
    $result = sql_query("SELECT pollID, pollTitle, timeStamp, voters FROM ".$prefix."_poll_desc $querylang ORDER BY timeStamp DESC", $dbi);
    $counter = 0;
    OpenTable();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._PASTSURVEYS."</b></font></center>";
    CloseTable();
    echo "<table border=\"0\" cellpadding=\"8\"><tr><td>";
    while($object = sql_fetch_object($result, $dbi)) {
	$resultArray[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray); $count++) {
	$id = $resultArray[$count][0];
	$pollTitle = $resultArray[$count][1];
	$voters = $resultArray[$count][3];
	for($i = 0; $i < 12; $i++) {
	    $result = sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$id' AND voteID='$i'", $dbi);
	    $object = sql_fetch_object($result, $dbi);
	    $optionCount = $object->optionCount;
	    $sum = (int)$sum+$optionCount;
	}
	echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=$module_name&amp;pollID=$id\">$pollTitle</a> ";
	if (is_admin($admin)) {
	    $editing = " - <a href=\"admin.php?op=polledit&amp;pollID=$id\">Edit</a>";
	} else {
	    $editing = "";
	}
	echo "(<a href=\"modules.php?name=$module_name&amp;op=results&amp;pollID=$id$r_options\">"._RESULTS."</a> - $sum "._LVOTES."$editing)<br>\n";
	$sum = 0;
    }
    echo "</td></tr></table>"
	."<br>";
    OpenTable();
    echo "<center><font class=\"title\"><b>"._SURVEYSATTACHED."</b></font></center>";
    CloseTable();
    echo "<table border=\"0\" cellpadding=\"8\"><tr><td>";
    if ($multilingual == 1) {
        $querylang = "WHERE planguage='$currentlang' AND artid!='0'";
    } else {
        $querylang = "WHERE artid!='0'";
    }
    $counter = 0;
    $result = sql_query("SELECT pollID, pollTitle, timeStamp, voters FROM ".$prefix."_poll_desc $querylang ORDER BY timeStamp DESC", $dbi);
    while($object = sql_fetch_object($result, $dbi)) {
	$resultArray2[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray2); $count++) {
	$id = $resultArray2[$count][0];
	$pollTitle = $resultArray2[$count][1];
	$voters = $resultArray2[$count][3];
	for($i = 0; $i < 12; $i++) {
	    $result = sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$id' AND voteID='$i'", $dbi);
	    $object = sql_fetch_object($result, $dbi);
	    $optionCount = $object->optionCount;
	    $sum = (int)$sum+$optionCount;
	}
	echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?name=$module_name&amp;pollID=$id\">$pollTitle</a> ";
	if (is_admin($admin)) {
	    $editing = " - <a href=\"admin.php?op=polledit&amp;pollID=$id\">Edit</a>";
	} else {
	    $editing = "";
	}
	$res = sql_query("select sid, title from ".$prefix."_stories where pollID='$id'", $dbi);
	list($sid, $title) = sql_fetch_row($res, $dbi);
	echo "(<a href=\"modules.php?name=$module_name&amp;op=results&amp;pollID=$id$r_options\">"._RESULTS."</a> - $sum "._LVOTES."$editing)<br>\n"
	    .""._ATTACHEDTOARTICLE." <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a><br><br>\n";
	$sum = "";
    }
    echo "</td></tr></table>";
    CloseTable();
}

function pollResults($pollID) {
    global $resultTableBgColor, $resultBarFile, $Default_Theme, $user, $cookie, $prefix, $dbi, $admin, $module_name;
    if(!isset($pollID)) $pollID = 1;
    $result = sql_query("SELECT pollID, pollTitle, timeStamp, artid FROM ".$prefix."_poll_desc WHERE pollID='$pollID'", $dbi);
    $holdtitle = sql_fetch_row($result, $dbi);
    echo "<b>$holdtitle[1]</b><br><br>";
    for($i = 0; $i < 12; $i++) {
	$result = sql_query("SELECT optionCount FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'", $dbi);
	$object = sql_fetch_object($result, $dbi);
	$optionCount = $object->optionCount;
	$sum = (int)$sum+$optionCount;
    }
    echo "<table border=\"0\">";
    /* cycle through all options */
    for($i = 1; $i <= 12; $i++) {
	/* select next vote option */
	$result = sql_query("SELECT pollID, optionText, optionCount, voteID FROM ".$prefix."_poll_data WHERE pollID='$pollID' AND voteID='$i'", $dbi);
	$object = sql_fetch_object($result, $dbi);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    $optionCount = $object->optionCount;
	    if($optionText != "") {
		echo "<tr><td>";
		echo "$optionText";
		echo "</td>";
		if($sum) {
		    $percent = 100 * $optionCount / $sum;
		} else {
		    $percent = 0;
		}
		echo "<td>";
		$percentInt = (int)$percent * 4 * 1;
		$percent2 = (int)$percent;
		if(is_user($user)) {
		    if($cookie[9]=="") $cookie[9]=$Default_Theme;
		    if(!$file=@opendir("themes/$cookie[9]")) {
			$ThemeSel = $Default_Theme;
		    } else {
			$ThemeSel = $cookie[9];
		    }
		} else {
		    $ThemeSel = $Default_Theme;
		}
		if (file_exists("themes/$ThemeSel/images/survey_leftbar.gif") AND file_exists("themes/$ThemeSel/images/survey_mainbar.gif") AND file_exists("themes/$ThemeSel/images/survey_rightbar.gif")) {
		    $l_size = getimagesize("themes/$ThemeSel/images/survey_leftbar.gif");
    		    $m_size = getimagesize("themes/$ThemeSel/images/survey_mainbar.gif");
		    $r_size = getimagesize("themes/$ThemeSel/images/survey_rightbar.gif");
		    $leftbar = "survey_leftbar.gif";
		    $mainbar = "survey_mainbar.gif";
		    $rightbar = "survey_rightbar.gif";
		} else {
		    $l_size = getimagesize("themes/$ThemeSel/images/leftbar.gif");
    		    $m_size = getimagesize("themes/$ThemeSel/images/mainbar.gif");
		    $r_size = getimagesize("themes/$ThemeSel/images/rightbar.gif");
		    $leftbar = "leftbar.gif";
		    $mainbar = "mainbar.gif";
		    $rightbar = "rightbar.gif";
		}
		if (file_exists("themes/$ThemeSel/images/survey_mainbar_d.gif")) {
		    $m1_size = getimagesize("themes/$ThemeSel/images/survey_mainbar_d.gif");
		    $mainbar_d = "survey_mainbar_d.gif";
		    if ($percent2 > 0 AND $percent2 <= 23) {
			$salto = "<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$percentInt\">";
		    } elseif ($percent2 > 24 AND $percent2 < 50) {
			$a = $percentInt - 100;
			$salto = "<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    } elseif ($percent2 > 49 AND $percent2 < 75) {
			$a = $percentInt - 200;
			$salto = "<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    } elseif ($percent2 > 74 AND $percent2 <= 100) {
			$a = $percentInt - 300;
			$salto = "<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
			        ."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"70\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar_d\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m1_size[1]\" width=\"30\">"
				."<img src=\"themes/$ThemeSel/images/$mainbar\" alt=\"$percent2 %\" title=\"$percent2 %\" height=\"$m_size[1]\" width=\"$a\">";
		    }
		}
		if ($percent > 0) {
		    echo "<img src=\"themes/$ThemeSel/images/$leftbar\" height=\"$l_size[1]\" width=\"$l_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    if (file_exists("themes/$ThemeSel/images/survey_mainbar_d.gif")) {
			echo "$salto";
		    } else {
			echo "<img src=\"themes/$ThemeSel/images/$mainbar\" height=\"$m_size[1]\" width=\"$percentInt\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    }
		    echo "<img src=\"themes/$ThemeSel/images/$rightbar\" height=\"$r_size[1]\" width=\"$r_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		} else {
		    echo "<img src=\"themes/$ThemeSel/images/$leftbar\" height=\"$l_size[1]\" width=\"$l_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    if (!file_exists("themes/$ThemeSel/images/survey_mainbar_d.gif")) {
			echo "<img src=\"themes/$ThemeSel/images/$mainbar\" height=\"$m_size[1]\" width=\"$m_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		    }
		    echo "<img src=\"themes/$ThemeSel/images/$rightbar\" height=\"$r_size[1]\" width=\"$r_size[0]\" alt=\"$percent2 %\" title=\"$percent2 %\">";
		}
                printf(" %.2f%% (%s)", $percent, $optionCount);
		echo "</td></tr>";
	    }
	}

    }
    echo "</table><br>";
    echo "<center><font class=\"content\">";
    echo "<b>"._TOTALVOTES." $sum</b><br>";
    echo "<br><br>";
    $booth = $pollID;
    if ($holdtitle[3] > 0) {
	$article = "<br><br>"._GOBACK."</font></center>";
    } else {
	$article = "</font></center>";
    }
    echo "[ <a href=\"modules.php?name=$module_name&amp;pollID=$booth\">"._VOTING."</a> | "
	."<a href=\"modules.php?name=$module_name\">"._OTHERPOLLS."</a> ] $article";
    if (is_admin($admin)) {
	echo "<br><center>[ <a href=\"admin.php?op=create\">"._ADD."</a> | <a href=\"admin.php?op=polledit&pollID=$pollID\">"._EDIT."</a> ]</center>";
    }
    return(1);
}

?>