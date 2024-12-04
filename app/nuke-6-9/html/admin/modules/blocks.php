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

if (!eregi("admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }
$result = sql_query("select radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminsuper) = sql_fetch_row($result, $dbi);
if ($radminsuper==1) {

/*********************************************************/
/* Blocks Functions                                      */
/*********************************************************/

function BlocksAdmin() {
	global $bgcolor2, $bgcolor4, $prefix, $dbi, $currentlang, $multilingual;
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._BLOCKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><table border=\"1\" width=\"100%\"><tr>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._POSITION."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\" colspan=\"2\"><b>"._WEIGHT."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._TYPE."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._STATUS."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._VIEW."</b></td>";
	if ($multilingual == 1) {
	    echo "<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._LANGUAGE."</b></td>";
	}
	echo "<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></tr>";
	$result = sql_query("select bid, bkey, title, url, bposition, weight, active, blanguage, blockfile, view from ".$prefix."_blocks order by bposition, weight", $dbi);
	while(list($bid, $bkey, $title, $url, $bposition, $weight, $active, $blanguage, $blockfile, $view) = sql_fetch_row($result, $dbi)) {
	    $weight1 = $weight - 1;
	    $weight3 = $weight + 1;
	    $res = sql_query("select bid from ".$prefix."_blocks where weight='$weight1' AND bposition='$bposition'", $dbi);
	    list ($bid1) = sql_fetch_array($res, $dbi);
	    $con1 = "$bid1";
	    $res2 = sql_query("select bid from ".$prefix."_blocks where weight='$weight3' AND bposition='$bposition'", $dbi);
	    list ($bid2) = sql_fetch_array($res2, $dbi);
	    $con2 = "$bid2";
	    echo "<tr>"
		."<td align=\"center\">$title</td>";
	    if ($bposition == "l") {
		$bposition = "<img src=\"images/center_r.gif\" border=\"0\" alt=\""._LEFTBLOCK."\" title=\""._LEFTBLOCK."\" hspace=\"5\"> "._LEFT."";
	    } elseif ($bposition == "r") {
		$bposition = ""._RIGHT." <img src=\"images/center_l.gif\" border=\"0\" alt=\""._RIGHTBLOCK."\" title=\""._RIGHTBLOCK."\" hspace=\"5\">";
	    } elseif ($bposition == "c") {
		$bposition = "<img src=\"images/center_l.gif\" border=\"0\" alt=\""._CENTERBLOCK."\" title=\""._CENTERBLOCK."\">&nbsp;"._CENTERUP."&nbsp;<img src=\"images/center_r.gif\" border=\"0\" alt=\""._CENTERBLOCK."\" title=\""._CENTERBLOCK."\">";
	    } elseif ($bposition == "d") {
		$bposition = "<img src=\"images/center_l.gif\" border=\"0\" alt=\""._CENTERBLOCK."\" title=\""._CENTERBLOCK."\">&nbsp;"._CENTERDOWN."&nbsp;<img src=\"images/center_r.gif\" border=\"0\" alt=\""._CENTERBLOCK."\" title=\""._CENTERBLOCK."\">";
	    }
	    echo "<td align=\"center\">$bposition</td>"
		."<td align=\"center\">"
		."&nbsp;$weight&nbsp;</td><td align=\"center\">";
	    if ($con1) {
		echo"<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight1&amp;bidrep=$con1\"><img src=\"images/up.gif\" alt=\""._BLOCKUP."\" title=\""._BLOCKUP."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    if ($con2) {
		echo "<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight&amp;bidrep=$con2\"><img src=\"images/down.gif\" alt=\""._BLOCKDOWN."\" title=\""._BLOCKDOWN."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    echo"</td>";
	    if ($bkey == "") {
		if ($url == "") {
		    $type = "HTML";
		} elseif ($url != "") {
		    $type = "RSS/RDF";
		}
		if ($blockfile != "") {
		    $type = _BLOCKFILE2;
		}
	    } elseif ($bkey != "") {
		$type = _BLOCKSYSTEM;
	    }
	    echo "<td align=\"center\">$type</td>";
	    $block_act = $active;
	    if ($active == 1) {
		$active = _ACTIVE;
		$change = _DEACTIVATE;
	    } elseif ($active == 0) {
		$active = "<i>"._INACTIVE."</i>";
		$change = _ACTIVATE;
	    }
	    echo "<td align=\"center\">$active</td>";
	    if ($view == 0) {
		$who_view = _MVALL;
	    } elseif ($view == 1) {
		$who_view = _MVUSERS;
	    } elseif ($view == 2) {
		$who_view = _MVADMIN;
	    } elseif ($view == 3) {
		$who_view = _MVANON;
	    }
	    echo "<td align=\"center\">$who_view</td>";
	    if ($multilingual == 1) {
		if ($blanguage == "") {
		    $blanguage = _ALL;
		} else {
		    $blanguage = ucfirst($blanguage);
		}
		echo "<td align=\"center\">$blanguage</td>";
	    }
	    echo "<td align=\"center\"><font class=\"content\">[ <a href=\"admin.php?op=BlocksEdit&amp;bid=$bid\">"._EDIT."</a> | <a href=\"admin.php?op=ChangeStatus&amp;bid=$bid\">$change</a> | ";
	    if ($bkey == "") {
		echo "<a href=\"admin.php?op=BlocksDelete&amp;bid=$bid\">"._DELETE."</a> | ";
	    } elseif ($bkey != "") {
		echo ""._DELETE." | ";
	    }
	    if ($block_act == 0) {
		echo "<a href=\"admin.php?op=block_show&bid=$bid\">"._SHOW."</a> ]</font></td></tr>";
	    } else {
		echo ""._SHOW." ]</font></td></tr>";
	    }
	}
	echo "</table>"
	    ."<br><br>"
	    ."<center>[ <a href=\"admin.php?op=fixweight\">"._FIXBLOCKS."</a> ]</center><br>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"option\"><b>"._ADDNEWBLOCK."</b></font></center><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<table border=\"0\" width=\"100%\">"
	    ."<tr><td>"._TITLE.":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._RSSFILE.":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\">&nbsp;&nbsp;"
	    ."<select name=\"headline\">"
	    ."<option name=\"headline\" value=\"0\" selected>"._CUSTOM."</option>";
	$res = sql_query("select hid, sitename from ".$prefix."_headlines", $dbi);
	while(list($hid, $htitle) = sql_fetch_row($res, $dbi)) {
	    echo "<option name=\"headline\" value=\"$hid\">$htitle</option>";
	}
	echo "</select>&nbsp;[ <a href=\"admin.php?op=HeadlinesAdmin\">Setup</a> ]<br><font class=\"tiny\">";
	echo ""._SETUPHEADLINES."</font></td></tr>"
	    ."<tr><td>"._FILENAME.":</td><td>"
	    ."<select name=\"blockfile\">"
	    ."<option name=\"blockfile\" value=\"\" selected>"._NONE."</option>";
	$blocksdir = dir("blocks");
	while($func=$blocksdir->read()) {
	    if(substr($func, 0, 6) == "block-") {
    		$blockslist .= "$func ";
	    }
	}
	closedir($blocksdir->handle);
	$blockslist = explode(" ", $blockslist);
	sort($blockslist);
	for ($i=0; $i < sizeof($blockslist); $i++) {
	    if($blockslist[$i]!="") {
		$bl = ereg_replace("block-","",$blockslist[$i]);
		$bl = ereg_replace(".php","",$bl);
		$bl = ereg_replace("_"," ",$bl);
		$result = sql_query("select * from ".$prefix."_blocks where blockfile='$blockslist[$i]'", $dbi);
		if (sql_num_rows($result, $dbi) == 0) {
		    echo "<option value=\"$blockslist[$i]\">$bl</option>\n";
		}
	    }
	}
	echo "</select>&nbsp;&nbsp;<font class=\"tiny\">"._FILEINCLUDE."</font></td></tr>"
	    ."<tr><td>"._CONTENT.":</td><td><textarea name=\"content\" cols=\"50\" rows=\"10\"></textarea><br><font class=\"tiny\">"._IFRSSWARNING."</font></td></tr>"
	    ."<tr><td>"._POSITION.":</td><td><select name=\"bposition\"><option name=\"bposition\" value=\"l\">"._LEFT."</option>"
	    ."<option name=\"bposition\" value=\"c\">"._CENTERUP."</option>"
	    ."<option name=\"bposition\" value=\"d\">"._CENTERDOWN."</option>"
	    ."<option name=\"bposition\" value=\"r\">"._RIGHT."</option></select></td></tr>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td>"
	    ."<select name=\"blanguage\">";
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
	echo "<option value=\"\">"._ALL."</option></select></td></tr>";
    } else {
	echo "<input type=\"hidden\" name=\"blanguage\" value=\"\">";
    }
	echo "<tr><td>"._ACTIVATE2."</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	    ."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td></tr>"
	    ."<tr><td>"._REFRESHTIME.":</td><td><select name=\"refresh\">"
	    ."<option name=\"refresh\" value=\"1800\">1/2 "._HOUR."</option>"
	    ."<option name=\"refresh\" value=\"3600\" selected>1 "._HOUR."</option>"
	    ."<option name=\"refresh\" value=\"18000\">5 "._HOURS."</option>"
	    ."<option name=\"refresh\" value=\"36000\">10 "._HOURS."</option>"
	    ."<option name=\"refresh\" value=\"86400\">24 "._HOURS."</option></select>&nbsp;<font class=\"tiny\">"._ONLYHEADLINES."</font></td></tr>"
	    ."<tr><td>"._VIEWPRIV."</td><td><select name=\"view\">"
	    ."<option value=\"0\" >"._MVALL."</option>"
	    ."<option value=\"1\" >"._MVUSERS."</option>"
	    ."<option value=\"2\" >"._MVADMIN."</option>"
	    ."<option value=\"3\" >"._MVANON."</option>"
	    ."</select>"
	    ."</td></tr></table><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"BlocksAdd\">"
	    ."<input type=\"submit\" value=\""._CREATEBLOCK."\"></form>";
	CloseTable();
	include("footer.php");
}

function block_show($bid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    title(""._BLOCKSADMIN."");
    OpenTable2();
    $result = sql_query("select bid, bkey, title, content, url, bposition, blockfile from ".$prefix."_blocks where bid='$bid'", $dbi);
    list($bid, $bkey, $title, $content, $url, $bposition, $blockfile) = sql_fetch_row($result, $dbi);
    if ($bkey == main) {
        mainblock();
    } elseif ($bkey == admin) {
        adminblock();
    } elseif ($bkey == modules) {
        modules_block();
    } elseif ($bkey == category) {
        category();
    } elseif ($bkey == userbox) {
        userblock();
    } elseif ($bkey == "") {
        if ($url == "") {
    	    if ($blockfile == "") {
		if ($bposition == "c") {
		    themecenterbox($title, $content);
		} else {
	    	    themesidebox($title, $content);
		}
	    } else {
		if ($bposition == "c") {
		    blockfileinc($title, $blockfile, 1);
		} else {
	    	    blockfileinc($title, $blockfile);
		}
	    }
	} else {
	    headlines($bid);
	}
    }
    CloseTable2();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._BLOCKSADMIN.": "._FUNCTIONS."</b></font><br><br>"
	."[ <a href=\"admin.php?op=ChangeStatus&bid=$bid\">"._ACTIVATE."</a> | <a href=\"admin.php?op=BlocksEdit&bid=$bid\">"._EDIT."</a> | ";
    if ($bkey == "") {
	echo "<a href=\"admin.php?op=BlocksDelete&bid=$bid\">"._DELETE."</a> | ";
    } else {
	echo ""._DELETE." | ";
    }
    echo "<a href=\"admin.php?op=BlocksAdmin\">"._BLOCKSADMIN."</a> ]</center>";
    CloseTable();
    include("footer.php");
}

function fixweight() {
    global $prefix, $dbi;
    $leftpos = "l";
    $rightpos = "r";
    $centerpos = "c";
    $result = sql_query("select bid from ".$prefix."_blocks where bposition='$leftpos' order by weight ASC", $dbi);
    $weight = 0;
    while(list($bid) = sql_fetch_row($result, $dbi)) {
	$weight++;
	sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'", $dbi);
    }
    $result = sql_query("select bid from ".$prefix."_blocks where bposition='$rightpos' order by weight ASC", $dbi);
    $weight = 0;
    while(list($bid) = sql_fetch_row($result, $dbi)) {
	$weight++;
	sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'", $dbi);
    }
    $result = sql_query("select bid from ".$prefix."_blocks where bposition='$centerpos' order by weight ASC", $dbi);
    $weight = 0;
    while(list($bid) = sql_fetch_row($result, $dbi)) {
	$weight++;
	sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$bid'", $dbi);
    }
    Header("Location: admin.php?op=BlocksAdmin");
}

function BlockOrder ($weightrep,$weight,$bidrep,$bidori) {
    global $prefix, $dbi;
    $result = sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$bidrep'", $dbi);
    $result2 = sql_query("update ".$prefix."_blocks set weight='$weightrep' where bid='$bidori'", $dbi);
    Header("Location: admin.php?op=BlocksAdmin");
}

function rssfail() {
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._BLOCKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._RSSFAIL."</b><br><br>"
	.""._RSSTRYAGAIN."<br><br>"
	.""._GOBACK."</center>";
    CloseTable();
    include("footer.php");
    die;
}

function BlocksAdd($title, $content, $url, $bposition, $active, $refresh, $headline, $blanguage, $blockfile, $view) {
    global $prefix, $dbi;
    if ($headline != 0) {
	$result = sql_query("select sitename, headlinesurl from ".$prefix."_headlines where hid='$headline'", $dbi);
	list ($title, $url) = sql_fetch_row($result, $dbi);
    }
    $result = sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition='$bposition' ORDER BY weight DESC", $dbi);
    list ($weight) = sql_fetch_array($result, $dbi);
    $weight++;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    $bkey = "";
    $btime = "";
    if ($blockfile != "") {
	$url = "";
	if ($title == "") {
	    $title = ereg_replace("block-","",$blockfile);
	    $title = ereg_replace(".php","",$title);
	    $title = ereg_replace("_"," ",$title);
	}
    }
    if ($url != "") {
	$btime = time();
	if (!ereg("http://",$url)) {
	    $url = "http://$url";
	}
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
	    rssfail();
	    exit;
	}
	if ($fp) {
	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
	    $string = "";
	    while(!feof($fp)) {
	    	$pagetext = fgets($fp,228);
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
		if ($items[$i] == "" AND $cont != 1) {
		    $content = "";
		} else {
		    if (strcmp($link,$title2) AND $items[$i] != "") {
			$cont = 1;
			$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		    }
		}
	    }
	}
    }
    $content = FixQuotes($content);
    if (($content == "") AND ($blockfile == "")) {
	rssfail();
    } else {
	sql_query("insert into ".$prefix."_blocks values (NULL, '$bkey', '$title', '$content', '$url', '$bposition', '$weight', '$active', '$refresh', '$btime', '$blanguage', '$blockfile', '$view')", $dbi);
	Header("Location: admin.php?op=BlocksAdmin");
    }
}

function BlocksEdit($bid) {
    global $bgcolor2, $bgcolor4, $prefix, $dbi, $multilingual;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._EDITBLOCK."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select bkey, title, content, url, bposition, weight, active, refresh, blanguage, blockfile, view from ".$prefix."_blocks where bid='$bid'", $dbi);
    list($bkey, $title, $content, $url, $bposition, $weight, $active, $refresh, $blanguage, $blockfile, $view) = sql_fetch_row($result, $dbi);
    if ($url != "") {
	$type = _RSSCONTENT;
    } elseif ($blockfile != "") {
	$type = _BLOCKFILE;
    }
    OpenTable();
    echo "<center><font class=\"option\"><b>"._BLOCK.": $title $type</b></font></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\" width=\"100%\">"
        ."<tr><td>"._TITLE.":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" value=\"$title\"></td></tr>";
    if ($blockfile != "") {
	echo "<tr><td>"._FILENAME.":</td><td>"
	    ."<select name=\"blockfile\">";
	$blocksdir = dir("blocks");
	while($func=$blocksdir->read()) {
	    if(substr($func, 0, 6) == "block-") {
    		$blockslist .= "$func ";
	    }
	}
	closedir($blocksdir->handle);
	$blockslist = explode(" ", $blockslist);
	sort($blockslist);
	for ($i=0; $i < sizeof($blockslist); $i++) {
	    if($blockslist[$i]!="") {
		$bl = ereg_replace("block-","",$blockslist[$i]);
		$bl = ereg_replace(".php","",$bl);
		$bl = ereg_replace("_"," ",$bl);
		echo "<option value=\"$blockslist[$i]\" ";
		if ($blockfile == $blockslist[$i]) { echo "selected"; }
		echo ">$bl</option>\n";
	    }
	}
	echo "</select>&nbsp;&nbsp;<font class=\"tiny\">"._FILEINCLUDE."</font></td></tr>";
    } else {
	if ($url != "") {
	    echo "<tr><td>"._RSSFILE.":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\" value=\"$url\">&nbsp;&nbsp;<font class=\"tiny\">"._ONLYHEADLINES."</font></td></tr>";
	} else {
	    echo "<tr><td>"._CONTENT.":</td><td><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea></td></tr>";
	}
    }
    $oldposition = $bposition;
    echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
    if ($bposition == "l") {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($bposition == "c") {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($bposition == "r") {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
    } elseif ($bposition == "d") {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
    }
    echo "<tr><td>"._POSITION.":</td><td><select name=\"bposition\">"
	."<option name=\"bposition\" value=\"l\" $sel1>"._LEFT."</option>"
	."<option name=\"bposition\" value=\"c\" $sel2>"._CENTERUP."</option>"
	."<option name=\"bposition\" value=\"d\" $sel4>"._CENTERDOWN."</option>"
	."<option name=\"bposition\" value=\"r\" $sel3>"._RIGHT."</option></select></td></tr>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td>"
	    ."<select name=\"blanguage\">";
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
		if($languageslist[$i]==$blanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($blanguage != "") {
	    $sel3 = "";
	} else {
	    $sel3 = "selected";
	}
	echo "<option value=\"\" $sel3>"._ALL."</option></select></td></tr>";
    } else {
	echo "<input type=\"hidden\" name=\"blanguage\" value=\"\">";
    }
    if ($active == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } elseif ($active == 0) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td>"._ACTIVATE2."</td><td><input type=\"radio\" name=\"active\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>"._NO."</td></tr>";
    if ($url != "") {
    if ($refresh == 1800) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 3600) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 18000) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 36000) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
	$sel5 = "";
    } elseif ($refresh == 86400) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "selected";
    }
    echo "<tr><td>"._REFRESHTIME.":</td><td><select name=\"refresh\"><option name=\"refresh\" value=\"1800\" $sel1>1/2 "._HOUR."</option>"
        ."<option name=\"refresh\" value=\"3600\" $sel2>1 "._HOUR."</option>"
        ."<option name=\"refresh\" value=\"18000\" $sel3>5 "._HOURS."</option>"
        ."<option name=\"refresh\" value=\"36000\" $sel4>10 "._HOURS."</option>"
        ."<option name=\"refresh\" value=\"86400\" $sel5>24 "._HOURS."</option></select>&nbsp;<font class=\"tiny\">"._ONLYHEADLINES."</font>";
    }
    if ($view == 0) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 1) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";    
    } elseif ($view == 3) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";    
    }
    echo "</td></tr><tr><td>"._VIEWPRIV."</td><td><select name=\"view\">"
        ."<option value=\"0\" $sel1>"._MVALL."</option>"
        ."<option value=\"1\" $sel2>"._MVUSERS."</option>"
        ."<option value=\"2\" $sel3>"._MVADMIN."</option>"
        ."<option value=\"3\" $sel4>"._MVANON."</option>"
        ."</select>"
	."</td></tr></table><br><br>"
	."<input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
        ."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">"
        ."<input type=\"submit\" value=\""._SAVEBLOCK."\"></form>";
    CloseTable();
    include("footer.php");
}

function SortWeight($bposition) {
    global $prefix, $dbi;
    $numbers = 1;
    $number_two = 1;
    $result = sql_query("SELECT bid,weight FROM ".$prefix."_blocks WHERE bposition='$bposition' ORDER BY weight", $dbi);
    while (list ($bid,$weight) = sql_fetch_row($result, $dbi)) {
	$result2 = sql_query("update ".$prefix."_blocks set weight='$numbers' where bid='$bid'", $dbi);
	$numbers++;
    }
    if ($bposition == l) {
	$position_two = "r";
    } else {
	$position_two = "l";
    }
    $result_two = sql_query("SELECT bid,weight FROM ".$prefix."_blocks WHERE bposition='$position_two' ORDER BY weight", $dbi);
    while (list ($bid2,$weight) = sql_fetch_row($result_two, $dbi)) {
	$result_two2 = sql_query("update ".$prefix."_blocks set weight='$number_two' where bid='$bid2'", $dbi);
	$number_two++;
    }
    return $numbers;
}

function BlocksEditSave($bid, $bkey, $title, $content, $url, $oldposition, $bposition, $active, $refresh, $weight, $blanguage, $blockfile, $view) { 
    global $prefix, $dbi;
    if ($url != "") {
	$bkey = "";
	$btime = time();
	if (!ereg("http://",$url)) {
	    $url = "http://$url";
	}
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
    	    rssfail();
    	    exit;
	}
	if ($fp) {
    	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTML/1.0\r\n");
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
		if ($items[$i] == "" AND $cont != 1) {
		    $content = "";
		} else {
		    if (strcmp($link,$title2) AND $items[$i] != "") {
			$cont = 1;
			$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		    }
		}
	    }
	}
	if ($oldposition != $bposition) {
	    $result = sql_query("select bid from ".$prefix."_blocks where weight>='$weight' AND bposition='$bposition'", $dbi);
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = sql_fetch_row($result, $dbi)) {
		$weight++;
		sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'", $dbi);
	    }
	    $result = sql_query("select bid from ".$prefix."_blocks where weight>'$oweight' AND bposition='$oldposition'", $dbi);
	    while (list($obid) = sql_fetch_row($result, $dbi)) {
		sql_query("update ".$prefix."_blocks set weight='$oweight' where bid='$obid'", $dbi);
		$oweight++;
	    }
	    $result = sql_query("select weight from ".$prefix."_blocks where bposition='$bposition' order by weight DESC limit 0,1", $dbi);
	    list($lastw) = sql_fetch_row($result, $dbi);
	    if ($lastw <= $fweight) {
		$lastw++;
		sql_query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$lastw', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	    } else {
		sql_query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$fweight', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	    }
	} else {
	    $result = sql_query("update ".$prefix."_blocks set bkey='$bkey', title='$title', content='$content', url='$url', bposition='$bposition', weight='$weight', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	}
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
	$title = stripslashes(FixQuotes($title));
	$content = stripslashes(FixQuotes($content));
	if ($oldposition != $bposition) {
	    $result = sql_query("select bid from ".$prefix."_blocks where weight>='$weight' AND bposition='$bposition'", $dbi);
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = sql_fetch_row($result, $dbi)) {
		$weight++;
		sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'", $dbi);
	    }
	    $result = sql_query("select bid from ".$prefix."_blocks where weight>'$oweight' AND bposition='$oldposition'", $dbi);
	    while (list($obid) = sql_fetch_row($result, $dbi)) {
		sql_query("update ".$prefix."_blocks set weight='$oweight' where bid='$obid'", $dbi);
		$oweight++;
	    }
	    $result = sql_query("select weight from ".$prefix."_blocks where bposition='$bposition' order by weight DESC limit 0,1", $dbi);
	    list($lastw) = sql_fetch_row($result, $dbi);
	    if ($lastw <= $fweight) {
		$lastw++;
		sql_query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$lastw', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	    } else {
		sql_query("update ".$prefix."_blocks set title='$title', content='$content', bposition='$bposition', weight='$fweight', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	    }
	} else {
	    $result = sql_query("update ".$prefix."_blocks set bkey='$bkey', title='$title', content='$content', url='$url', bposition='$bposition', weight='$weight', active='$active', refresh='$refresh', blanguage='$blanguage', view='$view' where bid='$bid'", $dbi);
	}
	Header("Location: admin.php?op=BlocksAdmin");
    }
}

function ChangeStatus($bid, $ok=0) {
    global $prefix, $dbi;
    $result = sql_query("select active from ".$prefix."_blocks where bid='$bid'", $dbi);
    list($active) = sql_fetch_row($result, $dbi);
    if (($ok) OR ($active == 1)) {
	if ($active == 0) {
	    $active = 1;
	} elseif ($active == 1) {
	    $active = 0;
	}
	$result = sql_query("update ".$prefix."_blocks set active='$active' where bid='$bid'", $dbi);
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
	$result = sql_query("select title, content from ".$prefix."_blocks where bid='$bid'", $dbi);
	list($title, $content) = sql_fetch_row($result, $dbi);
	include("header.php");
	GraphicAdmin();
	echo "<br>";
	OpenTable();
	echo "<center><font class=\"option\"><b>"._BLOCKACTIVATION."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	if ($content != "") {
	    echo "<center>"._BLOCKPREVIEW." <i>$title</i><br><br>";
	    themesidebox($title, $content);
	} else {
	    echo "<center><i>$title</i><br><br>";
	}
	echo "<br>"._WANT2ACTIVATE."<br><br>"
	    ."[ <a href=\"admin.php?op=BlocksAdmin\">"._NO."</a> | <a href=\"admin.php?op=ChangeStatus&amp;bid=$bid&amp;ok=1\">"._YES."</a> ]"
	    ."</center>";
	CloseTable();
	include("footer.php");
    }
}

function BlocksDelete($bid, $ok=0) {
    global $prefix, $dbi;
    if ($ok) {
	$result = sql_query("select bposition, weight from ".$prefix."_blocks where bid='$bid'", $dbi);
	list($bposition, $weight) = sql_fetch_row($result, $dbi);
	$result = sql_query("select bid from ".$prefix."_blocks where weight>'$weight' AND bposition='$bposition'", $dbi);
	while (list($nbid) = sql_fetch_row($result, $dbi)) {
	    sql_query("update ".$prefix."_blocks set weight='$weight' where bid='$nbid'", $dbi);
	    $weight++;
	}
	sql_query("delete from ".$prefix."_blocks where bid='$bid'", $dbi);
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
        $result = sql_query("select title from ".$prefix."_blocks where bid='$bid'", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._BLOCKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center>"._ARESUREDELBLOCK." <i>$title</i>?";
	echo "<br><br>[ <a href=\"admin.php?op=BlocksAdmin\">"._NO."</a> | <a href=\"admin.php?op=BlocksDelete&amp;bid=$bid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
	include("footer.php");
    }
}

function HeadlinesAdmin() {
    global $bgcolor1, $bgcolor2, $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._HEADLINESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
	."<table border=\"1\" width=\"100%\" align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._SITENAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._URL."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td><tr>";
    $result = sql_query("select hid, sitename, headlinesurl from ".$prefix."_headlines order by hid", $dbi);
    while(list($hid, $sitename, $headlinesurl) = sql_fetch_row($result, $dbi)) {
	echo "<td bgcolor=\"$bgcolor1\" align=\"center\">$sitename</td>"
	    ."<td bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$headlinesurl\" target=\"new\">$headlinesurl</a></td>"
	    ."<td bgcolor=\"$bgcolor1\" align=\"center\">[ <a href=\"admin.php?op=HeadlinesEdit&amp;hid=$hid\">"._EDIT."</a> | <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=0\">"._DELETE."</a> ]</td><tr>";
    }
    echo "</form></td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<font class=\"option\"><b>"._ADDHEADLINE."</b></font><br><br>"
	."<font class=\"content\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._SITENAME.":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\"></td></tr><tr><td>"
	.""._RSSFILE.":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\"></td></tr><tr><td>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"HeadlinesAdd\">"
	."<input type=\"submit\" value=\""._ADD."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function HeadlinesEdit($hid) {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._HEADLINESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select sitename, headlinesurl from ".$prefix."_headlines where hid='$hid'", $dbi);
    list($xsitename, $headlinesurl) = sql_fetch_row($result, $dbi);
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITHEADLINE."</b></font></center>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"hid\" value=\"$hid\">
	<table border=\"0\" width=\"100%\"><tr><td>
	"._SITENAME.":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\" value=\"$xsitename\"></td></tr><tr><td>
	"._RSSFILE.":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" value=\"$headlinesurl\"></td></tr><tr><td>
	</select></td></tr></table>
	<input type=\"hidden\" name=\"op\" value=\"HeadlinesSave\">
	<input type=\"submit\" value=\""._SAVECHANGES."\">
	</form>";
    CloseTable();
    include("footer.php");
}

function HeadlinesSave($hid, $xsitename, $headlinesurl) {
    global $prefix, $dbi;
    $xsitename = ereg_replace(" ", "", $xsitename);
    sql_query("update ".$prefix."_headlines set sitename='$xsitename', headlinesurl='$headlinesurl' where hid='$hid'", $dbi);
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesAdd($xsitename, $headlinesurl) {
    global $prefix, $dbi;
    $xsitename = ereg_replace(" ", "", $xsitename);
    sql_query("insert into ".$prefix."_headlines values (NULL, '$xsitename', '$headlinesurl')", $dbi);
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesDel($hid, $ok=0) {
    global $prefix, $dbi;
    if($ok==1) {
	sql_query("delete from ".$prefix."_headlines where hid='$hid'", $dbi);
	Header("Location: admin.php?op=HeadlinesAdmin");
    } else {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><br>";
	echo "<font class=\"option\">";
	echo "<b>"._SURE2DELHEADLINE."</b></font><br><br>";
    }
    echo "[ <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=HeadlinesAdmin\">"._NO."</a> ]<br><br>";
    CloseTable();
    include("footer.php");
}

switch($op) {

    case "BlocksAdmin":
    BlocksAdmin();
    break;

    case "BlocksAdd":
    BlocksAdd($title, $content, $url, $bposition, $active, $refresh, $headline, $blanguage, $blockfile, $view);
    break;

    case "BlocksEdit":
    BlocksEdit($bid);
    break;

    case "BlocksEditSave":
    BlocksEditSave($bid, $bkey, $title, $content, $url, $oldposition, $bposition, $active, $refresh, $weight, $blanguage, $blockfile, $view);
    break;

    case "ChangeStatus":
    ChangeStatus($bid, $ok, $de);
    break;

    case "BlocksDelete":
    BlocksDelete($bid, $ok);
    break;

    case "BlockOrder":
    BlockOrder ($weightrep,$weight,$bidrep,$bidori);
    break;

    case "HeadlinesDel":
    HeadlinesDel($hid, $ok);
    break;

    case "HeadlinesAdd":
    HeadlinesAdd($xsitename, $headlinesurl);
    break;

    case "HeadlinesSave":
    HeadlinesSave($hid, $xsitename, $headlinesurl);
    break;

    case "HeadlinesAdmin":
    HeadlinesAdmin();
    break;

    case "HeadlinesEdit":
    HeadlinesEdit($hid);
    break;

    case "fixweight":
    fixweight();
    break;

    case "block_show":
    block_show($bid);
    break;

}

} else {
    echo "Access Denied";
}

?>