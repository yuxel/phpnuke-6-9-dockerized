<?PHP

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

$result = sql_query("select radminephem, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminephem, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminephem==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Ephemerids Functions to have a Historic Ephemerids    */
/*********************************************************/

function Ephemerids() {
    global $admin, $currentlang, $multilingual;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDEPHEM."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">";
    $nday = "1";
    echo ""._DAY.": <select name=\"did\">";
    while ($nday<=31) {
	echo "<option name=\"did\">$nday</option>";
	$nday++;
    }
    echo "</select>";
    $nmonth = "1";
    echo ""._UMONTH.": <select name=\"mid\">";
    while ($nmonth<=12) {
	echo "<option name=\"mid\">$nmonth</option>";
	$nmonth++;
    }
    echo "</select>"._YEAR.": <input type=\"text\" name=\"yid\" maxlength=\"4\" size=\"5\"><br><br>";
    if ($multilingual == 1) {
	echo "<b>"._LANGUAGE.": </b>"
	    ."<select name=\"elanguage\">";
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
	echo "</select><br><br>";
    } else {
	echo "<input type=\"hidden\" name=\"elanguage\" value=\"$language\">";
    }
    echo "<b>"._EPHEMDESC.":</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\"></textarea><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridsadd\">"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    echo "<br>";
    OpenTable();
	echo "<center><font class=\"option\"><b>"._EPHEMMAINT."</b></font></center><br>"
	."<center><form action=\"admin.php\" method=\"post\">";
    $nday = "1";
    echo ""._DAY.": <select name=\"did\">";
    while ($nday<=31) {
	echo "<option name=\"did\">$nday</option>";
	$nday++;
    }
    echo "</select>";
    $nmonth = "1";
    echo ""._UMONTH.": <select name=\"mid\">";
    while ($nmonth<=12) {
	echo "<option name=\"mid\">$nmonth</option>";
	$nmonth++;
    }
    echo "</select>"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridsmaintenance\">"
	."<input type=\"submit\" value=\""._EDIT."\">"
	."</form></center>";
    CloseTable();
    include ("footer.php");
}

function Ephemeridsadd($did, $mid, $yid, $content, $elanguage) {
    global $prefix, $dbi;
    sql_query("insert into ".$prefix."_ephem values (NULL, '$did', '$mid', '$yid', '$content', '$elanguage')", $dbi);
    Header("Location: admin.php?op=Ephemerids");
}

function Ephemeridsmaintenance($did, $mid) {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EPHEMMAINT."</b></font></center><br>";
    $result=sql_query("select eid, did, mid, yid, content, elanguage from ".$prefix."_ephem where did='$did' AND mid='$mid'", $dbi);
    while(list($eid, $did, $mid, $yid, $content, $elanguage) = sql_fetch_row($result, $dbi)) {
    echo "<font class=\"content\"><b>$yid</b> - ($elanguage) - [ <a href=\"admin.php?op=Ephemeridsedit&amp;eid=$eid&amp;did=$did&amp;mid=$mid\">"._EDIT."</a> | <a href=\"admin.php?op=Ephemeridsdel&amp;eid=$eid&amp;did=$did&amp;mid=$mid\">"._DELETE."</a> ]<br>"
	."<font class=\"tiny\">$content<br><br><br>";
    }
    CloseTable();
    include ('footer.php');
}

function Ephemeridsdel($eid, $did, $mid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_ephem where eid='$eid'", $dbi);
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

function Ephemeridsedit($eid, $did, $mid) {
    global $prefix, $dbi, $multilingual;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result=sql_query("select yid, content, elanguage from ".$prefix."_ephem where eid='$eid'", $dbi);
    list($yid, $content, $elanguage) = sql_fetch_row($result, $dbi);
    OpenTable();
    echo "<center><font class=title><b>"._EPHEMEDIT."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._YEAR.":</b> <input type=\"text\" name=\"yid\" value=\"$yid\" maxlength=\"4\" size=\"5\"><br><br>";
    if ($multilingual == 1) {
	echo "<b>"._LANGUAGE.": </b>"
	    ."<select name=\"elanguage\">";
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
		if($languageslist[$i]==$elanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	echo "</select><br><br>";
    } else {
	echo "<input type=\"hidden\" name=\"elanguage\" value=\"$language\">";
    }
    echo "<b>"._EPHEMDESC."</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\">$content</textarea><br><br>"
	."<input type=\"hidden\" name=\"did\" value=\"$did\">"
	."<input type=\"hidden\" name=\"mid\" value=\"$mid\">"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridschange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function Ephemeridschange($eid, $did, $mid, $yid, $content, $elanguage) {
    global $prefix, $dbi;
    $content = stripslashes(FixQuotes($content));
    sql_query("update ".$prefix."_ephem set yid='$yid', content='$content', elanguage='$elanguage' where eid='$eid'", $dbi);
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

switch($op) {

    case "Ephemeridsedit":
    Ephemeridsedit($eid, $did, $mid);
    break;

    case "Ephemeridschange":
    Ephemeridschange($eid, $did, $mid, $yid, $content, $elanguage);
    break;

    case "Ephemeridsdel":
    Ephemeridsdel($eid, $did, $mid);
    break;

    case "Ephemeridsmaintenance":
    Ephemeridsmaintenance($did, $mid);
    break;

    case "Ephemeridsadd":
    Ephemeridsadd($did, $mid, $yid, $content, $elanguage);
    break;

    case "Ephemerids":
    Ephemerids();
    break;

}

} else {
    echo "Access Denied";
}

?>