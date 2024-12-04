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

$result = sql_query("select radminreviews, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($radminreviews, $radminsuper) = sql_fetch_row($result, $dbi);
if (($radminreviews==1) OR ($radminsuper==1)) {

/*********************************************************/
/* REVIEWS Block Functions                               */
/*********************************************************/

function mod_main($title, $description) {
    global $prefix, $dbi;
    $title = stripslashes(FixQuotes($title));
    $description = stripslashes(FixQuotes($description));
    sql_query("update ".$prefix."_reviews_main set title='$title', description='$description'", $dbi);
    Header("Location: admin.php?op=reviews");
}

function reviews() {
    global $prefix, $dbi, $multilingual;
    include ("header.php");
    
    GraphicAdmin();
    OpenTable();
    echo "<center><font class=\"title\"><b>"._REVADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $resultrm = sql_query("select title, description from ".$prefix."_reviews_main", $dbi);
    list($title, $description) = sql_fetch_row($resultrm, $dbi);
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
	."<center>"._REVTITLE."<br>"
	."<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br><br>"
	.""._REVDESC."<br>"
	."<textarea name=\"description\" rows=\"15\" wrap=\"virtual\" cols=\"60\">$description</textarea><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"mod_main\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._REVWAITING."</b></font><br>";
    $result = sql_query("select * from ".$prefix."_reviews_add order by id", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if ($numrows>0) {
	while(list($id, $date, $title, $text, $reviewer, $email, $score, $url, $url_title, $rlanguage) = sql_fetch_row($result, $dbi)) {
	    $title = stripslashes($title);
	    $text = stripslashes($text);
	    echo "<form action=\"admin.php\" method=\"post\">"
		."<hr noshade size=\"1\"><br><table border=\"0\" cellpadding=\"1\" cellspacing=\"2\">"
		."<tr><td><b>"._REVIEWID.":</td><td><b>$id</b></td></tr>"
		."<input type=\"hidden\" name=\"id\" value=\"$id\">"
		."<tr><td>"._DATE.":</td><td><input type=\"text\" name=\"date\" value=\"$date\" size=\"11\" maxlength=\"10\"></td></tr>"
		."<tr><td>"._PRODUCTTITLE.":</td><td><input type=\"text\" name=\"title\" value=\"$title\" size=\"25\" maxlength=\"40\"></td></tr>";
	    if ($multilingual == 1) {
		echo "<tr><td>"._LANGUAGE.":</td><td>"
		    ."<select name=\"rlanguage\">";
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
			if($languageslist[$i]==$rlanguage) echo "selected";
			echo ">".ucfirst($languageslist[$i])."</option>\n";
		    }
		}
		echo "</select></td></tr>";
	    } else {
		echo "<input type=\"hidden\" name=\"rlanguage\" value=\"$language\">";
	    }
	    echo "<tr><td>"._TEXT.":</td><td><TEXTAREA name=\"text\" rows=\"6\" wrap=\"virtual\" cols=\"40\">$text</textarea></td></tr>"
		."<tr><td>"._REVIEWER."</td><td><input type=\"text\" name=\"reviewer\" value=\"$reviewer\" size=\"41\" maxlength=\"40\"></td></tr>"
		."<tr><td>"._EMAIL.":</td><td><input type=\"text\" name=\"email\" value=\"$email\" size=\"41\" maxlength=\"80\"></td></tr>"
		."<tr><td>"._SCORE."</td><td><input type=\"text\" name=\"score\" value=\"$score\" size=\"3\" maxlength=\"2\"></td></tr>";
	    if ($url != "") {
		echo "<tr><td>"._RELATEDLINK.":</td><td><input type=\"text\" name=\"url\" value=\"$url\" size=\"25\" maxlength=\"100\"></td></tr>"
		    ."<tr><td>"._LINKTITLE.":</td><td><input type=\"text\" name=\"url_title\" value=\"$url_title\" size=\"25\" maxlength=\"50\"></td></tr>";
	    }
	    echo "<tr><td>"._IMAGE.":</td><td><input type=\"text\" name=\"cover\" size=\"25\" maxlength=\"100\"><br><i>"._REVIMGINFO."</i></td></tr></table>";
	    echo "<input type=\"hidden\" name=\"op\" value=\"add_review\"><input type=\"submit\" value=\""._ADDREVIEW."\"> - [ <a href=\"admin.php?op=deleteNotice&amp;id=$id&amp;table=".$prefix."_reviews_add&amp;op_back=reviews\">"._DELETE."</a> ]</form>";
	}
    } else {
    	echo "<br><br><i>"._NOREVIEW2ADD."</i><br><br>";
    }
    echo "<a href=\"modules.php?name=Reviews&rop=write_review\">"._CLICK2ADDREVIEW."</a></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font class=\"option\"><b>"._DELMODREVIEW."</b></font><br><br>"
	.""._MODREVINFO."</center>";
    CloseTable();
    include ("footer.php");
}

function add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $rlanguage) {
    global $prefix, $dbi;
    $title = stripslashes(FixQuotes($title));
    $text = stripslashes(FixQuotes($text));
    $reviewer = stripslashes(FixQuotes($reviewer));
    $email = stripslashes(FixQuotes($email));
    sql_query("insert into ".$prefix."_reviews values (NULL, '$date', '$title', '$text', '$reviewer', '$email', '$score', '$cover', '$url', '$url_title', '1', '$rlanguage')", $dbi);
    sql_query("delete from ".$prefix."_reviews_add WHERE id = $id", $dbi);
    Header("Location: admin.php?op=reviews");
}

switch ($op){

    case "reviews":
    reviews();
    break;

    case "add_review":
    add_review($id, $date, $title, $text, $reviewer, $email, $score, $cover, $url, $url_title, $rlanguage);
    break;

    case "mod_main":
    mod_main($title, $description);
    break;

}

} else {
    echo "Access Denied";
}

?>