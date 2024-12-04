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

$pagetitle = "- $module_name";

function showpage($pid, $page=0) {
    global $prefix, $db, $sitename, $admin, $module_name;
    include("header.php");
    OpenTable();
    $sql = "SELECT * FROM ".$prefix."_pages WHERE pid='$pid'";
    $result = $db->sql_query($sql);
    $mypage = $db->sql_fetchrow($result);
    if (($mypage[active] == 0) AND (!is_admin($admin))) {
	echo "Sorry... This page doesn't exist.";
    } else {
	$db->sql_query("UPDATE ".$prefix."_pages SET counter=counter+1 WHERE pid='$pid'");
	$date = explode(" ", $mypage[date]);
	echo "<font class=\"title\">$mypage[title]</font><br>"
	    ."<font class=\"content\">$mypage[subtitle]<br><br><br><br>";
	$contentpages = explode( "<!--pagebreak-->", $mypage[text] );
	$pageno = count($contentpages);
	if ( $page=="" || $page < 1 )
	    $page = 1;
	if ( $page > $pageno )
	    $page = $pageno;
	$arrayelement = (int)$page;
	$arrayelement --;
	if ($pageno > 1) {
	    echo ""._PAGE.": $page/$pageno<br>";
	}
	if ($page == 1) {
	    echo "<p align=\"justify\">".nl2br($mypage[page_header])."</p><br>";
	}
	echo "<p align=\"justify\">$contentpages[$arrayelement]</p>";
	if($page >= $pageno) {
	    $next_page = "";
	} else {
	    $next_pagenumber = $page + 1;
	    if ($page != 1) {
		$next_page .= "- ";
	    }
	    $next_page .= "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$next_pagenumber\"><img src=\"images/download/right.gif\" border=\"0\" alt=\""._NEXT."\" title=\""._NEXT."\"></a>";
	}
	if ($page == $pageno) {
	    echo "<br><p align=\"justify\">".nl2br($mypage[page_footer])."</p><br><br>";
	}
	if($page <= 1) {
	    $previous_page = "";
	} else {
	    $previous_pagenumber = $page - 1;
	    $previous_page = "<a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\"><img src=\"images/download/left.gif\" border=\"0\" alt=\""._PREVIOUS."\" title=\""._PREVIOUS."\"></a> <a href=\"modules.php?name=$module_name&pa=showpage&pid=$pid&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
	}
	echo "<br><br><br><center>$previous_page $next_page</center><br><br>";
	if ($page == $pageno) {
	    echo "<p align=\"right\">".nl2br($mypage[signature])."</p>"
		."<p align=\"right\">"._COPYRIGHT." $sitename "._COPYRIGHT2."</p>"
		."<p align=\"right\"><font class=\"tiny\">"._PUBLISHEDON.": $date[0] ($mypage[counter] "._READS.")</font></p>"
		."<center>"._GOBACK."</center>";
	}
    }
    CloseTable();
    include("footer.php");
}

function list_pages() {
    global $prefix, $db, $sitename, $admin, $multilingual, $module_name;
    include("header.php");
    title("$sitename: "._PAGESLIST."");
    OpenTable();
    echo "<center><font class=\"content\">"._LISTOFCONTENT." $sitename:</center><br><br>";
    $sql = "SELECT * FROM ".$prefix."_pages_categories";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    $numrows2 = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_pages WHERE cid!='0' AND active='1'"));
    if ($numrows > 0 AND $numrows2 > 0) {
	echo "<center>"._CONTENTCATEGORIES."</center><br><br>"
    	    ."<table border=\"1\" align=\"center\" width=\"95%\">";
	while ($row = $db->sql_fetchrow($result)) {
	    $cid = $row[cid];
	    $title = $row[title];
	    $description = $row[description];
	    $numrows3 = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_pages WHERE cid='$cid'"));
	    if ($numrows3 > 0) {
		echo "<tr><td valign=\"top\">&nbsp;<a href=\"modules.php?name=$module_name&amp;pa=list_pages_categories&amp;cid=$cid\">$title</a>&nbsp;</td><td align=\"left\">$description</td></tr>";
	    }
	}
	echo "</td></tr></table><br><br>"
	    ."<center>"._NONCLASSCONT."</center><br><br>";
    }
    $sql = "SELECT pid, title, subtitle, clanguage FROM ".$prefix."_pages WHERE active='1' AND cid='0' ORDER BY date";
    $result = $db->sql_query($sql);
    echo "<blockquote>";
    while ($row = $db->sql_fetchrow($result)) {
	$pid = $row[pid];
	$title = $row[title];
	$subtitle = $row[subtitle];
	$clanguage = $row[clanguage];
	if ($multilingual == 1) {
	    $the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
	} else {
	    $the_lang = "";
	}
        if ($subtitle != "") {
	    $subtitle = " ($subtitle)";
	} else {
    	    $subtitle = "";
	}
	if (is_admin($admin)) {
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=1\">"._DEACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
	} else {
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle<br>";
	}
    }
    echo "</blockquote>";
    if (is_admin($admin)) {
	$sql = "SELECT pid, cid, title, subtitle, clanguage FROM ".$prefix."_pages WHERE active='0' ORDER BY date";
	$result = $db->sql_query($sql);
	echo "<br><br><center><b>"._YOURADMINLIST."</b></center><br><br>";
	echo "<blockquote>";
	while ($row = $db->sql_fetchrow($result)) {
	    $pid = $row[pid];
	    $cid = $row[cid];
	    $title = $row[title];
	    $subtitle = $row[subtitle];
	    $clanguage = $row[clanguage];
	    if ($multilingual == 1) {
		$the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
	    } else {
		$the_lang = "";
	    }
    	    if ($subtitle != "") {
	        $subtitle = " ($subtitle) ";
	    } else {
    	        $subtitle = " ";
	    }
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=0\">"._ACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
	}
	echo "</blockquote>";
    }
    CloseTable();
    include("footer.php");
}

function list_pages_categories($cid) {
    global $prefix, $db, $sitename, $admin, $multilingual, $module_name;
    include("header.php");
    title("$sitename: "._PAGESLIST."");
    OpenTable();
    echo "<center><font class=\"content\">"._LISTOFCONTENT." $sitename:</center><br><br>";
    $sql = "SELECT pid, title, subtitle, clanguage FROM ".$prefix."_pages WHERE active='1' AND cid='$cid' ORDER BY date";
    $result = $db->sql_query($sql);
    echo "<blockquote>";
    while ($row = $db->sql_fetchrow($result)) {
	$pid = $row[pid];
	$title = $row[title];
	$subtitle = $row[subtitle];
	$clanguage = $row[clanguage];
	if ($multilingual == 1) {
	    $the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
	} else {
	    $the_lang = "";
	}
        if ($subtitle != "") {
	    $subtitle = " ($subtitle)";
	} else {
    	    $subtitle = "";
	}
	if (is_admin($admin)) {
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=1\">"._DEACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
	} else {
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle<br>";
	}
    }
    echo "</blockquote>";
    if (is_admin($admin)) {
	$sql = "SELECT pid, title, subtitle, clanguage FROM ".$prefix."_pages WHERE active='0' AND cid='$cid' ORDER BY date";
	$result = $db->sql_query($sql);
	echo "<br><br><center><b>"._YOURADMINLIST."</b></center><br><br>";
	echo "<blockquote>";
	while ($row = $db->sql_fetchrow($result)) {
	    $pid = $row[pid];
	    $title = $row[title];
	    $subtitle = $row[subtitle];
	    $clanguage = $row[clanguage];
	    if ($multilingual == 1) {
		$the_lang = "<img src=\"images/language/flag-$clanguage.png\" hspace=\"3\" border=\"0\" height=\"10\" width=\"20\">";
	    } else {
		$the_lang = "";
	    }
    	    if ($subtitle != "") {
	        $subtitle = " ($subtitle) ";
	    } else {
    	        $subtitle = " ";
	    }
	    echo "<strong><big>&middot;</big></strong> $the_lang <a href=\"modules.php?name=$module_name&amp;pa=showpage&amp;pid=$pid\">$title</a> $subtitle [ <a href=\"admin.php?op=content_edit&pid=$pid\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$pid&active=0\">"._ACTIVATE."</a> | <a href=\"admin.php?op=content_delete&pid=$pid\">"._DELETE."</a> ]<br>";
	}
	echo "</blockquote>";
    }
    echo "<center>"._GOBACK."</center>";
    CloseTable();
    include("footer.php");
}

switch($pa) {

    case "showpage":
    showpage($pid, $page);
    break;
    
    case "list_pages_categories":
    list_pages_categories($cid);
    break;
    
    default:
    list_pages();
    break;

}

?>