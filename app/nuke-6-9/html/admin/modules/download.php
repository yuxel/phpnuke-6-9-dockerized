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

$result = sql_query("select radmindownload, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radmindownload, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radmindownload==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Downloads Modified Web Downloads                      */
/*********************************************************/

function getparent($parentid,$title) {
    global $prefix,$dbi;
    $result=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories where cid='$parentid'", $dbi);
    list($cid, $ptitle, $pparentid) = sql_fetch_row($result, $dbi);
    if ($ptitle!="") $title=$ptitle."/".$title;
    if ($pparentid!=0) {
	$title=getparent($pparentid,$title);
    }
    return $title;
}

function downloads() {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    if (file_exists("themes/$ThemeSel/images/down-logo.gif")) {
	echo "<center><a href=\"modules.php?name=$module_name\"><img src=\"themes/$ThemeSel/images/down-logo.gif\" border=\"0\" alt=\"\"></a><br><br>";
    } else {
	echo "<center><a href=\"modules.php?name=$module_name\"><img src=\"modules/Downloads/images/down-logo.gif\" border=\"0\" alt=\"\"></a><br><br>";
    }
    $result=sql_query("select * from ".$prefix."_downloads_downloads", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    echo "<font class=\"content\">"._THEREARE." <b>$numrows</b> "._DOWNLOADSINDB."</font></center>";
    CloseTable();
    echo "<br>";
    
/* Temporarily 'homeless' downloads functions (to be revised in admin.php breakup) */

    $result = sql_query("select * from ".$prefix."_downloads_modrequest where brokendownload='1'", $dbi);
    $totalbrokendownloads = sql_num_rows($result, $dbi);
    $result2 = sql_query("select * from ".$prefix."_downloads_modrequest where brokendownload='0'", $dbi);
    $totalmodrequests = sql_num_rows($result2, $dbi);

/* List Downloads waiting for validation */

    $result = sql_query("select lid, cid, sid, title, url, description, name, email, submitter, filesize, version, homepage from ".$prefix."_downloads_newdownload order by lid", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	OpenTable();
	echo "<center><font class=\"content\"><b>"._DOWNLOADSWAITINGVAL."</b></font></center><br><br>";
	while(list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter, $filesize, $version, $homepage) = sql_fetch_row($result, $dbi)) {
	    if ($submitter == "") {
		$submitter = _NONE;
	    }
    	    $homepage = ereg_replace("http://","",$homepage);
	    echo "<form action=\"admin.php\" method=\"post\">"
		."<b>"._DOWNLOADID.": $lid</b><br><br>"
		.""._SUBMITTER.": <b>$submitter</b><br>"
		.""._DOWNLOADNAME.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br>"
		.""._FILEURL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\">&nbsp;[ <a target=\"_blank\" href=\"$url\">"._CHECK."</a> ]<br>"
		.""._DESCRIPTION.": <br><textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea><br>"
		.""._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"20\" maxlength=\"100\" value=\"$name\">&nbsp;&nbsp;"
		.""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"20\" maxlength=\"100\" value=\"$email\"><br>"
		.""._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\" value=\"$filesize\"><br>"
		.""._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\" value=\"$version\"><br>"
		.""._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"30\" maxlength=\"200\" value=\"http://$homepage\"> [ <a href=\"http://$homepage\">"._VISIT."</a> ]<br>";
	    echo "<input type=\"hidden\" name=\"new\" value=\"1\">";
	    echo "<input type=\"hidden\" name=\"hits\" value=\"0\">";
	    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
	    echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
	    echo ""._CATEGORY.": <select name=\"cat\">";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by title", $dbi);
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
		if ($cid2==$cid) {
			$sel = "selected";
		} else {
			$sel = "";
		}
		if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\" $sel>$ctitle2</option>";
	}

	    echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
	    echo "</select><input type=\"hidden\" name=\"op\" value=\"DownloadsAddDownload\"><input type=\"submit\" value="._ADD."> [ <a href=\"admin.php?op=DownloadsDelNew&amp;lid=$lid\">"._DELETE."</a> ]</form><br><hr noshade><br>";
	}
	CloseTable();
	echo "<br>";
    } else {
    }

/* Add a New Main Category */

    OpenTable();
    echo "<center><font class=\"content\">[ <a href=\"admin.php?op=DownloadsCleanVotes\">"._CLEANDOWNLOADSDB."</a> | "
	."<a href=\"admin.php?op=DownloadsListBrokenDownloads\">"._BROKENDOWNLOADSREP." ($totalbrokendownloads)</a> | "
	."<a href=\"admin.php?op=DownloadsListModRequests\">"._DOWNLOADMODREQUEST." ($totalmodrequests)</a> | "
	."<a href=\"admin.php?op=DownloadsDownloadCheck\">"._VALIDATEDOWNLOADS."</a> ]</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form method=\"post\" action=\"admin.php\">"
	."<font class=\"content\"><b>"._ADDMAINCATEGORY."</b><br><br>"
	.""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\"><br>"
	.""._DESCRIPTION.":<br><textarea name=\"cdescription\" cols=\"60\" rows=\"10\"></textarea><br>"
	."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddCat\">"
	."<input type=\"submit\" value=\""._ADD."\"><br>"
	."</form>";
    CloseTable();
    echo "<br>";

// Add a New Sub-Category

    $result = sql_query("select * from ".$prefix."_downloads_categories", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font class=\"content\"><b>"._ADDSUBCATEGORY."</b></font><br><br>"
	    .""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\">&nbsp;"._IN."&nbsp;";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by parentid,title", $dbi);
	echo "<select name=\"cid\">";
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
		if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\">$ctitle2</option>";
	}
	echo "</select><br>"
	    .""._DESCRIPTION.":<br><textarea name=\"cdescription\" cols=\"60\" rows=\"10\"></textarea><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddSubCat\">"
	    ."<input type=\"submit\" value=\""._ADD."\"><br>"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Add a New Download to Database

    $result = sql_query("select cid, title from ".$prefix."_downloads_categories", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font class=\"content\"><b>"._ADDNEWDOWNLOAD."</b><br><br>"
	    .""._DOWNLOADNAME.": <input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\"><br>"
	    .""._FILEURL.": <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"100\" value=\"http://\"><br>";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by title", $dbi);
	echo ""._CATEGORY.": <select name=\"cat\">";
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
	    if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\">$ctitle2</option>";
	}
	echo "</select><br><br><br>"
	    .""._DESCRIPTION255."<br><textarea name=\"description\" cols=\"60\" rows=\"5\"></textarea><br><br><br>"
	    .""._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\"><br><br>"
	    .""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\"><br><br>"
	    .""._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\"> ("._INBYTES.")<br><br>"
	    .""._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\"><br><br>"
	    .""._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"30\" maxlength=\"200\" value=\"http://\"><br><br>"
	    .""._HITS.": <input type=\"text\" name=\"hits\" size=\"12\" maxlength=\"11\"><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsAddDownload\">"
    	    ."<input type=\"hidden\" name=\"new\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"lid\" value=\"0\">"
	    ."<center><input type=\"submit\" value=\""._ADDURL."\"><br>"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Modify Category

    $result = sql_query("select * from ".$prefix."_downloads_categories", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font class=\"content\"><b>"._MODCATEGORY."</b></font><br><br>";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by title", $dbi);
	echo ""._CATEGORY.": <select name=\"cat\">";
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
	    if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\">$ctitle2</option>";
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCat\">"
	    ."<input type=\"submit\" value=\""._MODIFY."\">"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Modify Downloads

    $result = sql_query("select * from ".$prefix."_downloads_downloads", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
    OpenTable();
    echo "<form method=\"post\" action=\"admin.php\">"
	."<font class=\"content\"><b>"._MODDOWNLOAD."</b><br><br>"
	.""._DOWNLOADID.": <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"op\" value=\"DownloadsModDownload\">"
	."<input type=\"submit\" value=\""._MODIFY."\">"
	."</form>";
    CloseTable();
    echo "<br>";
    } else {
    }

// Transfer Categories

    $result = sql_query("select * from ".$prefix."_downloads_downloads", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
    OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font class=\"option\"><b>"._EZTRANSFERDOWNLOADS."</b></font><br><br>"
	    .""._CATEGORY.": "
	    ."<select name=\"cidfrom\">";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by parentid,title", $dbi);
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
		if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\">$ctitle2</option>";
	}
	echo "</select><br>"
	    .""._IN."&nbsp;"._CATEGORY.": ";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by parentid,title", $dbi);
	echo "<select name=\"cidto\">";
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
		if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\">$ctitle2</option>";
	}
	echo "</select><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsTransfer\">"
	    ."<input type=\"submit\" value=\""._EZTRANSFER."\"><br>"
	    ."</form>";
    CloseTable();
    echo "<br>";
    } else {
    }

    include ("footer.php");
}

function DownloadsTransfer($cidfrom,$cidto) {
    global $prefix, $dbi;
    sql_query("update ".$prefix."_downloads_downloads set cid=$cidto where cid='$cidfrom'", $dbi);
    Header("Location: admin.php?op=downloads");
}

function DownloadsModDownload($lid) {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    global $anonymous;
    $result = sql_query("select cid, sid, title, url, description, name, email, hits, filesize, version, homepage from ".$prefix."_downloads_downloads where lid='$lid'", $dbi);
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>"._MODDOWNLOAD."</b></font></center><br><br>";
    while(list($cid, $sid, $title, $url, $description, $name, $email, $hits, $filesize, $version, $homepage) = sql_fetch_row($result, $dbi)) {
    	$title = stripslashes($title); $description = stripslashes($description);
    	echo "<form action=admin.php method=post>"
	    .""._DOWNLOADID.": <b>$lid</b><br>"
	    .""._PAGETITLE.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br>"
	    .""._PAGEURL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\">&nbsp;[ <a href=\"$url\">"._CHECK."</a> ]<br>"
	    .""._DESCRIPTION.":<br><textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea><br>"
	    .""._AUTHORNAME.": <input type=\"text\" name=\"name\" size=\"50\" maxlength=\"100\" value=\"$name\"><br>"
	    .""._AUTHOREMAIL.": <input type=\"text\" name=\"email\" size=\"50\" maxlength=\"100\" value=\"$email\"><br>"
	    .""._FILESIZE.": <input type=\"text\" name=\"filesize\" size=\"12\" maxlength=\"11\" value=\"$filesize\"><br>"
	    .""._VERSION.": <input type=\"text\" name=\"version\" size=\"11\" maxlength=\"10\" value=\"$version\"><br>"
	    .""._HOMEPAGE.": <input type=\"text\" name=\"homepage\" size=\"50\" maxlength=\"200\" value=\"$homepage\">&nbsp;[ <a href=\"$homepage\">"._VISIT."</a> ]<br>"
	    .""._HITS.": <input type=\"text\" name=\"hits\" value=\"$hits\" size=\"12\" maxlength=\"11\"><br>";
	$result2=sql_query("select cid, title from ".$prefix."_downloads_categories order by title", $dbi);
	echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">"
	    .""._CATEGORY.": <select name=\"cat\">";
	$result2=sql_query("select cid, title, parentid from ".$prefix."_downloads_categories order by title", $dbi);
	while(list($cid2, $ctitle2, $parentid2) = sql_fetch_row($result2, $dbi)) {
		if ($cid2==$cid) {
			$sel = "selected";
		} else {
			$sel = "";
		}
		if ($parentid2!=0) $ctitle2=getparent($parentid2,$ctitle2);
	    echo "<option value=\"$cid2\" $sel>$ctitle2</option>";
	}
    
    echo "</select>"
	."<input type=\"hidden\" name=\"op\" value=\"DownloadsModDownloadS\">"
	."<input type=\"submit\" value=\""._MODIFY."\"> [ <a href=\"admin.php?op=DownloadsDelDownload&amp;lid=$lid\">"._DELETE."</a> ]</form><br>";
    CloseTable();
    echo "<br>";    
    /* Modify or Add Editorial */
        
        $resulted2 = sql_query("select adminid, editorialtimestamp, editorialtext, editorialtitle from ".$prefix."_downloads_editorials where downloadid='$lid'", $dbi);
        $recordexist = sql_num_rows($resulted2, $dbi);
	OpenTable();
    /* if returns 'bad query' status 0 (add editorial) */
    	if ($recordexist == 0) {
    	    echo "<center><font class=\"content\"><b>"._ADDEDITORIAL."</b></font></center><br><br>"
    		."<form action=\"admin.php\" method=\"post\">"
    		."<input type=\"hidden\" name=\"downloadid\" value=\"$lid\">"
    		.""._EDITORIALTITLE.":<br><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\"><br>"
    		.""._EDITORIALTEXT.":<br><textarea name=\"editorialtext\" cols=\"60\" rows=\"10\">$editorialtext</textarea><br>"
        	."</select><input type=\"hidden\" name=\"op\" value=\"DownloadsAddEditorial\"><input type=\"submit\" value=\"Add\">";
        } else {
    /* if returns 'cool' then status 1 (modify editorial) */
        	while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = sql_fetch_row($resulted2, $dbi)) {
        	$editorialtitle = stripslashes($editorialtitle); $editorialtext = stripslashes($editorialtext);
    		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $editorialtimestamp, $editorialtime);
		$editorialtime = strftime("%F",mktime($editorialtime[4],$editorialtime[5],$editorialtime[6],$editorialtime[2],$editorialtime[3],$editorialtime[1]));
		$date_array = explode("-", $editorialtime); 
		$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
       		$formatted_date = date("F j, Y", $timestamp);         	
        	echo "<center><font class=\"content\"><b>Modify Editorial</b></font></center><br><br>"
        	    ."<form action=\"admin.php\" method=\"post\">"
        	    .""._AUTHOR.": $adminid<br>"
        	    .""._DATEWRITTEN.": $formatted_date<br><br>"
        	    ."<input type=\"hidden\" name=\"downloadid\" value=\"$lid\">"
        	    .""._EDITORIALTITLE.":<br><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\"><br>"
        	    .""._EDITORIALTEXT.":<br><textarea name=\"editorialtext\" cols=\"60\" rows=\"10\">$editorialtext</textarea><br>"
            	    ."</select><input type=\"hidden\" name=\"op\" value=\"DownloadsModEditorial\"><input type=\"submit\" value=\""._MODIFY."\"> [ <a href=\"admin.php?op=DownloadsDelEditorial&amp;downloadid=$lid\">"._DELETE."</a> ]";
                }
    	} 
    CloseTable();
    echo "<br>";
    OpenTable();
    /* Show Comments */
    $result5=sql_query("SELECT ratingdbid, ratinguser, ratingcomments, ratingtimestamp FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid' AND ratingcomments != '' ORDER BY ratingtimestamp DESC", $dbi);
    $totalcomments = sql_num_rows($result5, $dbi);
    echo "<table valign=top width=100%>";
    echo "<tr><td colspan=7><b>Download Comments (total comments: $totalcomments)</b><br><br></td></tr>";    
    echo "<tr><td width=20 colspan=1><b>User  </b></td><td colspan=5><b>Comment  </b></td><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalcomments == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Comments<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $ratinguser, $ratingcomments, $ratingtimestamp)=sql_fetch_row($result5, $dbi)) {
    	$ratingcomments = stripslashes($ratingcomments);
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
            $formatted_date = date("F j, Y", $timestamp);
            echo "<tr><td valign=top bgcolor=$colorswitch>$ratinguser</td><td valign=top colspan=5 bgcolor=$colorswitch>$ratingcomments</td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=DownloadsDelComment&lid=$lid&rid=$ratingdbid>X</a></b></center></td><br></tr>";                       
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }    

    	    
    // Show Registered Users Votes
    $result5=sql_query("SELECT ratingdbid, ratinguser, rating, ratinghostname, ratingtimestamp FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid' AND ratinguser != 'outside' AND ratinguser != '$anonymous' ORDER BY ratingtimestamp DESC", $dbi);
    $totalvotes = sql_num_rows($result5, $dbi);
    echo "<tr><td colspan=7><br><br><b>Registered User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td><b>User  </b></td><td><b>IP Address  </b></td><td><b>Rating  </b></td><td><b>User AVG Rating  </b></td><td><b>Total Ratings  </b></td><td><b>Date  </b></td></font></b><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Registered User Votes<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=sql_fetch_row($result5, $dbi)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
            $formatted_date = date("F j, Y", $timestamp); 
    	
    	//Individual user information
    	$result2=sql_query("SELECT rating FROM ".$prefix."_downloads_votedata WHERE ratinguser = '$ratinguser'", $dbi);
            $usertotalcomments = sql_num_rows($result2, $dbi);
            $useravgrating = 0;
            while(list($rating2)=sql_fetch_row($result2, $dbi))	$useravgrating = $useravgrating + $rating2;
            $useravgrating = $useravgrating / $usertotalcomments;
            $useravgrating = number_format($useravgrating, 1);
            echo "<tr><td bgcolor=$colorswitch>$ratinguser</td><td bgcolor=$colorswitch>$ratinghostname</td><td bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$useravgrating</td><td bgcolor=$colorswitch>$usertotalcomments</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=DownloadsDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }    
        
    // Show Unregistered Users Votes
    $result5=sql_query("SELECT ratingdbid, rating, ratinghostname, ratingtimestamp FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid' AND ratinguser = '$anonymous' ORDER BY ratingtimestamp DESC", $dbi);
    $totalvotes = sql_num_rows($result5, $dbi);
    echo "<tr><td colspan=7><b><br><br>Unregistered User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td colspan=2><b>IP Address  </b></td><td colspan=3><b>Rating  </b></td><td><b>Date  </b></font></td><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Unregistered User Votes<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=sql_fetch_row($result5, $dbi)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
        $formatted_date = date("F j, Y", $timestamp); 
        echo "<td colspan=2 bgcolor=$colorswitch>$ratinghostname</td><td colspan=3 bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=DownloadsDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";           
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }  
        
    // Show Outside Users Votes
    $result5=sql_query("SELECT ratingdbid, rating, ratinghostname, ratingtimestamp FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid' AND ratinguser = 'outside' ORDER BY ratingtimestamp DESC", $dbi);
    $totalvotes = sql_num_rows($result5, $dbi);
    echo "<tr><td colspan=7><b><br><br>Outside User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td colspan=2><b>IP Address  </b></td><td colspan=3><b>Rating  </b></td><td><b>Date  </b></td></font></b><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Votes from Outside $sitename<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd"; 
    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=sql_fetch_row($result5, $dbi)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
        $formatted_date = date("F j, Y", $timestamp); 
        echo "<tr><td colspan=2 bgcolor=$colorswitch>$ratinghostname</td><td colspan=3 bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=DownloadsDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";           
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";
        }            

    echo "<tr><td colspan=6><br></td></tr>";	    
    echo "</table>";
    
    }
    echo "</form>";
    CloseTable();
    echo "<br>";
    include ("footer.php");
}

function DownloadsDelComment($lid, $rid) {
    global $prefix, $dbi;
    sql_query("UPDATE ".$prefix."_downloads_votedata SET ratingcomments='' WHERE ratingdbid='$rid'", $dbi);
    sql_query("UPDATE ".$prefix."_downloads_downloads SET totalcomments = (totalcomments - 1) WHERE lid='$lid'", $dbi);
    Header("Location: admin.php?op=DownloadsModDownload&lid=$lid");
    
}

function DownloadsDelVote($lid, $rid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_votedata where ratingdbid='$rid'", $dbi);
    $voteresult = sql_query("select rating, ratinguser, ratingcomments FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid'", $dbi);
    $totalvotesDB = sql_num_rows($voteresult, $dbi);
    include ("voteinclude.php");
    sql_query("UPDATE ".$prefix."_downloads_downloads SET downloadratingsummary=$finalrating,totalvotes=$totalvotesDB,totalcomments=$truecomments WHERE lid='$lid'", $dbi);
    Header("Location: admin.php?op=DownloadsModDownload&lid=$lid");
}

function DownloadsListBrokenDownloads() {
    global $bgcolor1, $bgcolor2, $prefix, $dbi, $user_prefix;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = sql_query("select requestid, lid, modifysubmitter from ".$prefix."_downloads_modrequest where brokendownload='1' order by requestid", $dbi);
    $totalbrokendownloads = sql_num_rows($result, $dbi);
    echo "<center><font class=\"content\"><b>"._DUSERREPBROKEN." ($totalbrokendownloads)</b></font></center><br><br><center>"
	.""._DIGNOREINFO."<br>"
	.""._DDELETEINFO."</center><br><br><br>"
	."<table align=\"center\" width=\"450\">";
    if ($totalbrokendownloads==0) {
	echo "<center><font class=\"content\">"._DNOREPORTEDBROKEN."</font></center><br><br><br>";
    } else {
        $colorswitch = $bgcolor2;
        echo "<tr>"
            ."<td><b>"._DOWNLOAD."</b></td>"
            ."<td><b>"._SUBMITTER."</b></td>"
            ."<td><b>"._DOWNLOADOWNER."</b></td>"
            ."<td><b>"._IGNORE."</b></td>"
            ."<td><b>"._DELETE."</b></td>"
            ."<td><b>"._EDIT."</b></td>"
    	    ."</tr>";
        while(list($requestid, $lid, $modifysubmitter)=sql_fetch_row($result, $dbi)) {
	    $result2 = sql_query("select title, url, submitter from ".$prefix."_downloads_downloads where lid='$lid'", $dbi);
	    if ($modifysubmitter != '$anonymous') {
		$result3 = sql_query("select user_email from ".$user_prefix."_users where username='$modifysubmitter'", $dbi);
		list($email)=sql_fetch_row($result3, $dbi);
	    }
    	    list($title, $url, $owner)=sql_fetch_row($result2, $dbi);
    	    $result4 = sql_query("select user_email from ".$user_prefix."_users where username='$owner'", $dbi);
    	    list($owneremail)=sql_fetch_row($result4, $dbi);
    	    echo "<tr>"
    		."<td bgcolor=\"$colorswitch\"><a href=\"$url\">$title</a>"
    		."</td>";
    	    if ($email=='') {
		echo "<td bgcolor=\"$colorswitch\">$modifysubmitter";
	    } else {
		echo "<td bgcolor=\"$colorswitch\"><a href=\"mailto:$email\">$modifysubmitter</a>";
	    }
    	    echo "</td>";
    	    if ($owneremail=='') {
		echo "<td bgcolor=\"$colorswitch\">$owner";
	    } else {
		echo "<td bgcolor=\"$colorswitch\"><a href=\"mailto:$owneremail\">$owner</a>";
	    }
    	    echo "</td>"
    		."<td bgcolor=\"$colorswitch\"><center><a href=\"admin.php?op=DownloadsIgnoreBrokenDownloads&amp;lid=$lid\">X</a></center>"
    		."</td>"
    		."<td bgcolor=\"$colorswitch\"><center><a href=\"admin.php?op=DownloadsDelBrokenDownloads&amp;lid=$lid\">X</a></center>"
    		."</td>"
    		."<td bgcolor=\"$colorswitch\"><center><a href=\"admin.php?op=DownloadsModDownload&amp;lid=$lid\">X</a></center>"
    		."</td>"
		."</tr>";
    	    if ($colorswitch == $bgcolor2) {
		$colorswitch = $bgcolor1;
       	    } else {
		$colorswitch = $bgcolor2;
	    }
    	}
    }
    echo "</table>";
    CloseTable();
    include ("footer.php");
}

function DownloadsDelBrokenDownloads($lid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_modrequest where lid='$lid'", $dbi);
    sql_query("delete from ".$prefix."_downloads_downloads where lid='$lid'", $dbi);
    Header("Location: admin.php?op=DownloadsListBrokenDownloads");
}

function DownloadsIgnoreBrokenDownloads($lid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_modrequest where lid='$lid' and brokendownload='1'", $dbi);
    Header("Location: admin.php?op=DownloadsListBrokenDownloads");
}

function DownloadsListModRequests() {
    global $bgcolor2, $prefix, $dbi, $user_prefix;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = sql_query("select requestid, lid, cid, sid, title, url, description, modifysubmitter, name, email, filesize, version, homepage from ".$prefix."_downloads_modrequest where brokendownload='0' order by requestid", $dbi);
    $totalmodrequests = sql_num_rows($result, $dbi);
    echo "<center><font class=\"content\"><b>"._DUSERMODREQUEST." ($totalmodrequests)</b></font></center><br><br><br>";
    echo "<table width=\"95%\"><tr><td>";
    while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter, $name, $email, $filesize, $version, $homepage)=sql_fetch_row($result, $dbi)) {
	$result2 = sql_query("select cid, sid, title, url, description, name, email, submitter, filesize, version, homepage from ".$prefix."_downloads_downloads where lid='$lid'", $dbi);
	list($origcid, $origsid, $origtitle, $origurl, $origdescription, $origname, $origemail, $owner, $origfilesize, $origversion, $orighomepage)=sql_fetch_row($result2, $dbi);
	$result3 = sql_query("select title from ".$prefix."_downloads_categories where cid='$cid'", $dbi);
	$result5 = sql_query("select title from ".$prefix."_downloads_categories where cid='$origcid'", $dbi);
	$result7 = sql_query("select user_email from ".$user_prefix."_users where username='$modifysubmitter'", $dbi);
	$result8 = sql_query("select user_email from ".$user_prefix."_users where username='$owner'", $dbi);
	list($cidtitle)=sql_fetch_row($result3, $dbi);
	list($origcidtitle)=sql_fetch_row($result5, $dbi);
	list($modifysubmitteremail)=sql_fetch_row($result7, $dbi);
	list($owneremail)=sql_fetch_row($result8, $dbi);
    	$title = stripslashes($title);
    	$description = stripslashes($description);
    	if ($owner=="") {
	    $owner="administration";
	}
    	if ($origsidtitle=="") {
	    $origsidtitle= "-----";
	}
    	if ($sidtitle=="") {
	    $sidtitle= "-----";
	}
    	echo "<table border=\"1\" bordercolor=\"black\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\" width=\"450\">"
    	    ."<tr>"
    	    ."<td>"
    	    ."<table width=\"100%\" bgcolor=\"$bgcolor2\">"
    	    ."<tr>"
    	    ."<td valign=\"top\" width=\"45%\"><b>"._ORIGINAL."</b></td>"
    	    ."<td rowspan=\"10\" valign=\"top\" align=\"left\"><font class=\"tiny\"><br>"._DESCRIPTION.":<br>$origdescription</font></td>"
    	    ."</tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._TITLE.": $origtitle</td></tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._URL.": <a href=\"$origurl\">$origurl</a></td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._CATEGORY.": $origcidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._SUBCATEGORY.": $origsidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._AUTHORNAME.": $origname</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._AUTHOREMAIL.": $origemail</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._FILESIZE.": $origfilesize</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._VERSION.": $origversion</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._HOMEPAGE.": <a href=\"$orighomepage\" target=\"new\">$orighomepage</a></td></tr>"
    	    ."</table>"
    	    ."</td>"
    	    ."</tr>"
    	    ."<tr>"
    	    ."<td>"
    	    ."<table width=\"100%\">"
    	    ."<tr>"
    	    ."<td valign=\"top\" width=\"45%\"><b>"._PROPOSED."</b></td>"
    	    ."<td rowspan=\"10\" valign=\"top\" align=\"left\"><font class=\"tiny\"><br>"._DESCRIPTION.":<br>$description</font></td>"
    	    ."</tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._TITLE.": $title</td></tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._URL.": <a href=\"$url\">$url</a></td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._CATEGORY.": $cidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._SUBCATEGORY.": $sidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._AUTHORNAME.": $name</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._AUTHOREMAIL.": $email</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._FILESIZE.": $filesize</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._VERSION.": $version</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font class=\"tiny\">"._HOMEPAGE.": <a href=\"$homepage\" target=\"new\">$homepage</a></td></tr>"
    	    ."</table>"
    	    ."</td>"
    	    ."</tr>"
    	    ."</table>"
    	    ."<table align=\"center\" width=\"450\">"
    	    ."<tr>";
    	if ($modifysubmitteremail=="") {
	    echo "<td align=\"left\"><font class=\"tiny\">"._SUBMITTER.":  $modifysubmitter</font></td>";
	} else {
	    echo "<td align=\"left\"><font class=\"tiny\">"._SUBMITTER.":  <a href=\"mailto:$modifysubmitteremail\">$modifysubmitter</a></font></td>";
	}
    	if ($owneremail=="") {
	    echo "<td align=\"center\"><font class=\"tiny\">"._OWNER.":  $owner</font></td>";
	} else {
	    echo "<td align=\"center\"><font class=\"tiny\">"._OWNER.": <a href=\"mailto:$owneremail\">$owner</a></font></td>";
	}
    	echo "<td align=\"right\"><font class=\"tiny\">( <a href=\"admin.php?op=DownloadsChangeModRequests&amp;requestid=$requestid\">"._ACCEPT."</a> / <a href=\"admin.php?op=DownloadsChangeIgnoreRequests&amp;requestid=$requestid\">"._IGNORE."</a> )</font></td></tr></table><br><br>";
    }
    if ($totalmodrequests == 0) {
	echo "<center>"._NOMODREQUESTS."<br><br>"
	    .""._GOBACK."<br><br></center>";
    }
    echo "</td></tr></table>";
    CloseTable();
    include ("footer.php");
}

function DownloadsChangeModRequests($requestid) {
    global $prefix, $dbi;
    $result = sql_query("select requestid, lid, cid, sid, title, url, description, name, email, filesize, version, homepage from ".$prefix."_downloads_modrequest where requestid='$requestid'", $dbi);
    while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $name, $email, $filesize, $version, $homepage)=sql_fetch_row($result, $dbi)) {
	$title = stripslashes($title);
    	$description = stripslashes($description);
    	sql_query("UPDATE ".$prefix."_downloads_downloads SET cid=$cid, sid=$sid, title='$title', url='$url', description='$description', name='$name', email='$email', filesize='$filesize', version='$version', homepage='$homepage' WHERE lid='$lid'", $dbi);
	sql_query("delete from ".$prefix."_downloads_modrequest where requestid='$requestid'", $dbi);
    }
    Header("Location: admin.php?op=DownloadsListModRequests");
}

function DownloadsChangeIgnoreRequests($requestid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_modrequest where requestid='$requestid'", $dbi);
    Header("Location: admin.php?op=DownloadsListModRequests");
}

function DownloadsCleanVotes() {
    global $prefix, $dbi;
    $totalvoteresult = sql_query("select distinct ratinglid FROM ".$prefix."_downloads_votedata", $dbi);
    while(list($lid)=sql_fetch_row($totalvoteresult, $dbi)) {
	$voteresult = sql_query("select rating, ratinguser, ratingcomments FROM ".$prefix."_downloads_votedata WHERE ratinglid='$lid'", $dbi);
	$totalvotesDB = sql_num_rows($voteresult, $dbi);
	include ("voteinclude.php");
        sql_query("UPDATE ".$prefix."_downloads_downloads SET downloadratingsummary=$finalrating,totalvotes=$totalvotesDB,totalcomments=$truecomments WHERE lid='$lid'", $dbi);
    }
    Header("Location: admin.php?op=downloads");
}

function DownloadsModDownloadS($lid, $title, $url, $description, $name, $email, $hits, $cat, $filesize, $version, $homepage) {
    global $prefix, $dbi;
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
        $cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    sql_query("update ".$prefix."_downloads_downloads set cid='$cat[0]', sid='$cat[1]', title='$title', url='$url', description='$description', name='$name', email='$email', hits='$hits', filesize='$filesize', version='$version', homepage='$homepage' where lid='$lid'", $dbi);
    Header("Location: admin.php?op=downloads");
}

function DownloadsDelDownload($lid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_downloads where lid='$lid'", $dbi);
    Header("Location: admin.php?op=downloads");
}

function DownloadsModCat($cat) {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
        $cat[1] = 0;
    }
    OpenTable();
    echo "<center><font class=\"content\"><b>"._MODCATEGORY."</b></font></center><br><br>";
    if ($cat[1]==0) {
	$result=sql_query("select title, cdescription from ".$prefix."_downloads_categories where cid='$cat[0]'", $dbi);
	list($title,$cdescription) = sql_fetch_row($result, $dbi);
	$cdescription = stripslashes($cdescription);
	echo "<form action=\"admin.php\" method=\"get\">"
	    .""._NAME.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"51\" maxlength=\"50\"><br>"
	    .""._DESCRIPTION.":<br><textarea name=\"cdescription\" cols=\"60\" rows=\"10\">$cdescription</textarea><br>"
	    ."<input type=\"hidden\" name=\"sub\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCatS\">"
	    ."<table border=\"0\"><tr><td>"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\"></form></td><td>"
	    ."<form action=\"admin.php\" method=\"get\">"
	    ."<input type=\"hidden\" name=\"sub\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsDelCat\">"
	    ."<input type=\"submit\" value=\""._DELETE."\"></form></td></tr></table>";
    } else {
	$result=sql_query("select title from ".$prefix."_downloads_categories where cid='$cat[0]'", $dbi);
	list($ctitle) = sql_fetch_row($result, $dbi);
	$result2=sql_query("select title from ".$prefix."_downloads_subcategories where sid='$cat[1]'", $dbi);
	list($stitle) = sql_fetch_row($result2, $dbi);
	echo "<form action=\"admin.php\" method=\"get\">"
	    .""._CATEGORY.": $ctitle<br>"
	    .""._SUBCATEGORY.": <input type=\"text\" name=\"title\" value=\"$stitle\" size=\"51\" maxlength=\"50\"><br>"
	    ."<input type=\"hidden\" name=\"sub\" value=\"1\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsModCatS\">"
	    ."<table border=\"0\"><tr><td>"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\"></form></td><td>"
	    ."<form action=\"admin.php\" method=\"get\">"
	    ."<input type=\"hidden\" name=\"sub\" value=\"1\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"DownloadsDelCat\">"
	    ."<input type=\"submit\" value=\""._DELETE."\"></form></td></tr></table>";
    }
    CloseTable();
    include("footer.php");
}

function DownloadsModCatS($cid, $sid, $sub, $title, $cdescription) {
    global $prefix, $dbi;
    if ($sub==0) {
	sql_query("update ".$prefix."_downloads_categories set title='$title', cdescription='$cdescription' where cid='$cid'", $dbi);
    } else {
	sql_query("update ".$prefix."_downloads_subcategories set title='$title' where sid='$sid'", $dbi);
    }
    Header("Location: admin.php?op=downloads");
}

function DownloadsDelCat($cid, $sid, $sub, $ok=0) {
    global $prefix, $dbi;
    if($ok==1) {
	if ($sub>0) {
    	sql_query("delete from ".$prefix."_downloads_categories where cid='$cid'", $dbi);
	    sql_query("delete from ".$prefix."_downloads_downloads where cid='$cid'", $dbi);
	} else {
	    sql_query("delete from ".$prefix."_downloads_downloads where cid='$cid'", $dbi);
		// suppression des liens de catégories filles
    	$result2 = sql_query("select cid from ".$prefix."_downloads_categories where parentid='$cid'", $dbi);
    	while(list($cid2) = sql_fetch_row($result2, $dbi)) {
			sql_query("delete from ".$prefix."_downloads_downloads where cid='$cid2'", $dbi);
		}
	    sql_query("delete from ".$prefix."_downloads_categories where parentid='$cid'", $dbi);
	    sql_query("delete from ".$prefix."_downloads_categories where cid='$cid'", $dbi);
	}
	Header("Location: admin.php?op=downloads");    
    } else {
	$result = sql_query("select * from ".$prefix."_downloads_categories where parentid='$cid'", $dbi);
	$nbsubcat = sql_num_rows($result, $dbi);

	$result2 = sql_query("select cid from ".$prefix."_downloads_categories where parentid='$cid'", $dbi);
	while(list($cid2) = sql_fetch_row($result2, $dbi)) {
		$result3 = sql_query("select * from ".$prefix."_downloads_downloads where cid='$cid2'", $dbi);
		$nblink += sql_num_rows($result3, $dbi);
	}

	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<br><center><font class=\"option\">";
	echo "<b>"._EZTHEREIS." $nbsubcat "._EZSUBCAT." "._EZATTACHEDTOCAT."</b><br>";
	echo "<b>"._EZTHEREIS." $nblink "._downloads." "._EZATTACHEDTOCAT."</b><br>";
	echo "<b>"._DELEZDOWNLOADSCATWARNING."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=DownloadsDelCat&amp;cid=$cid&amp;sid=$sid&amp;sub=$sub&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=Links\">"._NO."</a> ]<br><br>";
	CloseTable();
	include("footer.php");	
}

function DownloadsDelNew($lid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_newdownload where lid='$lid'", $dbi);
    Header("Location: admin.php?op=downloads");
}

function DownloadsAddCat($title, $cdescription) {
    global $prefix, $dbi;
    $result = sql_query("select cid from ".$prefix."_downloads_categories where title='$title'", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<br><center><font class=\"content\">"
	    ."<b>"._ERRORTHECATEGORY." $title "._ALREADYEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    } else {
	sql_query("insert into ".$prefix."_downloads_categories values (NULL, '$title', '$cdescription', '$parentid')", $dbi);
	Header("Location: admin.php?op=downloads");
    }
}

function DownloadsAddSubCat($cid, $title, $cdescription) {
    global $prefix, $dbi;
    $result = sql_query("select cid from ".$prefix."_downloads_categories where title='$title' AND cid='$cid'", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<br><center>";
	echo "<font class=\"content\">"
	    ."<b>"._ERRORTHESUBCATEGORY." $title "._ALREADYEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	include("footer.php");
    } else {
	sql_query("insert into ".$prefix."_downloads_categories values (NULL, '$title', '$cdescription', '$cid')", $dbi);
	Header("Location: admin.php?op=downloads");
    }
}

function DownloadsAddEditorial($downloadid, $editorialtitle, $editorialtext) {
    global $aid, $prefix, $dbi;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    sql_query("insert into ".$prefix."_downloads_editorials values ($downloadid, '$aid', now(), '$editorialtext', '$editorialtitle')", $dbi);
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><br>"
	."<font class=option>"
	.""._EDITORIALADDED."<br><br>"
	."[ <a href=\"admin.php?op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br><br>";
    echo "$downloadid  $adminid, $editorialtitle, $editorialtext";
    CloseTable();
    include("footer.php");
}

function DownloadsModEditorial($downloadid, $editorialtitle, $editorialtext) {
    global $prefix, $dbi;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    sql_query("update ".$prefix."_downloads_editorials set editorialtext='$editorialtext', editorialtitle='$editorialtitle' where downloadid='$downloadid'", $dbi);
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<br><center>"
	."<font class=\"content\">"
	.""._EDITORIALMODIFIED."<br><br>"
	."[ <a href=\"admin.php?op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br><br>";
    CloseTable();
    include("footer.php");    
}

function DownloadsDelEditorial($downloadid) {
    global $prefix, $dbi;
    sql_query("delete from ".$prefix."_downloads_editorials where downloadid='$downloadid'", $dbi);
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<br><center>"
	."<font class=\"content\">"
	.""._EDITORIALREMOVED."<br><br>"
	."[ <a href=\"admin.php?op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]<br><br>";
    CloseTable();
    include("footer.php");
}

function DownloadsDownloadCheck() {
    global $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"content\"><b>"._DOWNLOADVALIDATION."</b></font></center><br>"
	."<table width=\"100%\" align=\"center\"><tr><td colspan=\"2\" align=\"center\">"
	."<a href=\"admin.php?op=DownloadsValidate&amp;cid=0&amp;sid=0\">"._CHECKALLDOWNLOADS."</a><br><br></td></tr>"
	."<tr><td valign=\"top\"><center><b>"._CHECKCATEGORIES."</b><br>"._INCLUDESUBCATEGORIES."<br><br><font class=\"tiny\">";
    $result = sql_query("select cid, title from ".$prefix."_downloads_categories order by title", $dbi);
    while(list($cid, $title) = sql_fetch_row($result, $dbi)) {
        $transfertitle = str_replace (" ", "_", $title);
    	echo "<a href=\"admin.php?op=DownloadsValidate&amp;cid=$cid&amp;sid=0&amp;ttitle=$transfertitle\">$title</a><br>";
    }
    echo "</font></center></td></tr></table>";
    CloseTable();
    include ("footer.php");
}

function DownloadsValidate($cid, $sid, $ttitle) {
    global $bgcolor2, $prefix, $dbi;
    include ("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $transfertitle = str_replace ("_", "", $ttitle);
    /* Check ALL Downloads */
    echo "<table width=100% border=0>";
    if ($cid==0 && $sid==0) {
	echo "<tr><td colspan=\"3\"><center><b>"._CHECKALLDOWNLOADS."</b><br>"._BEPATIENT."</center><br><br></td></tr>";
	$result = sql_query("select lid, title, url, name, email, submitter from ".$prefix."_downloads_downloads order by title", $dbi);
    }   	
    /* Check Categories & Subcategories */
    if ($cid!=0 && $sid==0) {
	echo "<tr><td colspan=\"3\"><center><b>"._VALIDATINGCAT.": $transfertitle</b><br>"._BEPATIENT."</center><br><br></td></tr>";
	$result = sql_query("select lid, title, url, name, email, submitter from ".$prefix."_downloads_downloads where cid='$cid' order by title", $dbi);
    }
    /* Check Only Subcategory */
    if ($cid==0 && $sid!=0) {
   	echo "<tr><td colspan=\"3\"><center><b>"._VALIDATINGSUBCAT.": $transfertitle</b><br>"._BEPATIENT."</center><br><br></td></tr>";
   	$result = sql_query("select lid, title, url, name, email, submitter from ".$prefix."_downloads_downloads where sid='$sid' order by title", $dbi);
    }
    echo "<tr><td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._STATUS."</b></td><td bgcolor=\"$bgcolor2\" width=\"100%\"><b>"._DOWNLOADTITLE."</b></td><td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td></tr>";
    while(list($lid, $title, $url, $name, $email, $submitter) = sql_fetch_row($result, $dbi)) {
	$vurl = parse_url($url);
	$fp = fsockopen($vurl['host'], 80, $errno, $errstr, 15);
	if (!$fp){ 
	    echo "<tr><td align=\"center\"><b>&nbsp;&nbsp;"._FAILED."&nbsp;&nbsp;</b></td>"
		."<td>&nbsp;&nbsp;<a href=\"$url\" target=\"new\">$title</a>&nbsp;&nbsp;</td>"
		."<td align=\"center\"><font class=\"content\">&nbsp;&nbsp;[ <a href=\"admin.php?op=DownloadsModDownload&amp;lid=$lid\">"._EDIT."</a> | <a href=\"admin.php?op=DownloadsDelDownload&amp;lid=$lid\">"._DELETE."</a> ]&nbsp;&nbsp;</font>"
		."</td></tr>";
	}		
	if ($fp){ 
	    echo "<tr><td align=\"center\">&nbsp;&nbsp;"._OK."&nbsp;&nbsp;</td>"
		."<td>&nbsp;&nbsp;<a href=\"$url\" target=\"new\">$title</a>&nbsp;&nbsp;</td>"
		."<td align=\"center\"><font class=\"content\">&nbsp;&nbsp;"._NONE."&nbsp;&nbsp;</font>"
		."</td></tr>";
	} 
    }
    echo "</table>";
    CloseTable();   	
    include ("footer.php");
}

function DownloadsAddDownload($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter, $filesize, $version, $homepage, $hits) {
    global $prefix, $dbi;
    $result = sql_query("select url from ".$prefix."_downloads_downloads where url='$url'", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font class=\"content\">"
	    ."<b>"._ERRORURLEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    } else {
/* Check if Title exist */
    if ($title=="") {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font class=\"content\">"
	    ."<b>"._ERRORNOTITLE."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
/* Check if URL exist */
    if ($url=="") {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font class=\"content\">"
	    ."<b>"._ERRORNOURL."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
// Check if Description exist
    if ($description=="") {
	include("header.php");
	GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._WEBDOWNLOADSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font class=\"content\">"
	    ."<b>"._ERRORNODESCRIPTION."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
	$cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    sql_query("insert into ".$prefix."_downloads_downloads values (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', now(), '$name', '$email', '$hits','$submitter',0,0,0, '$filesize', '$version', '$homepage')", $dbi);
    global $nukeurl, $sitename;
    include("header.php");
    GraphicAdmin();
    OpenTable();
    echo "<br><center>";
    echo "<font class=\"content\">";
    echo ""._NEWDOWNLOADADDED."<br><br>";
    echo "[ <a href=\"admin.php?op=downloads\">"._WEBDOWNLOADSADMIN."</a> ]</center><br><br>";
    CloseTable();
    if ($new==1) {
	sql_query("delete from ".$prefix."_downloads_newdownload where lid='$lid'", $dbi);
    }
    include("footer.php");
    }
}

switch ($op) {
			
    case "downloads":
    downloads();
    break;

    case "DownloadsDelNew":
    DownloadsDelNew($lid);
    break;

    case "DownloadsAddCat":
    DownloadsAddCat($title, $cdescription);
    break;

    case "DownloadsAddSubCat":
    DownloadsAddSubCat($cid, $title, $cdescription);
    break;

    case "DownloadsAddDownload":
    DownloadsAddDownload($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter, $filesize, $version, $homepage, $hits);
    break;
			
    case "DownloadsAddEditorial":
    DownloadsAddEditorial($downloadid, $editorialtitle, $editorialtext);
    break;			
			
    case "DownloadsModEditorial":
    DownloadsModEditorial($downloadid, $editorialtitle, $editorialtext);
    break;	
			
    case "DownloadsDownloadCheck":
    DownloadsDownloadCheck();
    break;	
		
    case "DownloadsValidate":
    DownloadsValidate($cid, $sid, $ttitle);
    break;

    case "DownloadsDelEditorial":
    DownloadsDelEditorial($downloadid);
    break;						

    case "DownloadsCleanVotes":
    DownloadsCleanVotes();
    break;	
		
    case "DownloadsListBrokenDownloads":
    DownloadsListBrokenDownloads();
    break;

    case "DownloadsDelBrokenDownloads":
    DownloadsDelBrokenDownloads($lid);
    break;
			
    case "DownloadsIgnoreBrokenDownloads":
    DownloadsIgnoreBrokenDownloads($lid);
    break;			
			
    case "DownloadsListModRequests":
    DownloadsListModRequests();
    break;		
			
    case "DownloadsChangeModRequests":
    DownloadsChangeModRequests($requestid);
    break;	
			
    case "DownloadsChangeIgnoreRequests":
    DownloadsChangeIgnoreRequests($requestid);
    break;
			
    case "DownloadsDelCat":
    DownloadsDelCat($cid, $sid, $sub, $ok);
    break;

    case "DownloadsModCat":
    DownloadsModCat($cat);
    break;

    case "DownloadsModCatS":
    DownloadsModCatS($cid, $sid, $sub, $title, $cdescription);
    break;

    case "DownloadsModDownload":
    DownloadsModDownload($lid);
    break;

    case "DownloadsModDownloadS":
    DownloadsModDownloadS($lid, $title, $url, $description, $name, $email, $hits, $cat, $filesize, $version, $homepage);
    break;

    case "DownloadsDelDownload":
    DownloadsDelDownload($lid);
    break;

    case "DownloadsDelVote":
    DownloadsDelVote($lid, $rid);
    break;			

    case "DownloadsDelComment":
    DownloadsDelComment($lid, $rid);
    break;

    case "DownloadsTransfer":
    DownloadsTransfer($cidfrom,$cidto);
    break;

}
    
} else {
    echo "Access Denied";
}

?>