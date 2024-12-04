<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
include("header.php");

if ((isset($query) AND !isset($eid)) AND ($query != "")) {
    $query = check_html($query, nohtml);
    $sql = "SELECT tid, title FROM ".$prefix."_encyclopedia_text WHERE title LIKE '%$query%'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result2);
    $ency_title = $row[title];
    title("$ency_title: "._SEARCHRESULTS."");
    OpenTable();
    echo "<center><b>"._SEARCHRESULTSFOR." <i>$query</i></b></center><br><br><br>"
	."<i><b>"._RESULTSINTERMTITLE."</b></i><br><br>";
    if ($numrows = $db->sql_numrows($result) == 0) {
        echo _NORESULTSTITLE;
    } else {
	while ($row = $db->sql_fetchrow($result)) {
	    $tid = $row[tid];
	    $title = $row[title];
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&op=content&tid=$tid\">$title</a><br>";
	}
    }
    $sql = "SELECT tid, title FROM ".$prefix."_encyclopedia_text WHERE text LIKE '%$query%'";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    echo "<br><br><i><b>"._RESULTSINTERMTEXT."</b></i><br><br>";
    if ($numrows == 0) {
        echo _NORESULTSTEXT;
    } else {
	while ($row = $db->sql_fetchrow($result)) {
	    $tid = $row[tid];
	    $title = $row[title];
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&op=content&tid=$tid&query=$query\">$title</a><br>";
	}
    }
    echo "<br><br>"
	."<center><form action=\"modules.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form><br><br>"
	."[ <a href=\"modules.php?name=$module_name\">"._RETURNTO." $module_name</a> ]<br><br>"
	.""._GOBACK."</center>";
    CloseTable();
} elseif ((isset($query) AND isset($eid)) AND ($query != "")) {
    $query = check_html($query, nohtml);
    $sql = "SELECT tid, title FROM ".$prefix."_encyclopedia_text WHERE eid='$eid' AND title LIKE '%$query%'";
    $result = $db->sql_query($sql);
    $sql2 = "SELECT title FROM ".$prefix."_encyclopedia WHERE eid='$eid'";
    $result2 = $db->sql_query($sql2);
    $row = $db->sql_fetchrow($result2);
    $ency_title = $row[title];
    title("$ency_title: "._SEARCHRESULTS."");
    OpenTable();
    echo "<center><b>"._SEARCHRESULTSFOR." <i>$query</i></b></center><br><br><br>"
	."<i><b>"._RESULTSINTERMTITLE."</b></i><br><br>";
    if ($numrows = $db->sql_numrows($result) == 0) {
        echo _NORESULTSTITLE;
    } else {
	while ($row = $db->sql_fetchrow($result)) {
	    $tid = $row[tid];
	    $title = $row[title];
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&op=content&tid=$tid\">$title</a><br>";
	}
    }
    $sql = "SELECT tid, title FROM ".$prefix."_encyclopedia_text WHERE eid='$eid' AND text LIKE '%$query%'";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    echo "<br><br><i><b>"._RESULTSINTERMTEXT."</b></i><br><br>";
    if ($numrows == 0) {
        echo _NORESULTSTEXT;
    } else {
	while ($row = $db->sql_fetchrow($result)) {
	    $tid = $row[tid];
	    $title = $row[title];
	    echo "<strong><big>&middot</big></strong>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&op=content&tid=$tid&query=$query\">$title</a><br>";
	}
    }
    echo "<br><br>"
	."<center><form action=\"modules.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form><br><br>"
	."[ <a href=\"modules.php?name=$module_name&op=list_content&eid=$eid\">"._RETURNTO." $ency_title</a> ]<br><br>"
	.""._GOBACK."</center>";
    CloseTable();
} else {
    OpenTable();
    echo "<center>"._SEARCHNOTCOMPLETE."<br><br><br>"
	."<center><form action=\"modules.php?name=$module_name&file=search\" method=\"post\">"
	."<input type=\"text\" size=\"20\" name=\"query\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"submit\" value=\""._SEARCH."\">"
	."</form><br><br>"
	.""._GOBACK."</center>";
    CloseTable();
}

include("footer.php");

?>