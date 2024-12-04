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
/* REVIEWS Block Functions                               */
/*********************************************************/

function modules() {
    global $prefix, $dbi, $multilingual, $bgcolor2;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._MODULESADMIN."</b></font></center>";
    CloseTable();
    $handle=opendir('modules');
    while ($file = readdir($handle)) {
	if ( (!ereg("[.]",$file)) ) {
		$modlist .= "$file ";
	}
    }
    closedir($handle);
    $modlist = explode(" ", $modlist);
    sort($modlist);
    for ($i=0; $i < sizeof($modlist); $i++) {
	if($modlist[$i] != "") {
	    $result = sql_query("select mid from ".$prefix."_modules where title='$modlist[$i]'", $dbi);
	    list ($mid) = sql_fetch_row($result, $dbi);
	    if ($mid == "") {
		sql_query("insert into ".$prefix."_modules values (NULL, '$modlist[$i]', '$modlist[$i]', '0', '0', '1')", $dbi);
	    }
	}
    }
    $result = sql_query("select title from ".$prefix."_modules", $dbi);
    while (list($title) = sql_Fetch_row($result, $dbi)) {
	$a = 0;
	$handle=opendir('modules');
	while ($file = readdir($handle)) {
	    if ($file == $title) {
		$a = 1;
	    }
	}
	closedir($handle);
	if ($a == 0) {
	    sql_query("delete from ".$prefix."_modules where title='$title'", $dbi);
	}
    }
    echo "<br>";
    OpenTable();
    echo "<br><center><font class=\"option\">"._MODULESADDONS."</font><br><br>"
	."<font class=\"content\">"._MODULESACTIVATION."</font><br><br>"
	.""._MODULEHOMENOTE."<br><br>"._NOTINMENU."<br><br>"
	."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"1\" align=\"center\" width=\"90%\"><tr><td align=\"center\" bgcolor=\"$bgcolor2\">"
	."<b>"._TITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._CUSTOMTITLE."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._STATUS."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._VIEW."</b></td><td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></td></tr>";
    $main_m = sql_query("select main_module from ".$prefix."_main", $dbi);
    list($main_module) = sql_fetch_row($main_m, $dbi);
    $result = sql_query("select mid, title, custom_title, active, view, inmenu from ".$prefix."_modules order by title ASC", $dbi);
    while(list($mid, $title, $custom_title, $active, $view, $inmenu) = sql_fetch_row($result, $dbi)) {
	if ($custom_title == "") {
	    $custom_title = ereg_replace("_"," ",$title);
	    sql_query("update ".$prefix."_modules set custom_title='$custom_title' where mid='$mid'", $dbi);
	}
	if ($active == 1) {
	    $active = _ACTIVE;
	    $change = _DEACTIVATE;
	    $act = 0;
	} else {
	    $active = "<i>"._INACTIVE."</i>";
	    $change = _ACTIVATE;
	    $act = 1;
	}
	if ($custom_title == "") {
	    $custom_title = ereg_replace("_", " ", $title);
	}
	if ($view == 0) {
	    $who_view = _MVALL;
	} elseif ($view == 1) {
	    $who_view = _MVUSERS;
	} elseif ($view == 2) {
	    $who_view = _MVADMIN;
	}
	if ($title != $main_module AND $inmenu == 0) {
	    $title = "[ <big><strong>&middot;</strong></big> ] $title";
	}
	if ($title == $main_module) {
	    $title = "<b>$title</b>";
	    $custom_title = "<b>$custom_title</b>";
	    $active = "<b>$active ("._INHOME.")</b>";
	    $who_view = "<b>$who_view</b>";
	    $puthome = "<i>"._PUTINHOME."</i>";
	    $change_status = "<i>$change</i>";
	    $background = "bgcolor=\"$bgcolor2\"";
	} else {
	    $puthome = "<a href=\"admin.php?op=home_module&mid=$mid\">"._PUTINHOME."</a>";
	    $change_status = "<a href=\"admin.php?op=module_status&mid=$mid&active=$act\">$change</a>";
	    $background = "";
	}
	echo "<tr><td $background>&nbsp;$title</td><td align=\"center\" $background>$custom_title</td><td align=\"center\" $background>$active</td><td align=\"center\" $background>$who_view</td><td align=\"center\" $background>[ <a href=\"admin.php?op=module_edit&mid=$mid\">"._EDIT."</a> | $change_status | $puthome ]</td></tr>";
    }
    echo "</table>";
    CloseTable();
    include ("footer.php");
}

function home_module($mid, $ok=0) {
    global $prefix, $dbi;
    if ($ok == 0) {
	include ("header.php");
	GraphicAdmin();
	title(""._HOMECONFIG."");
	OpenTable();
	$result = sql_query("select title from ".$prefix."_modules where mid='$mid'", $dbi);
	list($new_m) = sql_fetch_row($result, $dbi);
	$result = sql_query("select main_module from ".$prefix."_main", $dbi);
	list($old_m) = sql_fetch_row($result, $dbi);
	echo "<center><b>"._DEFHOMEMODULE."</b><br><br>"
	    .""._SURETOCHANGEMOD." <b>$old_m</b> "._TO." <b>$new_m</b>?<br><br>"
	    ."[ <a href=\"admin.php?op=modules\">"._NO."</a> | <a href=\"admin.php?op=home_module&mid=$mid&ok=1\">"._YES."</a> ]</center>";
	CloseTable();
	include("footer.php");
    } else {
	$result = sql_query("select title from ".$prefix."_modules where mid='$mid'", $dbi);
	list($title) = sql_fetch_row($result, $dbi);
	$active = 1;
	$view = 0;
	$result = sql_query("update ".$prefix."_main set main_module='$title'", $dbi);
	$result = sql_query("update ".$prefix."_modules set active='$active', view='$view' where mid='$mid'", $dbi);
	Header("Location: admin.php?op=modules");
    }
}

function module_status($mid, $active) {
    global $prefix, $dbi;
    sql_query("update ".$prefix."_modules set active='$active' where mid='$mid'", $dbi);
    Header("Location: admin.php?op=modules");
}

function module_edit($mid) {
    global $prefix, $dbi;
    $main_m = sql_query("select main_module from ".$prefix."_main", $dbi);
    list($main_module) = sql_fetch_row($main_m, $dbi);
    $result = sql_query("select title, custom_title, view, inmenu from ".$prefix."_modules where mid='$mid'", $dbi);
    list($title, $custom_title, $view, $inmenu) = sql_fetch_row($result, $dbi);
    include ("header.php");
    GraphicAdmin();
    title(""._MODULEEDIT."");
    OpenTable();
    if ($view == 0) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
    } elseif ($view == 1) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";    
    }
    if ($title == $main_module) {
	$a = " - "._INHOME."";
    } else {
	$a = "";
    }
    if ($inmenu == 1) {
	$insel1 = "checked";
	$insel2 = "";
    } elseif ($inmenu == 0) {
	$insel1 = "";
	$insel2 = "checked";
    }
    echo "<center><b>"._CHANGEMODNAME."</b><br>($title$a)</center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\"><tr><td>"
	.""._CUSTOMMODNAME."</td><td>"
	."<input type=\"text\" name=\"custom_title\" value=\"$custom_title\" size=\"50\"></td></tr>";
    if ($title == $main_module) {
	echo "<input type=\"hidden\" name=\"view\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"inmenu\" value=\"$inmenu\">"
	    ."</table><br><br>";
    } else {
	echo "<tr><td>"._VIEWPRIV."</td><td><select name=\"view\">"
	    ."<option value=\"0\" $sel1>"._MVALL."</option>"
	    ."<option value=\"1\" $sel2>"._MVUSERS."</option>"
	    ."<option value=\"2\" $sel3>"._MVADMIN."</option>"
	    ."</select></tr></td>"
	    ."<tr><td>"._SHOWINMENU."</td><td>"
	    ."<input type=\"radio\" name=\"inmenu\" value=\"1\" $insel1> "._YES." &nbsp;&nbsp; <input type=\"radio\" name=\"inmenu\" value=\"0\" $insel2> "._NO.""
	    ."</td></tr></table><br><br>";
    }
    if ($title != $main_module) {
	
    }
    echo "<input type=\"hidden\" name=\"mid\" value=\"$mid\">"
	."<input type=\"hidden\" name=\"op\" value=\"module_edit_save\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>"
	."<br><br><center>"._GOBACK."</center>";
    CloseTable();
    include("footer.php");
}

function module_edit_save($mid, $custom_title, $view, $inmenu) {
    global $prefix, $dbi;
    $result = sql_query("update ".$prefix."_modules set custom_title='$custom_title', view='$view', inmenu='$inmenu' where mid='$mid'", $dbi);
    Header("Location: admin.php?op=modules");
}

switch ($op){

    case "modules":
    modules();
    break;

    case "module_status":
    module_status($mid, $active);
    break;

    case "module_edit":
    module_edit($mid);
    break;
    
    case "module_edit_save":
    module_edit_save($mid, $custom_title, $view, $inmenu);
    break;

    case "home_module":
    home_module($mid, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>