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
/* Admin/Authors Functions                               */
/*********************************************************/

function displayadmins() {
    global $admin, $prefix, $dbi, $language, $multilingual;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._AUTHORSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITADMINS."</b></font></center><br>"
	."<table border=\"1\" align=\"center\">";
    $result = sql_query("select aid, name, admlanguage from ".$prefix."_authors", $dbi);
    while(list($a_aid, $name, $admlanguage) = sql_fetch_row($result, $dbi)) {
        echo "<tr><td align=\"center\">$a_aid</td>";
	if ($admlanguage == "") {
	    $admlanguage = ""._ALL."";
	}
        echo "<td align=\"center\">$admlanguage</td>";
    	echo "<td><a href=\"admin.php?op=modifyadmin&amp;chng_aid=$a_aid\">"._MODIFYINFO."</a></td>";
	if($name=="God") {
	    echo "<td>"._MAINACCOUNT."</td></tr>";
	} else {
	    echo "<td><a href=\"admin.php?op=deladmin&amp;del_aid=$a_aid\">"._DELAUTHOR."</a></td></tr>";
	}
    }
    echo "</table><br><center><font class=\"tiny\">"._GODNOTDEL."</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDAUTHOR."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\">"
	."<tr><td>"._NAME.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"> <font class=\"tiny\">"._REQUIREDNOCHANGE."</font></td></tr>"
	."<tr><td>"._NICKNAME.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"add_aid\" size=\"30\" maxlength=\"30\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	."<tr><td>"._EMAIL.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	."<tr><td>"._URL.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td></tr>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td colspan=\"3\">"
	    ."<select name=\"add_admlanguage\">";
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
	echo "<option value=\"\">"._ALL."</option></select></td></tr>";
    } else {
	echo "<input type=\"hidden\" name=\"add_admlanguage\" value=\"\">";
    }
    echo "<tr><td>"._PERMISSIONS.":</td>"
	."<td><input type=\"checkbox\" name=\"add_radminarticle\" value=\"1\"> "._ARTICLES."</td>"
	."<td><input type=\"checkbox\" name=\"add_radmintopic\" value=\"1\"> "._TOPICS."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminuser\" value=\"1\"> "._USERS."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"add_radminsurvey\" value=\"1\"> "._SURVEYS."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminsection\" value=\"1\"> "._SECTIONS."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminlink\" value=\"1\"> "._WEBLINKS."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"add_radminephem\" value=\"1\"> "._EPHEMERIDS."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminfaq\" value=\"1\"> "._FAQ."</td>"
	."<td><input type=\"checkbox\" name=\"add_radmindownload\" value=\"1\"> "._DOWNLOAD."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"add_radminreviews\" value=\"1\"> "._REVIEWS."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminnewsletter\" value=\"1\"> "._NEWSLETTER."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminforum\" value=\"1\"> "._BBFORUM."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"add_radmincontent\" value=\"1\"> "._CONTENT."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminency\" value=\"1\"> "._ENCYCLOPEDIA."</td>"
	."<td><input type=\"checkbox\" name=\"add_radminsuper\" value=\"1\"> <b>"._SUPERUSER."</b></td>"
	."</tr>"
	."<tr><td>&nbsp;</td><td colspan=\"3\"><font class=\"tiny\"><i>"._SUPERWARNING."</i></font></td></tr>"
	."<tr><td>"._PASSWORD."</td>"
	."<td colspan=\"3\"><input type=\"password\" name=\"add_pwd\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	."<input type=\"hidden\" name=\"op\" value=\"AddAuthor\">"
	."<tr><td><input type=\"submit\" value=\""._ADDAUTHOR2."\"></td></tr>"
	."</table></form>";
    CloseTable();
    include("footer.php");
}


function modifyadmin($chng_aid) {
    global $prefix, $dbi, $multilingual;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._AUTHORSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._MODIFYINFO."</b></font></center><br><br>";
    $adm_aid = $chng_aid;
    $result = sql_query("select aid, name, url, email, pwd, radminarticle,radmintopic,radminuser,radminsurvey,radminsection,radminlink,radminephem,radminfaq,radmindownload,radminreviews,radminnewsletter,radminforum,radmincontent,radminency,radminsuper,admlanguage from ".$prefix."_authors where aid='$chng_aid'", $dbi);
    list($chng_aid, $chng_name, $chng_url, $chng_email, $chng_pwd, $chng_radminarticle, $chng_radmintopic, $chng_radminuser, $chng_radminsurvey, $chng_radminsection, $chng_radminlink, $chng_radminephem, $chng_radminfaq, $chng_radmindownload, $chng_radminreviews, $chng_radminnewsletter, $chng_radminforum, $chng_radmincontent, $chng_radminency, $chng_radminsuper, $chng_admlanguage) = sql_fetch_row($result, $dbi);
    $aid = $chng_aid;
    echo "<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\">"
	."<tr><td>"._NAME.":</td>"
	."<td colspan=\"3\"><b>$chng_name</b> <input type=\"hidden\" name=\"chng_name\" value=\"$chng_name\"></td></tr>"
	."<tr><td>"._NICKNAME.":</td>"
        ."<td colspan=\"3\"><input type=\"text\" name=\"chng_aid\" value=\"$chng_aid\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	."<tr><td>"._EMAIL.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	."<tr><td>"._URL.":</td>"
	."<td colspan=\"3\"><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>";
    if ($multilingual == 1) {
	echo "<tr><td>"._LANGUAGE.":</td><td colspan=\"3\">"
	    ."<select name=\"chng_admlanguage\">";
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
		if($languageslist[$i]==$chng_admlanguage) echo "selected";
		echo ">".ucfirst($languageslist[$i])."</option>\n";
	    }
	}
	if ($chng_admlanguage == "") {
	    $allsel = "selected";
	} else {
    	    $allsel = "";
	}
	echo "<option value=\"\" $allsel>"._ALL."</option></select></td></tr>";
    } else {
	echo "<input type=\"hidden\" name=\"chng_admlanguage\" value=\"\">";
    }
    echo "<tr><td>"._PERMISSIONS.":</td>";
    if ($chng_radminarticle == 1) {
	$sel1 = "checked";
    } else {
	$sel1 = "";
    }
    if ($chng_radmintopic == 1) {
	$sel2 = "checked";
    } else {
	$sel2 = "";
    }
    if ($chng_radminuser == 1) {
	$sel3 = "checked";
    } else {
	$sel3 = "";
    }
    if ($chng_radminsurvey == 1) {
	$sel4 = "checked";
    } else {
	$sel4 = "";
    }
    if ($chng_radminsection == 1) {
	$sel5 = "checked";
    } else {
	$sel5 = "";
    }
    if ($chng_radminlink == 1) {
	$sel6 = "checked";
    } else {
	$sel6 = "";
    }
    if ($chng_radminephem == 1) {
	$sel7 = "checked";
    } else {
	$sel7 = "";
    }
    if ($chng_radminfaq == 1) {
	$sel8 = "checked";
    } else {
	$sel8 = "";
    }
    if ($chng_radmindownload == 1) {
	$sel9 = "checked";
    } else {
	$sel9 = "";
    }
    if ($chng_radminreviews == 1) {
	$sel10 = "checked";
    } else {
	$sel10 = "";
    }
    if ($chng_radminnewsletter == 1) {
	$sel11 = "checked";
    } else {
	$sel11 = "";
    }
    if ($chng_radminforum == 1) {
	$sel12 = "checked";
    } else {
	$sel12 = "";
    }
    if ($chng_radmincontent == 1) {
	$sel13 = "checked";
    } else {
	$sel13 = "";
    }
    if ($chng_radminency == 1) {
	$sel14 = "checked";
    } else {
	$sel14 = "";
    }
    if ($chng_radminsuper == 1) {
	$sel15 = "checked";
    } else {
	$sel15 = "";
    }
    echo "<td><input type=\"checkbox\" name=\"chng_radminarticle\" value=\"1\" $sel1> "._ARTICLES."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radmintopic\" value=\"1\" $sel2> "._TOPICS."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminuser\" value=\"1\" $sel3> "._USERS."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminsurvey\" value=\"1\" $sel4> "._SURVEYS."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminsection\" value=\"1\" $sel5> "._SECTIONS."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminlink\" value=\"1\" $sel6> "._WEBLINKS."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminephem\" value=\"1\" $sel7> "._EPHEMERIDS."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminfaq\" value=\"1\" $sel8> "._FAQ."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radmindownload\" value=\"1\" $sel9> "._DOWNLOAD."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminreviews\" value=\"1\" $sel10> "._REVIEWS."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminnewsletter\" value=\"1\" $sel11> "._NEWSLETTER."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminforum\" value=\"1\" $sel12> "._BBFORUM."</td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td><input type=\"checkbox\" name=\"chng_radmincontent\" value=\"1\" $sel13> "._CONTENT."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminency\" value=\"1\" $sel14> "._ENCYCLOPEDIA."</td>"
	."<td><input type=\"checkbox\" name=\"chng_radminsuper\" value=\"1\" $sel15> <b>"._SUPERUSER."</b></td>"
	."</tr><tr><td>&nbsp;</td>"
	."<td colspan=\"3\"><font class=\"tiny\"><i>"._SUPERWARNING."</i></font></td></tr>"
	."<tr><td>"._PASSWORD.":</td>"
    	."<td colspan=\"3\"><input type=\"password\" name=\"chng_pwd\" size=\"12\" maxlength=\"12\"></td></tr>"
	."<tr><td>"._RETYPEPASSWD.":</td>"
	."<td colspan=\"3\"><input type=\"password\" name=\"chng_pwd2\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._FORCHANGES."</font></td></tr>"
	."<input type=\"hidden\" name=\"adm_aid\" value=\"$adm_aid\">"
	."<input type=\"hidden\" name=\"op\" value=\"UpdateAuthor\">"
	."<tr><td><input type=\"submit\" value=\""._SAVE."\"> "._GOBACK.""
	."</td></tr></table></form>";
    CloseTable();
    include("footer.php");
}

function updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminarticle, $chng_radmintopic, $chng_radminuser, $chng_radminsurvey, $chng_radminsection, $chng_radminlink, $chng_radminephem, $chng_radminfaq, $chng_radmindownload, $chng_radminreviews, $chng_radminnewsletter, $chng_radminforum, $chng_radmincontent, $chng_radminency, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid) {
    global $prefix, $dbi;
    if (!($chng_aid && $chng_name && $chng_email)) {
	Header("Location: admin.php?op=mod_authors");
    }
    if ($chng_pwd2 != "") {
	if($chng_pwd != $chng_pwd2) {
	    include("header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo ""._PASSWDNOMATCH."<br><br>"
		."<center>"._GOBACK."</center>";
	    CloseTable();
	    include("footer.php");
	    exit;
	}
	$chng_pwd = md5($chng_pwd);
	if ($chng_radminsuper == 1) {
	    $result = sql_query("update ".$prefix."_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminarticle='0', radmintopic='0', radminuser='0', radminsurvey='0', radminsection='0', radminlink='0', radminephem='0', radminfaq='0', radmindownload='0', radminreviews='0', radminnewsletter='0', radminforum='0', radmincontent='0', radminency='0', radminsuper='$chng_radminsuper', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name'", $dbi);
	    Header("Location: admin.php?op=mod_authors");
	} else {
	    $result = sql_query("update ".$prefix."_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminarticle='$chng_radminarticle', radmintopic='$chng_radmintopic', radminuser='$chng_radminuser', radminsurvey='$chng_radminsurvey', radminsection='$chng_radminsection', radminlink='$chng_radminlink', radminephem='$chng_radminephem', radminfaq='$chng_radminfaq', radmindownload='$chng_radmindownload', radminreviews='$chng_radminreviews', radminnewsletter='$chng_radminnewsletter', radminforum='$chng_radminforum', radmincontent='$chng_radmincontent', radminency='$chng_radminency', radminsuper='0', pwd='$chng_pwd', admlanguage='$chng_admlanguage' where name='$chng_name'", $dbi);
	    Header("Location: admin.php?op=mod_authors");
	}
    } else {
	if ($chng_radminsuper == 1) {
	    $result = sql_query("update ".$prefix."_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminarticle='0', radmintopic='0', radminuser='0', radminsurvey='0', radminsection='0', radminlink='0', radminephem='0', radminfaq='0', radmindownload='0', radminreviews='0', radminnewsletter='0', radminforum='$chng_radminforum', radmincontent='$chng_radmincontent', radminency='$chng_radminency', radminsuper='$chng_radminsuper', admlanguage='$chng_admlanguage' where name='$chng_name'", $dbi);
	    Header("Location: admin.php?op=mod_authors");
	} else {
	    $result = sql_query("update ".$prefix."_authors set aid='$chng_aid', email='$chng_email', url='$chng_url', radminarticle='$chng_radminarticle', radmintopic='$chng_radmintopic', radminuser='$chng_radminuser', radminsurvey='$chng_radminsurvey', radminsection='$chng_radminsection', radminlink='$chng_radminlink', radminephem='$chng_radminephem', radminfaq='$chng_radminfaq', radmindownload='$chng_radmindownload', radminreviews='$chng_radminreviews', radminnewsletter='$chng_radminnewsletter', radminforum='$chng_radminforum', radmincontent='$chng_radmincontent', radminency='$chng_radminency', radminsuper='0', admlanguage='$chng_admlanguage' where name='$chng_name'", $dbi);
	    Header("Location: admin.php?op=mod_authors");
	}
    }
    if ($adm_aid != $chng_aid) {
	$result = sql_query("select sid, aid, informant from ".$prefix."_stories where aid='$adm_aid'", $dbi);
	while(list($sid, $old_aid, $informant) = sql_fetch_row($result)) {
	    if ($old_aid == $informant) {
		sql_query("update ".$prefix."_stories set informant='$chng_aid' where sid='$sid'", $dbi);
	    }
	    sql_query("update ".$prefix."_stories set aid='$chng_aid' WHERE sid='$sid'", $dbi);

	}
    }
}

function deladmin2($del_aid) {
    global $prefix, $dbi;
    $result = sql_query("select radminarticle from ".$prefix."_authors where aid='$del_aid'", $dbi);
    list($radminarticle) = sql_fetch_row($result, $dbi);
    if ($radminarticle == 1) {
	$result2 = sql_query("select sid from ".$prefix."_stories where aid='$del_aid'", $dbi);
	list($sid) = sql_fetch_row($result2, $dbi);
	if ($sid != "") {
	    include("header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo "<center><font class=\"title\"><b>"._AUTHORSADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
	    echo "<center><font class=\"option\"><b>"._PUBLISHEDSTORIES."</b></font><br><br>"
		.""._SELECTNEWADMIN.":<br><br>";
	    $res = sql_query("select aid from ".$prefix."_authors where aid!='$del_aid'", $dbi);
	    echo "<form action=\"admin.php\" method=\"post\"><select name=\"newaid\">";
	    while(list($oaid) = sql_fetch_row($res, $dbi)) {
		echo "<option name=\"newaid\" value=\"$oaid\">$oaid</option>";
	    }
	    echo "</select><input type=\"hidden\" name=\"del_aid\" value=\"$del_aid\">"
		."<input type=\"hidden\" name=\"op\" value=\"assignstories\">"
		."<input type=\"submit\" value=\""._OK."\">"
		."</form>";
	    CloseTable();
	    include("footer.php");
	    return;
	}
    }
    Header("Location: admin.php?op=deladminconf&del_aid=$del_aid");
}

switch ($op) {

    case "mod_authors":
    displayadmins();
    break;

    case "modifyadmin":
    modifyadmin($chng_aid);
    break;

    case "UpdateAuthor":
    updateadmin($chng_aid, $chng_name, $chng_email, $chng_url, $chng_radminarticle, $chng_radmintopic, $chng_radminuser, $chng_radminsurvey, $chng_radminsection, $chng_radminlink, $chng_radminephem, $chng_radminfaq, $chng_radmindownload, $chng_radminreviews, $chng_radminnewsletter, $chng_radminforum, $chng_radmincontent, $chng_radminency, $chng_radminsuper, $chng_pwd, $chng_pwd2, $chng_admlanguage, $adm_aid);
    break;

    case "AddAuthor":
    if (!($add_aid && $add_name && $add_email && $add_pwd)) {
        include("header.php");
        GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._AUTHORSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><font class=\"option\"><b>"._CREATIONERROR."</b></font><br><br>"
	    .""._COMPLETEFIELDS."<br><br>"
	    .""._GOBACK."</center>";
    	CloseTable();
	include("footer.php");
	return;
    }
    $add_pwd = md5($add_pwd);
    $result = sql_query("insert into ".$prefix."_authors values ('$add_aid', '$add_name', '$add_url', '$add_email', '$add_pwd', '0', '$add_radminarticle','$add_radmintopic','$add_radminuser','$add_radminsurvey','$add_radminsection','$add_radminlink','$add_radminephem','$add_radminfaq','$add_radmindownload','$add_radminreviews','$add_radminnewsletter','$add_radminforum','$add_radmincontent','$add_radminency','$add_radminsuper','$add_admlanguage')", $dbi);
    if (!$result) {
	return;
    }
    Header("Location: admin.php?op=mod_authors");
    break;

    case "deladmin":
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._AUTHORSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._AUTHORDEL."</b></font><br><br>"
	.""._AUTHORDELSURE." <i>$del_aid</i>?<br><br>";
    echo "[ <a href=\"admin.php?op=deladmin2&amp;del_aid=$del_aid\">"._YES."</a> | <a href=\"admin.php?op=mod_authors\">"._NO."</a> ]";
    CloseTable();
    include("footer.php");
    break;

    case "deladmin2":
    deladmin2($del_aid);
    break;

    case "assignstories":
    $result = sql_query("select sid from ".$prefix."_stories where aid='$del_aid'", $dbi);
    while(list($sid) = sql_fetch_row($result, $dbi)) {
	sql_query("update ".$prefix."_stories set aid='$newaid', informant='$newaid' where aid='$del_aid'", $dbi);
    }
    Header("Location: admin.php?op=deladminconf&del_aid=$del_aid");
    break;

    case "deladminconf":
    sql_query("delete from ".$prefix."_authors where aid='$del_aid'", $dbi);
    Header("Location: admin.php?op=mod_authors");
    break;

}

} else {
    echo "Access Denied";
}

?>