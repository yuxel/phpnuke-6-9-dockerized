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
$result = sql_query("select radmincontent, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmincontent, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmincontent==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function content() {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    OpenTable();
    echo "<table border=\"0\" width=\"100%\"><tr>"
        ."<td bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CURRENTSTATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CATEGORY."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $result = sql_query("select * from ".$prefix."_pages order by pid", $dbi);
    while($mypages = sql_fetch_array($result, $dbi)) {
        if ($mypages[cid] == "0" OR $mypages[cid] == "") {
            $cat_title = _NONE;
        } else {
            $res = sql_query("select title from ".$prefix."_pages_categories where cid='$mypages[cid]'", $dbi);
            list($cat_title) = sql_fetch_row($res, $dbi);
        }
        if ($mypages[active] == 1) {
            $status = _ACTIVE;
            $status_chng = _DEACTIVATE;
            $active = 1;
        } else {
            $status = "<i>"._INACTIVE."</i>";
            $status_chng = _ACTIVATE;
            $active = 0;
        }
        echo "<tr><td><a href=\"modules.php?name=Content&pa=showpage&pid=$mypages[pid]\">$mypages[title]</a></td><td align=\"center\">$status</td><td align=\"center\">$cat_title</td><td align=\"center\">[ <a href=\"admin.php?op=content_edit&pid=$mypages[pid]\">"._EDIT."</a> | <a href=\"admin.php?op=content_change_status&pid=$mypages[pid]&active=$active\">$status_chng</a> | <a href=\"admin.php?op=content_delete&pid=$mypages[pid]\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br>";

    OpenTable();
    echo "<center><b>"._ADDCATEGORY."</b></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<b>"._TITLE.":</b><br><input type=\"text\" name=\"cat_title\" size=\"50\"><br><br>"
        ."<b>"._DESCRIPTION.":</b><br><textarea name=\"description\" rows=\"10\" cols=\"50\"></textarea><br><br>"
        ."<input type=\"hidden\" name=\"op\" value=\"add_category\">"
        ."<input type=\"submit\" value=\""._ADD."\">"
        ."</form>";
    CloseTable();

    $rescat = sql_query("select cid, title from ".$prefix."_pages_categories order by title", $dbi);
    if (sql_num_rows($rescat, $dbi) > 0) {
        echo "<br>";
        OpenTable();
        echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
            ."<form action=\"admin.php\" method=\"post\">"
            ."<b>"._CATEGORY.":</b> "
            ."<select name=\"cid\">";
        while (list($cid, $cat_title) = sql_fetch_row($rescat, $dbi)) {
            echo "<option value=\"$cid\">$cat_title</option>";
        }
        echo "</select>&nbsp;&nbsp;"
            ."<input type=\"hidden\" name=\"op\" value=\"edit_category\">"
            ."<input type=\"submit\" value=\""._EDIT."\">"
            ."</form>";
        CloseTable();
    }

    echo "<br>";
    OpenTable();
    $res = sql_query("select cid, title from ".$prefix."_pages_categories order by title", $dbi);
    echo "<center><b>"._ADDANEWPAGE."</b></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<b>"._TITLE.":</b><br>"
        ."<input type=\"text\" name=\"title\" size=\"50\"><br><br>";
    if (sql_num_rows($res, $dbi) > 0) {
        echo "<b>"._CATEGORY.":</b>&nbsp;&nbsp;"
            ."<select name=\"cid\">"
            ."<option value=\"0\" selected>"._NONE."</option>";
        while(list($cid, $cat_title) = sql_fetch_row($res, $dbi)) {
            echo "<option value=\"$cid\">$cat_title</option>";
        }
        echo "</select><br><br>";
    } else {
        echo "<input type=\"hidden\" name=\"cid\" value=\"0\">";
    }
    echo "<b>"._CSUBTITLE.":</b><br>"
        ."<input type=\"text\" name=\"subtitle\" size=\"50\"><br><br>"
        ."<b>"._HEADERTEXT.":</b><br>"
        ."<textarea name=\"page_header\" cols=\"60\" rows=\"10\"></textarea><br><br>"
        ."<b>"._PAGETEXT.":</b><br>"
        ."<font class=\"tiny\">"._PAGEBREAK."</font><br>"
        ."<textarea name=\"text\" cols=\"60\" rows=\"40\"></textarea><br><br>"
        ."<b>"._FOOTERTEXT.":</b><br>"
        ."<textarea name=\"page_footer\" cols=\"60\" rows=\"10\"></textarea><br><br>"
        ."<b>"._SIGNATURE.":</b><br>"
        ."<textarea name=\"signature\" cols=\"60\" rows=\"5\"></textarea><br><br>";
    if ($multilingual == 1) {
        echo "<br><b>"._LANGUAGE.": </b>"
            ."<select name=\"clanguage\">";
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
                if($languageslist[$i]==$language) echo "selected";
                echo ">".ucfirst($languageslist[$i])."</option>\n";
            }
        }
        echo "</select><br><br>";
    } else {
        echo "<input type=\"hidden\" name=\"clanguage\" value=\"$language\">";
    }
    echo "<b>"._ACTIVATEPAGE."</b><br>"
        ."<input type=\"radio\" name=\"active\" value=\"1\" checked>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\">&nbsp;"._NO."<br><br>"
        ."<input type=\"hidden\" name=\"op\" value=\"content_save\">"
        ."<input type=\"submit\" value=\""._SEND."\">"
        ."</form>";
    CloseTable();
    include("footer.php");
}

function add_category($cat_title, $description) {
    global $prefix, $dbi;
    sql_query("insert into ".$prefix."_pages_categories values (NULL, '$cat_title', '$description')", $dbi);
    Header("Location: admin.php?op=content");
}

function edit_category($cid) {
    global $prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    OpenTable();
    $result = sql_query("select title, description from ".$prefix."_pages_categories where cid='$cid'", $dbi);
    list($title, $description) = sql_fetch_row($result, $dbi);
    echo "<center><b>"._EDITCATEGORY."</b></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<b>"._TITLE."</b><br>"
        ."<input type=\"text\" name=\"cat_title\" value='$title' size=\"50\"><br><br>"
        ."<b>"._DESCRIPTION."</b>:<br>"
        ."<textarea cols=\"50\" rows=\"10\" name=\"description\">$description</textarea><br><br>"
        ."<input type=\"hidden\" name=\"cid\" value=\"$cid\">"
        ."<input type=\"hidden\" name=\"op\" value=\"save_category\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">&nbsp;&nbsp;"
        ."[ <a href=\"admin.php?op=del_content_cat&amp;cid=$cid\">"._DELETE."</a> ]"
        ."</form>";
    CloseTable();
    include("footer.php");
}

function save_category($cid, $cat_title, $description) {
    global $prefix, $dbi;
    sql_query("update ".$prefix."_pages_categories set title='$cat_title', description='$description' where cid='$cid'", $dbi);
    Header("Location: admin.php?op=content");
}

function del_content_cat($cid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from ".$prefix."_pages_categories where cid='$cid'", $dbi);
        $result = sql_query("select pid from ".$prefix."_pages where cid='$cid'", $dbi);
        while (list($pid) = sql_fetch_row($result, $dbi)) {
            sql_query("update ".$prefix."_pages set cid='0' where pid='$pid'", $dbi);
        }
        Header("Location: admin.php?op=content");
    } else {
        include("header.php");
        GraphicAdmin();
        title(""._CONTENTMANAGER."");
        $result = sql_query("select title from ".$prefix."_pages_categories where cid='$cid'", $dbi);
        list($title) = sql_fetch_row($result, $dbi);
        OpenTable();
        echo "<center><b>"._DELCATEGORY.": $title</b><br><br>"
            .""._DELCONTENTCAT."<br><br>"
            ."[ <a href=\"admin.php?op=content\">"._NO."</a> | <a href=\"admin.php?op=del_content_cat&amp;cid=$cid&amp;ok=1\">"._YES."</a> ]</center>";
        CloseTable();
        include("footer.php");
    }
}

function content_edit($pid) {
    global $prefix, $dbi, $language, $multilingual, $bgcolor2;
    include("header.php");
    GraphicAdmin();
    title(""._CONTENTMANAGER."");
    $result = sql_query("select * from ".$prefix."_pages WHERE pid='$pid'", $dbi);
    $mypages = sql_fetch_array($result, $dbi);
        if ($mypages[active] == 1) {
            $sel1 = "checked";
            $sel2 = "";
        } else {
            $sel1 = "";
            $sel2 = "checked";
        }
    OpenTable();
    echo "<center><b>"._EDITPAGECONTENT."</b></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<b>"._TITLE.":</b><br>"
        ."<input type=\"text\" name=\"title\" size=\"50\" value='$mypages[title]'><br><br>";
    $res = sql_query("select cid, title from ".$prefix."_pages_categories", $dbi);
    if (sql_num_rows($res, $dbi) > 0) {
        echo "<b>"._CATEGORY.":</b>&nbsp;&nbsp;"
            ."<select name=\"cid\">";
        if ($mypages[cid] == 0) {
            $sel = "selected";
        } else {
            $sel = "";
        }
        echo "<option value=\"0\" $sel>"._NONE."</option>";
        while(list($cid, $cat_title) = sql_fetch_row($res, $dbi)) {
            if ($mypages[cid] == $cid) {
                $sel = "selected";
            } else {
                $sel = "";
            }
            echo "<option value=\"$cid\" $sel>$cat_title</option>";
        }
        echo "</select><br><br>";
    } else {
        echo "<input type=\"hidden\" name=\"cid\" value=\"0\">";
    }
    echo "<b>"._CSUBTITLE.":</b><br>"
        ."<input type=\"text\" name=\"subtitle\" size=\"50\" value='$mypages[subtitle]'><br><br>"
        ."<b>"._HEADERTEXT.":</b><br>"
        ."<textarea name=\"page_header\" cols=\"60\" rows=\"10\">$mypages[page_header]</textarea><br><br>"
        ."<b>"._PAGETEXT.":</b><br>"
        ."<font class=\"tiny\">"._PAGEBREAK."</font><br>"
        ."<textarea name=\"text\" cols=\"60\" rows=\"40\">$mypages[text]</textarea><br><br>"
        ."<b>"._FOOTERTEXT.":</b><br>"
        ."<textarea name=\"page_footer\" cols=\"60\" rows=\"10\">$mypages[page_footer]</textarea><br><br>"
        ."<b>"._SIGNATURE.":</b><br>"
        ."<textarea name=\"signature\" cols=\"60\" rows=\"5\">$mypages[signature]</textarea><br><br>";
    if ($multilingual == 1) {
        echo "<br><b>"._LANGUAGE.": </b>"
            ."<select name=\"clanguage\">";
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
                if($languageslist[$i]==$language) echo "selected";
                echo ">".ucfirst($languageslist[$i])."</option>\n";
            }
        }
        echo "</select><br><br>";
    } else {
        echo "<input type=\"hidden\" name=\"clanguage\" value=\"$mypages[clanguage]\">";
    }
    echo "<b>"._ACTIVATEPAGE."</b><br>"
        ."<input type=\"radio\" name=\"active\" value=\"1\" $sel1>&nbsp;"._YES."&nbsp&nbsp;<input type=\"radio\" name=\"active\" value=\"0\" $sel2>&nbsp;"._NO."<br><br>"
        ."<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
        ."<input type=\"hidden\" name=\"op\" value=\"content_save_edit\">"
        ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
        ."</form>";
    CloseTable();
    include("footer.php");
}

function content_save($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;
    $text = stripslashes(FixQuotes($text));
    $title = stripslashes(FixQuotes($title));
    $subtitle = stripslashes(FixQuotes($subtitle));
    sql_query("insert into ".$prefix."_pages values (NULL, '$cid', '$title', '$subtitle', '$active', '$page_header', '$text', '$page_footer', '$signature', now(), '0', '$clanguage')", $dbi);
    Header("Location: admin.php?op=content");
}

function content_save_edit($pid, $title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid) {
    global $prefix, $dbi;
    $text = stripslashes(FixQuotes($text));
    $title = stripslashes(FixQuotes($title));
    $subtitle = stripslashes(FixQuotes($subtitle));
    sql_query("update ".$prefix."_pages set cid='$cid', title='$title', subtitle='$subtitle', active='$active', page_header='$page_header', text='$text', page_footer='$page_footer', signature='$signature', clanguage='$clanguage' where pid='$pid'", $dbi);
    Header("Location: admin.php?op=content");
}

function content_change_status($pid, $active) {
    global $prefix, $dbi;
    if ($active == 1) {
        $new_active = 0;
    } elseif ($active == 0) {
        $new_active = 1;
    }
    sql_query("update ".$prefix."_pages set active='$new_active' WHERE pid='$pid'", $dbi);
    Header("Location: admin.php?op=content");
}

function content_delete($pid, $ok=0) {
    global $prefix, $dbi;
    if ($ok==1) {
        sql_query("delete from ".$prefix."_pages where pid='$pid'", $dbi);
        Header("Location: admin.php?op=content");
    } else {
        include("header.php");
        GraphicAdmin();
        title(""._CONTENTMANAGER."");
        $result = sql_query("select title from ".$prefix."_pages where pid='$pid'", $dbi);
        list($title) = sql_fetch_row($result, $dbi);
        OpenTable();
        echo "<center><b>"._DELCONTENT.": $title</b><br><br>"
            .""._DELCONTWARNING." $title?<br><br>"
            ."[ <a href=\"admin.php?op=content\">"._NO."</a> | <a href=\"admin.php?op=content_delete&amp;pid=$pid&amp;ok=1\">"._YES."</a> ]</center>";
        CloseTable();
        include("footer.php");
    }
}

switch ($op) {

    case "content":
    content();
    break;

    case "content_edit":
    content_edit($pid);
    break;

    case "content_delete":
    content_delete($pid, $ok);
    break;

    case "content_review":
    content_review($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active);
    break;

    case "content_save":
    content_save($title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid);
    break;

    case "content_save_edit":
    content_save_edit($pid, $title, $subtitle, $page_header, $text, $page_footer, $signature, $clanguage, $active, $cid);
    break;

    case "content_change_status":
    content_change_status($pid, $active);
    break;

    case "add_category":
    add_category($cat_title, $description);
    break;

    case "edit_category":
    edit_category($cid);
    break;

    case "save_category":
    save_category($cid, $cat_title, $description);
    break;

    case "del_content_cat":
    del_content_cat($cid, $ok);
    break;
}

} else {
    echo "Access Denied";
}

?>