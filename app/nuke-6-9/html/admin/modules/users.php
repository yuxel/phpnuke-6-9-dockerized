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

$result = sql_query("select radminuser, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminuser, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminuser==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Users Functions                                       */
/*********************************************************/

function displayUsers() {
    global $admin;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._EDITUSER."</b></font><br><br>"
	."<form method=\"post\" action=\"admin.php\">"
	."<b>"._NICKNAME.": </b> <input type=\"text\" name=\"chng_uid\" size=\"20\">\n"
	."<select name=\"op\">"
	."<option value=\"modifyUser\">"._MODIFY."</option>\n"
	."<option value=\"delUser\">"._DELETE."</option></select>\n"
	."<input type=\"submit\" value=\""._OK."\"></form></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._ADDUSER."</b></font><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\">"
        ."<tr><td width=\"100\">"._NICKNAME."</td>"
        ."<td><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"25\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._NAME."</td>"
        ."<td><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"></td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._FAKEEMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._ICQ."</td>"
        ."<td><input type=\"text\" name=\"add_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._AIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._YIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._MSNM."</td>"
        ."<td><input type=\"text\" name=\"add_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._LOCATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._OCCUPATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._INTERESTS."</td>"
        ."<td><input type=\"text\" name=\"add_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>"
	."<tr><td>"._OPTION."</td>"
        ."<td><input type=\"checkbox\" name=\"add_user_viewemail\" VALUE=\"1\"> "._ALLOWUSERS."</td></tr>"
	."<tr><td>"._NEWSLETTER."</td>"
	."<td><input type=\"radio\" name=\"add_newsletter\" value=\"1\">"._YES."<br>"
	."<input type=\"radio\" name=\"add_newsletter\" value=\"0\" checked>"._NO."</td></tr>"
        ."<tr><td>"._SIGNATURE."</td>"
        ."<td><textarea name=\"add_user_sig\" rows=\"6\" cols=\"45\"></textarea></td></tr>"
        ."<tr><td>"._PASSWORD."</td>"
        ."<td><input type=\"password\" name=\"add_pass\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
        ."<input type=\"hidden\" name=\"add_avatar\" value=\"blank.gif\">"
        ."<input type=\"hidden\" name=\"op\" value=\"addUser\">"
        ."<tr><td><input type=\"submit\" value=\""._ADDUSERBUT."\"></form></td></tr>"
        ."</table>";
    CloseTable();
    include("footer.php");
}

function modifyUser($chng_user) {
    global $user_prefix, $dbi;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = sql_query("select user_id, username, name, user_website, user_email, femail, user_icq, user_aim, user_yim, user_msnm, user_from, user_occ, user_interests, user_viewemail, user_avatar, user_sig, user_password, newsletter from ".$user_prefix."_users where user_id='$chng_user' or username='$chng_user'", $dbi);
    if(sql_num_rows($result, $dbi) > 0) {
        list($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass, $chng_newsletter) = sql_fetch_row($result, $dbi);
	OpenTable();
	echo "<center><font class=\"option\"><b>"._USERUPDATE.": <i>$chng_user</i></b></font></center>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<table border=\"0\">"
	    ."<tr><td>"._USERID."</td>"
	    ."<td><b>$chng_uid</b></td></tr>"
	    ."<tr><td>"._NICKNAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_uname\" value=\"$chng_uname\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._NAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_name\" value=\"$chng_name\"></td></tr>"
	    ."<tr><td>"._URL."</td>"
	    ."<td><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._EMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._FAKEEMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_femail\" value=\"$chng_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._ICQ."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_icq\" value=\"$chng_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._AIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_aim\" value=\"$chng_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._YIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_yim\" value=\"$chng_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._MSNM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_msnm\" value=\"$chng_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._LOCATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_from\" value=\"$chng_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._OCCUPATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_occ\" value=\"$chng_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._INTERESTS."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_intrest\" value=\"$chng_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>"
	    ."<tr><td>"._OPTION."</td>";
	if ($chng_user_viewemail ==1) {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked> "._ALLOWUSERS."</td></tr>";
	} else {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\"> "._ALLOWUSERS."</td></tr>";
	}
	if ($chng_newsletter == 1) {
	    echo "<tr><td>"._NEWSLETTER."</td><td><input type=\"radio\" name=\"chng_newsletter\" value=\"1\" checked>"._YES."<br>"
		."<input type=\"radio\" name=\"chng_newsletter\" value=\"0\">"._NO."<br>";
	} elseif ($chng_newsletter == 0) {
	    echo "<tr><td>"._NEWSLETTER."</td><td><input type=\"radio\" name=\"chng_newsletter\" value=\"1\">"._YES."<br>"
		."<input type=\"radio\" name=\"chng_newsletter\" value=\"0\" checked>"._NO."<br>";
	}
	echo "<tr><td>"._SIGNATURE."</td>"
	    ."<td><textarea name=\"chng_user_sig\" rows=\"6\" cols=\"45\">$chng_user_sig</textarea></td></tr>"
	    ."<tr><td>"._PASSWORD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"12\"></td></tr>"
	    ."<tr><td>"._RETYPEPASSWD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"12\"> <font class=\"tiny\">"._FORCHANGES."</font></td></tr>"
	    ."<input type=\"hidden\" name=\"chng_avatar\" value=\"$chng_avatar\">"
	    ."<input type=\"hidden\" name=\"chng_uid\" value=\"$chng_uid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"updateUser\">"
	    ."<tr><td><input type=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>"
	    ."</table>";
	CloseTable();
    } else {
	OpenTable();
	echo "<center><b>"._USERNOEXIST."</b><br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
    }
    include("footer.php");
}

function updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass, $chng_pass2, $chng_newsletter) {
    global $user_prefix, $dbi;
    $tmp = 0;
    if ($chng_pass2 != "") {
        if($chng_pass != $chng_pass2) {
            include("header.php");
	    GraphicAdmin();
	    OpenTable();
	    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
            echo "<center>"._PASSWDNOMATCH."<br><br>"
		.""._GOBACK."</center>";
	    CloseTable();
            include("footer.php");
            exit;
        }
        $tmp = 1;
    }
    if ($tmp == 0) {
	sql_query("update ".$user_prefix."_users set username='$chng_uname', name='$chng_name', user_email='$chng_email', femail='$chng_femail', user_website='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_interests='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', newsletter='$chng_newsletter' where user_id='$chng_uid'", $dbi);
    }
    if ($tmp == 1) {
    	$cpass = md5($chng_pass);
        sql_query("update ".$user_prefix."_users set username='$chng_uname', name='$chng_name', user_email='$chng_email', femail='$chng_femail', user_website='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_interests='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_user_sig', user_password='$cpass', newsletter='$chng_newsletter' where user_id='$chng_uid'", $dbi);
    }
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "mod_users":
    displayUsers();
    break;

    case "modifyUser":
    modifyUser($chng_uid);
    break;

    case "updateUser":
    updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass, $chng_pass2, $chng_newsletter);
    break;

    case "delUser":
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._DELETEUSER."</b></font><br><br>"
	.""._SURE2DELETE." $chng_uid?<br><br>"
	."[ <a href=\"admin.php?op=delUserConf&amp;del_uid=$chng_uid\">"._YES."</a> | <a href=\"admin.php?op=mod_users\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "delUserConf":
    sql_query("delete from ".$user_prefix."_users where username='$del_uid'", $dbi);
    Header("Location: admin.php?op=adminMain");
    break;

    case "addUser":
    $add_pass = md5($add_pass);
    if (!($add_uname && $add_email && $add_pass)) {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._USERADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><b>"._NEEDTOCOMPLETE."</b><br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
        return;
    }
    $user_regdate = date("M d, Y");
    $sql = "insert into ".$user_prefix."_users ";
    $sql .= "(user_id,name,username,user_email,femail,user_website,user_regdate,user_icq,user_aim,user_yim,user_msnm,user_from,user_occ,user_interests,user_viewemail,user_avatar,user_sig,user_password,newsletter,broadcast,popmeson) ";
    $sql .= "values (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_icq','$add_user_aim','$add_user_yim','$add_user_msnm','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_pass','$add_newsletter','1','0')";
    $result = sql_query($sql, $dbi);
    if (!$result) {
        return;
    }
    Header("Location: admin.php?op=adminMain");
    break;

}

} else {
    echo "Access Denied";
}

?>