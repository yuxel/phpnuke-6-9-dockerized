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

if (eregi("block-Old_Articles.php", $_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $locale, $oldnum, $storynum, $storyhome, $cookie, $categories, $cat, $prefix, $multilingual, $currentlang, $db, $new_topic, $user_news;

if ($multilingual == 1) {
    if ($categories == 1) {
    	$querylang = "where catid='$cat' AND (alanguage='$currentlang' OR alanguage='')";
    } else {
    	$querylang = "where (alanguage='$currentlang' OR alanguage='')";
	if ($new_topic != 0) {
	    $querylang .= " AND topic='$new_topic'";
	}
    }
} else {
    if ($categories == 1) {
   	$querylang = "where catid='$cat'";
    } else {
	$querylang = "";
	if ($new_topic != 0) {
	    $querylang = "WHERE topic='$new_topic'";
	}
    }
}
if (isset($cookie[3]) AND $user_news == 1) {
    $storynum = $cookie[3];
} else {
    $storynum = $storyhome;
}
$boxstuff = "<table border=\"0\" width=\"100%\">";
$boxTitle = _PASTARTICLES;
$sql = "SELECT sid, title, time, comments FROM ".$prefix."_stories $querylang ORDER BY time DESC LIMIT $storynum, $oldnum";
$result = $db->sql_query($sql);
$vari = 0;

$r_options = "";
if (isset($cookie[4])) { $r_options .= "&amp;mode=$cookie[4]"; }
if (isset($cookie[5])) { $r_options .= "&amp;order=$cookie[5]"; }
if (isset($cookie[6])) { $r_options .= "&amp;thold=$cookie[6]"; }

while ($row = $db->sql_fetchrow($result)) {
    $sid = $row['sid'];
    $title = $row[title];
    $time = $row[time];
    $comments = $row[comments];
    $see = 1;
    setlocale(LC_TIME, $locale);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime2);
    $datetime2 = strftime(""._DATESTRING2."", mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
    $datetime2 = ucfirst($datetime2);
    if ($articlecomm == 1) {
	$comments = "($comments)";
    } else {
	$comments = "";
    }
    if($time2==$datetime2) {
        $boxstuff .= "<tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
    } else {
        if($a=="") {
    	    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
	    $time2 = $datetime2;
	    $a = 1;
	} else {
	    $boxstuff .= "<tr><td colspan=\"2\"><b>$datetime2</b></td></tr><tr><td valign=\"top\"><strong><big>&middot;</big></strong></td><td> <a href=\"modules.php?name=News&amp;file=article&amp;sid=$sid$r_options\">$title</a> $comments</td></tr>\n";
	    $time2 = $datetime2;
	}
    }
    $vari++;
    if ($vari==$oldnum) {
	if (isset($cookie[3])) {
	    $storynum = $cookie[3];
	} else {
	    $storynum = $storyhome;
	}
	$min = $oldnum + $storynum;
	$dummy = 1;
    }
}

if ($dummy == 1 AND is_active(Stories_Archive)) {
    $boxstuff .= "</table><br><a href=\"modules.php?name=Stories_Archive\"><b>"._OLDERARTICLES."</b></a>\n";
} else {
    $boxstuff .= "</table>";
}

if ($see == 1) {
    $content = $boxstuff;
}

?>