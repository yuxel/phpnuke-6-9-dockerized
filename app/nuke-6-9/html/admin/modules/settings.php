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
/* Configuration Functions to Setup all the Variables    */
/*********************************************************/

function Configure() {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    $result = sql_query("SELECT sitename, nukeurl, site_logo, slogan, startdate, adminmail, anonpost, Default_Theme, foot1, foot2, foot3, commentlimit, anonymous, minpass, pollcomm, articlecomm, broadcast_msg, my_headlines, top, storyhome, user_news, oldnum, ultramode, banners, backend_title, backend_language, language, locale, multilingual, useflags, notify, notify_email, notify_subject, notify_message, notify_from, footermsgtxt, email_send, attachmentdir, attachments, attachments_view, download_dir, defaultpopserver, singleaccount, singleaccountname, numaccounts, imgpath, filter_forward, moderate, admingraphic, httpref, httprefmax, CensorMode, CensorReplace from ".$prefix."_config", $dbi);
    list($sitename, $nukeurl, $site_logo, $slogan, $startdate, $adminmail, $anonpost, $Default_Theme, $foot1, $foot2, $foot3, $commentlimit, $anonymous, $minpass, $pollcomm, $articlecomm, $broadcast_msg, $my_headlines, $top, $storyhome, $user_news, $oldnum, $ultramode, $banners, $backend_title, $backend_language, $language, $locale, $multilingual, $useflags, $notify, $notify_email, $notify_subject, $notify_message, $notify_from, $footermsgtxt, $email_send, $attachmentdir, $attachments, $attachments_view, $download_dir, $defaultpopserver, $singleaccount, $singleaccountname, $numaccounts, $imgpath, $filter_forward, $moderate, $admingraphic, $httpref, $httprefmax, $CensorMode, $CensorReplace) = sql_fetch_row($result, $dbi);
    OpenTable();
    echo "<center><font class='title'><b>"._SITECONFIG."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._GENSITEINFO."</b></font></center>"
	."<form action='admin.php' method='post'>"
	."<table border='0'><tr><td>"
	.""._SITENAME.":</td><td><input type='text' name='xsitename' value='$sitename' size='40' maxlength='255'>"
	."</td></tr><tr><td>"
	.""._SITEURL.":</td><td><input type='text' name='xnukeurl' value='$nukeurl' size='40' maxlength='255'>"
	."</td></tr><tr><td>"
	.""._SITELOGO.":</td><td><input type='text' name='xsite_logo' value='$site_logo' size='20' maxlength='255'> <font class='tiny'>[ "._MUSTBEINIMG." ]</font>"
	."</td></tr><tr><td>"
	.""._SITESLOGAN.":</td><td><input type='text' name='xslogan' value='$slogan' size='40' maxlength='255'>"
	."</td></tr><tr><td>"
	.""._STARTDATE.":</td><td><input type='text' name='xstartdate' value='$startdate' size='20' maxlength='50'>"
	."</td></tr><tr><td>"
	.""._ADMINEMAIL.":</td><td><input type='text' name='xadminmail' value='$adminmail' size='30' maxlength='255'>"
	."</td></tr><tr><td>"
	.""._ITEMSTOP.":</td><td><select name='xtop'>"
	."<option name='xtop'>$top</option>"
	."<option name='xtop'>5</option>"
	."<option name='xtop'>10</option>"
        ."<option name='xtop'>15</option>"
        ."<option name='xtop'>20</option>"
        ."<option name='xtop'>25</option>"
        ."<option name='xtop'>30</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._STORIESHOME.":</td><td><select name='xstoryhome'>"
        ."<option name='xstoryhome'>$storyhome</option>"
        ."<option name='xstoryhome'>5</option>"
        ."<option name='xstoryhome'>10</option>"
        ."<option name='xstoryhome'>15</option>"
        ."<option name='xstoryhome'>20</option>"
        ."<option name='xstoryhome'>25</option>"
        ."<option name='xstoryhome'>30</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._OLDSTORIES.":</td><td><select name='xoldnum'>"
        ."<option name='xoldnum'>$oldnum</option>"
        ."<option name='xoldnum'>10</option>"
        ."<option name='xoldnum'>20</option>"
        ."<option name='xoldnum'>30</option>"
        ."<option name='xoldnum'>40</option>"
        ."<option name='xoldnum'>50</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._ACTULTRAMODE."</td><td>";
    if ($ultramode==1) {
	echo "<input type='radio' name='xultramode' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xultramode' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xultramode' value='1'>"._YES." &nbsp;
	<input type='radio' name='xultramode' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>
    "._ALLOWANONPOST." </td><td>";
    if ($anonpost==1) {
	echo "<input type='radio' name='xanonpost' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xanonpost' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xanonpost' value='1'>"._YES." &nbsp;
	<input type='radio' name='xanonpost' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._DEFAULTTHEME.":</td><td><select name='xDefault_Theme'>";
    $handle=opendir('themes');
    while ($file = readdir($handle)) {
	if ( (!ereg("[.]",$file)) ) {
		$themelist .= "$file ";
	}
    }
    closedir($handle);
    $themelist = explode(" ", $themelist);
    sort($themelist);
    for ($i=0; $i < sizeof($themelist); $i++) {
	if($themelist[$i]!="") {
	    echo "<option name='xDefault_Theme' value='$themelist[$i]' ";
		if($themelist[$i]==$Default_Theme) echo "selected";
		echo ">$themelist[$i]\n";
	}
    }
    echo "</select>"
	."</td></tr><tr><td>"
	.""._SELLANGUAGE.":</td><td>"
	."<select name='xlanguage'>";
    $handle=opendir('language');
    while ($file = readdir($handle)) {
	if (ereg("^lang\-(.+)\.php", $file, $matches)) {
            $langFound = $matches[1];
            $languageslist .= "$langFound ";
        }
    }
    closedir($handle);
    $languageslist = explode(" ", $languageslist);
    sort($languageslist);
    for ($i=0; $i < sizeof($languageslist); $i++) {
	if($languageslist[$i]!="") {
	    echo "<option name='xlanguage' value='$languageslist[$i]' ";
		if($languageslist[$i]==$language) echo "selected";
		echo ">".ucfirst($languageslist[$i])."\n";
	}
    }
    echo "</select>"
	."</td></tr><tr><td>"
	.""._LOCALEFORMAT.":</td><td><input type='text' name='xlocale' value='$locale' size='20' maxlength='40'>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._MULTILINGUALOPT."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._ACTMULTILINGUAL."</td><td>";
    if ($multilingual==1) {
	echo "<input type='radio' name='xmultilingual' value='1' checked>"._YES." &nbsp;"
	    ."<input type='radio' name='xmultilingual' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xmultilingual' value='1'>"._YES." &nbsp;"
	    ."<input type='radio' name='xmultilingual' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._ACTUSEFLAGS."</td><td>";
    if ($useflags==1) {
	echo "<input type='radio' name='xuseflags' value='1' checked>"._YES." &nbsp;"
	    ."<input type='radio' name='xuseflags' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xuseflags' value='1'>"._YES." &nbsp;"
	    ."<input type='radio' name='xuseflags' value='0' checked>"._NO."";
    }
    echo "</td></tr></table>";
    echo "<br>";
    CloseTable();
    echo "<br><a name='banners'>";
    OpenTable();
    echo "<center><font class='option'><b>"._BANNERSOPT."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._ACTBANNERS."</td><td>";
    if ($banners==1) {
	echo "<input type='radio' name='xbanners' value='1' checked>"._YES." &nbsp;"
	    ."<input type='radio' name='xbanners' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xbanners' value='1'>"._YES." &nbsp;"
	    ."<input type='radio' name='xbanners' value='0' checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._FOOTERMSG."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._FOOTERLINE1.":</td><td><textarea name='xfoot1' cols='50' rows='5'>".stripslashes($foot1)."</textarea>"
	."</td></tr><tr><td>"
	.""._FOOTERLINE2.":</td><td><textarea name='xfoot2' cols='50' rows='5'>".stripslashes($foot2)."</textarea>"
	."</td></tr><tr><td>"
	.""._FOOTERLINE3.":</td><td><textarea name='xfoot3' cols='50' rows='5'>".stripslashes($foot3)."</textarea>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._BACKENDCONF."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._BACKENDTITLE.":</td><td><input type='text' name='xbackend_title' value='$backend_title' size='40' maxlength='100'>"
	."</td></tr><tr><td>"
	.""._BACKENDLANG.":</td><td><input type='text' name='xbackend_language' value='$backend_language' size='10' maxlength='10'>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._MAIL2ADMIN."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._NOTIFYSUBMISSION."</td><td>";
    if ($notify==1) {
	echo "<input type='radio' name='xnotify' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xnotify' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xnotify' value='1'>"._YES." &nbsp;
	<input type='radio' name='xnotify' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._EMAIL2SENDMSG.":</td><td><input type='text' name='xnotify_email' value='$notify_email' size='30' maxlength='100'>"
	."</td></tr><tr><td>"
	.""._EMAILSUBJECT.":</td><td><input type='text' name='xnotify_subject' value='$notify_subject' size='40' maxlength='100'>"
	."</td></tr><tr><td>"
	.""._EMAILMSG.":</td><td><textarea name='xnotify_message' cols='40' rows='8'>$notify_message</textarea>"
	."</td></tr><tr><td>"
	.""._EMAILFROM.":</td><td><input type='text' name='xnotify_from' value='$notify_from' size='15' maxlength='25'>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._COMMENTSMOD."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._MODTYPE.":</td><td>"
	."<select name='xmoderate'>";
    if ($moderate==1) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
    } elseif ($moderate==2) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
    } elseif ($moderate==0) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
    }
    echo "<option name='xmoderate' value='1' $sel1>"._MODADMIN."</option>"
        ."<option name='xmoderate' value='2' $sel2>"._MODUSERS."</option>"
        ."<option name='xmoderate' value='0' $sel3>"._NOMOD."</option>"
	."</select></td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._COMMENTSOPT."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._COMMENTSLIMIT.":</td><td><input type='text' name='xcommentlimit' value='$commentlimit' size='11' maxlength='10'>"
	."</td></tr><tr><td>"
	.""._ANONYMOUSNAME.":</td><td><input type='text' name='xanonymous' value='$anonymous'>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._GRAPHICOPT."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._ADMINGRAPHIC."</td><td>";
    if ($admingraphic==1) {
	echo "<input type='radio' name='xadmingraphic' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xadmingraphic' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xadmingraphic' value='1'>"._YES." &nbsp;
	<input type='radio' name='xadmingraphic' value='0' checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._MISCOPT."</b></font></center>"
	."<table border='0'><tr><td>"
        .""._ACTIVATEHTTPREF."</td><td>";
    if ($httpref==1) {
	echo "<input type='radio' name=xhttpref value='1' checked>"._YES." &nbsp;
	<input type='radio' name=xhttpref value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xhttpref' value='1'>"._YES." &nbsp;
	<input type='radio' name='xhttpref' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._MAXREF."</td><td>"
	."<select name='xhttprefmax'>"
        ."<option name='xhttprefmax' value='$httprefmax'>$httprefmax</option>"
        ."<option name='xhttprefmax' value='100'>100</option>"
        ."<option name='xhttprefmax' value='250'>250</option>"
        ."<option name='xhttprefmax' value='500'>500</option>"
        ."<option name='xhttprefmax' value='1000'>1000</option>"
        ."<option name='xhttprefmax' value='2000'>2000</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._COMMENTSPOLLS."</td><td>";
    if ($pollcomm==1) {
	echo "<input type='radio' name='xpollcomm' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xpollcomm' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xpollcomm' value='1'>"._YES." &nbsp;
	<input type='radio' name='xpollcomm' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
        .""._COMMENTSARTICLES."</td><td>";
    if ($articlecomm==1) {
	echo "<input type='radio' name='xarticlecomm' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xarticlecomm' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xarticlecomm' value='1'>"._YES." &nbsp;
	<input type='radio' name='xarticlecomm' value='0' checked>"._NO."";
    }
    echo "</td></tr></table><br><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._USERSOPTIONS."</b></font></center>"
	."<table border='0'><tr><td>"
        .""._PASSWDLEN.":</td><td>"
        ."<select name='xminpass'>"
        ."<option name='xminpass' value='$minpass'>$minpass</option>"
        ."<option name='xminpass' value='3'>3</option>"
        ."<option name='xminpass' value='5'>5</option>"
        ."<option name='xminpass' value='8'>8</option>"
        ."<option name='xminpass' value='10'>10</option>"
        ."</select>"
	."</td></tr><tr><td>"._BROADCASTMSG."</td><td>";
    if ($broadcast_msg == 1) {
	echo "<input type='radio' name='xbroadcast_msg' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xbroadcast_msg' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xbroadcast_msg' value='1'>"._YES." &nbsp;
	<input type='radio' name='xbroadcast_msg' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"._MYHEADLINES."</td><td>";
    if ($my_headlines == 1) {
	echo "<input type='radio' name='xmy_headlines' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xmy_headlines' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xmy_headlines' value='1'>"._YES." &nbsp;
	<input type='radio' name='xmy_headlines' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"._USERSHOMENUM."</td><td>";
    if ($user_news == 1) {
	echo "<input type='radio' name='xuser_news' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xuser_news' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xuser_news' value='1'>"._YES." &nbsp;
	<input type='radio' name='xuser_news' value='0' checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._CENSOROPTIONS."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._CENSORMODE."</td><td>";
    if ($CensorMode == 0) {
	$sel0 = "selected";
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
    } elseif ($CensorMode == 1) {
	$sel0 = "";
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
    } elseif ($CensorMode == 2) {
	$sel0 = "";
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
    } elseif ($CensorMode == 3) {
	$sel0 = "";
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
    }
    echo "<select name='xCensorMode'>"
	."<option name='xCensorMode' value='0' $sel0>"._NOFILTERING."</option>"
	."<option name='xCensorMode' value='1' $sel1>"._EXACTMATCH."</option>"
	."<option name='xCensorMode' value='2' $sel2>"._MATCHBEG."</option>"
	."<option name='xCensorMode' value='3' $sel3>"._MATCHANY."</option>"
	."</select>"
	."</td></tr><tr><td>"._CENSORREPLACE."</td><td>"
	."<input type='text' name='xCensorReplace' value='$CensorReplace' size='10' maxlength='10'>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class='option'><b>"._WEBMAILOPTIONS."</b></font></center>"
	."<table border='0'><tr><td>"
	.""._FOOTMSG."</td><td>"
	."<textarea name='xfootermsgtxt' rows='3' cols='50'>$footermsgtxt</textarea>"
	."</td><tr><tr><td>"._USERSSENDMAILS."</td><td>";
    if ($email_send == 1) {
	echo "<input type='radio' name='xemail_send' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xemail_send' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xemail_send' value='1'>"._YES." &nbsp;
	<input type='radio' name='xemail_send' value='0' checked>"._NO."";
    }
    echo "</td><tr><tr><td>"._ATTACHMENTS."</td><td>";
    if ($attachments == 1) {
	echo "<input type='radio' name='xattachments' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xattachments' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xattachments' value='1'>"._YES." &nbsp;
	<input type='radio' name='xattachments' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"._ATTACHDIR."</td><td>"
	."<input type='text' name='xattachmentdir' value='$attachmentdir' size='40' maxlength='255'> <font class='tiny'>[ "._WORLDWRITABLE." ]</font>"
	."</td><tr><tr><td>"._ATTACHVIEW."</td><td>";
    if ($attachments_view == 1) {
	echo "<input type='radio' name='xattachments_view' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xattachments_view' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xattachments_view' value='1'>"._YES." &nbsp;
	<input type='radio' name='xattachments_view' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"._DOWNDIR."</td><td>"
	."<input type='text' name='xdownload_dir' value='$download_dir' size='40' maxlength='255'> <font class='tiny'>[ "._WORLDWRITABLE." ]</font>"
	."</td><tr><tr><td>"._MAXACT."</td><td>"
	."<input type='text' name='xnumaccounts' value='$numaccounts' size='3' maxlength='2'> <font class='tiny'>[ "._MINUSONE." ]</font>"
	."</td></tr><tr><td>"._SINGLEACT."</td><td>";
    if ($singleaccount == 1) {
	echo "<input type='radio' name='xsingleaccount' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xsingleaccount' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xsingleaccount' value='1'>"._YES." &nbsp;
	<input type='radio' name='xsingleaccount' value='0' checked>"._NO."";
    }
    echo "</td></tr><tr><td>"._SINGLEACTNAME."</td><td>"
	."<input type='text' name='xsingleaccountname' value='$singleaccountname' size='50' maxlength='255'>"
	."</td><tr><tr><td>"._DEFPOP."</td><td>"
	."<input type='text' name='xdefaultpopserver' value='$defaultpopserver' size='50' maxlength='255'>"
	."</td><tr><tr><td>"._IMGPATH."</td><td>"
	."<input type='text' name='ximgpath' value='$imgpath' size='50' maxlength='255'>"
	."</td></tr><tr><td>"._FILTERFOR."</td><td>";
    if ($filter_forward == 1) {
	echo "<input type='radio' name='xfilter_forward' value='1' checked>"._YES." &nbsp;
	<input type='radio' name='xfilter_forward' value='0'>"._NO."";
    } else {
	echo "<input type='radio' name='xfilter_forward' value='1'>"._YES." &nbsp;
	<input type='radio' name='xfilter_forward' value='0' checked>"._NO."";
    }
    echo "</td></tr></table><br><br>";
    echo "<input type='hidden' name='op' value='ConfigSave'>"
	."<center><input type='submit' value='"._SAVECHANGES."'></center>"
	."</form>";
    CloseTable();
    include ("footer.php");
}

function ConfigSave ($xsitename, $xnukeurl, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xanonpost, $xDefault_Theme, $xfoot1, $xfoot2, $xfoot3, $xcommentlimit, $xanonymous, $xminpass, $xpollcomm, $xarticlecomm, $xbroadcast_msg, $xmy_headlines, $xtop, $xstoryhome, $xuser_news, $xoldnum, $xultramode, $xbanners, $xbackend_title, $xbackend_language, $xlanguage, $xlocale, $xmultilingual, $xuseflags, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xfootermsgtxt, $xemail_send, $xattachmentdir, $xattachments, $xattachments_view, $xdownload_dir, $xdefaultpopserver, $xsingleaccount, $xsingleaccountname, $xnumaccounts, $ximgpath, $xfilter_forward, $xmoderate, $xadmingraphic, $xhttpref, $xhttprefmax, $xCensorMode, $xCensorReplace) {
    global $prefix, $dbi;
    $xsitename = htmlentities($xsitename, ENT_QUOTES);
    $xslogan = htmlentities($xslogan, ENT_QUOTES);
    $xbackend_title = htmlentities($xbackend_title, ENT_QUOTES);
    $xnotify_subject = htmlentities($xnotify_subject, ENT_QUOTES);
    $xsingleaccountname = htmlentities($xsingleaccountname, ENT_QUOTES);
    sql_query("UPDATE ".$prefix."_config SET sitename='$xsitename', nukeurl='$xnukeurl', site_logo='$xsite_logo', slogan='$xslogan', startdate='$xstartdate', adminmail='$xadminmail', anonpost='$xanonpost', Default_Theme='$xDefault_Theme', foot1='$xfoot1', foot2='$xfoot2', foot3='$xfoot3', commentlimit='$xcommentlimit', anonymous='$xanonymous', minpass='$xminpass', pollcomm='$xpollcomm', articlecomm='$xarticlecomm', broadcast_msg='$xbroadcast_msg', my_headlines='$xmy_headlines', top='$xtop', storyhome='$xstoryhome', user_news='$xuser_news', oldnum='$xoldnum', ultramode='$xultramode', banners='$xbanners', backend_title='$xbackend_title', backend_language='$xbackend_language', language='$xlanguage', locale='$xlocale', multilingual='$xmultilingual', useflags='$xuseflags', notify='$xnotify', notify_email='$xnotify_email', notify_subject='$xnotify_subject', notify_message='$xnotify_message', notify_from='$xnotify_from', footermsgtxt='$xfootermsgtxt', email_send='$xemail_send', attachmentdir='$xattachmentdir', attachments='$xattachments', attachments_view='$xattachments_view', download_dir='$xdownload_dir', defaultpopserver='$xdefaultpopserver', singleaccount='$xsingleaccount', singleaccountname='$xsingleaccountname', numaccounts='$xnumaccounts', imgpath='$ximgpath', filter_forward='$xfilter_forward', moderate='$xmoderate', admingraphic='$xadmingraphic', httpref='$xhttpref', httprefmax='$xhttprefmax', CensorMode='$xCensorMode', CensorReplace='$xCensorReplace'", $dbi);
    Header("Location: admin.php?op=Configure");
}

switch($op) {

    case "Configure":
    Configure();
    break;

    case "ConfigSave":
    ConfigSave ($xsitename, $xnukeurl, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xanonpost, $xDefault_Theme, $xfoot1, $xfoot2, $xfoot3, $xcommentlimit, $xanonymous, $xminpass, $xpollcomm, $xarticlecomm, $xbroadcast_msg, $xmy_headlines, $xtop, $xstoryhome, $xuser_news, $xoldnum, $xultramode, $xbanners, $xbackend_title, $xbackend_language, $xlanguage, $xlocale, $xmultilingual, $xuseflags, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xfootermsgtxt, $xemail_send, $xattachmentdir, $xattachments, $xattachments_view, $xdownload_dir, $xdefaultpopserver, $xsingleaccount, $xsingleaccountname, $xnumaccounts, $ximgpath, $xfilter_forward, $xmoderate, $xadmingraphic, $xhttpref, $xhttprefmax, $xCensorMode, $xCensorReplace);
    break;

}

} else {
    echo "Access Denied";
}

?>