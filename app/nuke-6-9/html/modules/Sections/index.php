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

function listsections() {
    global $sitename, $prefix, $dbi, $module_name;
    include ('header.php');
    $result = sql_query("select secid, secname, image from ".$prefix."_sections order by secname", $dbi);
    OpenTable();
    echo "
    <center>"._SECWELCOME." $sitename.<br><br>
    "._YOUCANFIND."</center><br><br>
    <table border=\"0\" align=\"center\">";
    $count = 0;
    while (list($secid, $secname, $image) = sql_fetch_row($result, $dbi)) {
        if ($count==2) {
        echo "<tr>";
        $count = 0;
        }
        echo "<td><a href=\"modules.php?name=$module_name&op=listarticles&secid=$secid\"><img src=\"images/sections/$image\" border=\"0\" Alt=\"$secname\"></a>";
        $count++;
        if ($count==2) {
        echo "</tr>";
        }
        echo "</td>";
    }
    echo "</table></center>";
    CloseTable();
    include ('footer.php');
}

function listarticles($secid) {
    global $prefix, $multilingual, $currentlang, $dbi, $module_name;
    if ($multilingual == 1) {
    $querylang = "AND slanguage='$currentlang'";
    } else {
    $querylang = "";
    }
    include ('header.php');
    $result = sql_query("select secname from ".$prefix."_sections where secid=$secid", $dbi);
    list($secname) = sql_fetch_row($result, $dbi);
    $result = sql_query("select artid, secid, title, content, counter from ".$prefix."_seccont where secid=$secid $querylang", $dbi);
    OpenTable();
    $result2 = sql_query("select image from ".$prefix."_sections where secid=$secid", $dbi);
    list($image) = sql_fetch_row($result2, $dbi);
    echo "<center><img src=\"images/sections/$image\" border=\"0\" alt=\"\"><br><br>
    <font class=\"option\">
    "._THISISSEC." <b>$secname</b>.<br>"._FOLLOWINGART."</font></center><br><br>
    <table border=\"0\" align=\"center\">";
    while (list($artid, $secid, $title, $content, $counter) = sql_fetch_row($result, $dbi)) {
        echo "
        <tr><td align=\"left\" nowrap><font class=\"content\">
        <li><a href=\"modules.php?name=$module_name&op=viewarticle&artid=$artid\">$title</a> ($counter "._READS.")
        <a href=\"modules.php?name=$module_name&op=printpage&artid=$artid\"><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>
        </td></tr>
        ";
    }
    echo "</table>
    <br><br><br><center>
    [ <a href=\"modules.php?name=$module_name\">"._SECRETURN."</a> ]</center>";
    CloseTable();
    include ('footer.php');
}

function viewarticle($artid, $page) {
    global $prefix, $dbi, $module_name;
    include("header.php");
    if (($page == 1) OR ($page == "")) {
	sql_query("update ".$prefix."_seccont set counter=counter+1 where artid='$artid'", $dbi);
    }
    $result = sql_query("select artid, secid, title, content, counter from ".$prefix."_seccont where artid=$artid", $dbi);
    list($artid, $secid, $title, $content, $counter) = sql_fetch_row($result, $dbi);

    $result2 = sql_query("select secid, secname from ".$prefix."_sections where secid=$secid", $dbi);
    list($secid, $secname) = sql_fetch_row($result2, $dbi);
    $words = sizeof(explode(" ", $content));
    echo "<center>";
    OpenTable();
    $contentpages = explode( "<!--pagebreak-->", $content );
    $pageno = count($contentpages);
    if ( $page=="" || $page < 1 )
	$page = 1;
    if ( $page > $pageno )
	$page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo "<font class=\"option\"><b>$title</b></font><br><br><font class=\"content\">";
    if ($pageno > 1) {
	echo ""._PAGE.": $page/$pageno<br>";
    }
    echo "($words "._TOTALWORDS.")<br>"
	."($counter "._READS.") &nbsp;&nbsp;"
	."<a href=\"modules.php?name=$module_name&op=printpage&amp;artid=$artid\"><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>"
	."</font><br><br><br><br>";
    echo "$contentpages[$arrayelement]";
    if($page >= $pageno) {
	  $next_page = "";
    } else {
	$next_pagenumber = $page + 1;
	if ($page != 1) {
	    $next_page .= "<img src=\"images/blackpixel.gif\" width=\"10\" height=\"2\" border=\"0\" alt=\"\"> &nbsp;&nbsp; ";
	}
	$next_page .= "<a href=\"modules.php?name=$module_name&op=viewarticle&amp;artid=$artid&amp;page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"modules.php?name=$module_name&op=viewarticle&artid=$artid&page=$next_pagenumber\"><img src=\"images/download/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
    }

    if($page <= 1) {
	$previous_page = "";
    } else {
	$previous_pagenumber = $page - 1;
	$previous_page = "<a href=\"modules.php?name=$module_name&op=viewarticle&amp;artid=$artid&amp;page=$previous_pagenumber\"><img src=\"images/download/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a> <a href=\"modules.php?name=$module_name&op=viewarticle&artid=$artid&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
    }
    echo "</td></tr>"
	."<tr><td align=\"center\">"
	."$previous_page &nbsp;&nbsp; $next_page<br><br>"
	."[ <a href=\"modules.php?name=$module_name&op=listarticles&amp;secid=$secid\">"._BACKTO." $secname</a> | "
	."<a href=\"modules.php?name=$module_name\">"._SECINDEX."</a> ]";
    CloseTable();
    echo "</center>";
    include ('footer.php');
}

function PrintSecPage($artid) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix, $dbi, $module_name;
    $result = sql_query("select title, content from ".$prefix."_seccont where artid=$artid", $dbi);
    list($title, $content) = sql_fetch_row($result, $dbi);
    echo "
    <html>
    <head><title>$sitename</title></head>
    <body bgcolor=\"#FFFFFF\" text=\"#000000\">
    <table border=\"0\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#FFFFFF\"><tr><td>
    <center>
    <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br><br>
    <font class=\"content\">
    <b>$title</b></font><br>
    </center><font class=\"content\">
    $content<br><br>";
    echo "</td></tr></table></td></tr></table>
    <br><br><center>
    <font class=\"content\">
    "._COMESFROM." $sitename<br>
    <a href=\"$nukeurl\">$nukeurl</a><br><br>
    "._THEURL."<br>
    <a href=\"$nukeurl/modules.php?name=$module_name&op=viewarticle&amp;artid=$artid\">$nukeurl/modules.php?name=$module_name&op=viewarticle&amp;artid=$artid</a></font></center>
    </td></tr></table>
    </body>
    </html>
    ";
}

switch($op) {

    case "viewarticle":
    viewarticle($artid, $page);
    break;

    case "listarticles":
    listarticles($secid);
    break;

    case "printpage":
    PrintSecPage($artid);
    break;

    default:
    listsections();
    break;

}

?>