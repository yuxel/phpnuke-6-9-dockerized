<?PHP

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
/* Comments Delete Function                              */
/*********************************************************/

/* Thanks to Oleg [Dark Pastor] Martos from http://www.rolemancer.ru */
/* to code the comments childs deletion function!                    */

function removeSubComments($tid) {
    global $prefix, $dbi;
    $result = sql_query("select tid from ".$prefix."_comments where pid='$tid'", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if($numrows>0) {
	while(list($stid) = sql_fetch_row($result, $dbi)) {
            removeSubComments($stid);
            sql_query("delete from ".$prefix."_comments where tid='$stid'", $dbi);
        }
    }
    sql_query("delete from ".$prefix."_comments where tid='$tid'", $dbi);
}

function removeComment ($tid, $sid, $ok=0) {
    global $ultramode, $prefix, $dbi;
    if($ok) {
	$result = sql_query("select date from ".$prefix."_comments where pid='$tid'", $dbi);
	$numresults = sql_num_rows($result, $dbi);
        sql_query("update ".$prefix."_stories set comments=comments-1-'$numresults' where sid='$sid'", $dbi);
    /* Call recursive delete function to delete the comment and all its childs */
        removeSubComments($tid);
        if ($ultramode) {
    	    ultramode();
        }
        Header("Location: modules.php?name=News&file=article&sid=$sid");
    } else {
	include("header.php");
        GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._REMOVECOMMENTS."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
        echo "<center>"._SURETODELCOMMENTS."";
        echo "<br><br>[ <a href=\"javascript:history.go(-1)\">"._NO."</a> | <a href=\"admin.php?op=RemoveComment&tid=$tid&sid=$sid&ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function removePollSubComments($tid) {
    global $prefix, $dbi;
    $result = sql_query("select tid from ".$prefix."_pollcomments where pid='$tid'", $dbi);
    $numrows = sql_num_rows($result, $dbi);
    if($numrows>0) {
	while(list($stid) = sql_fetch_row($result, $dbi)) {
            removePollSubComments($stid);
            sql_query("delete from ".$prefix."_pollcomments where tid='$stid'", $dbi);
        }
    }
    sql_query("delete from ".$prefix."_pollcomments where tid='$tid'", $dbi);
}

function RemovePollComment ($tid, $pollID, $ok=0) {
    if($ok) {
        removePollSubComments($tid);
        Header("Location: modules.php?name=Surveys&op=results&pollID=$pollID");
    } else {
	include("header.php");
        GraphicAdmin();
	OpenTable();
	echo "<center><font class=\"title\"><b>"._REMOVECOMMENTS."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
        echo "<center>"._SURETODELCOMMENTS."";
        echo "<br><br>[ <a href=\"javascript:history.go(-1)\">"._NO."</a> | <a href=\"admin.php?op=RemovePollComment&tid=$tid&pollID=$pollID&ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

switch ($op) {

    case "RemoveComment":
    removeComment ($tid, $sid, $ok);
    break;

    case "removeSubComments":
    removeSubComments($tid);
    break;

    case "removePollSubComments":
    removePollSubComments($tid);
    break;

    case "RemovePollComment":
    RemovePollComment($tid, $pollID, $ok);
    break;

}

} else {
    echo "Access Denied";
}
?>
