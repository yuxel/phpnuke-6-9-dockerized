<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This is a 2 min hack of the old forum block to work with the phpBB2  */
/* port.                                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Forums.php", $_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

global $prefix, $db, $sitename;

$sql = "SELECT forum_id, topic_id, topic_title FROM ".$prefix."_bbtopics ORDER BY topic_time DESC LIMIT 10";
$result = $db->sql_query($sql);
$content = "<br>";
while ($row = $db->sql_fetchrow($result)) {
    $forum_id = $row[forum_id];
    $topic_id = $row[topic_id];
    $topic_title = $row[topic_title];
    $sql2 = "SELECT auth_view, auth_read FROM ".$prefix."_bbforums WHERE forum_id='$forum_id'";
    $result2 = $db->sql_query($sql2);
    $row2 = $db->sql_fetchrow($result);
    $auth_view = $row2[auth_view];
    $auth_read = $row2[auth_read];
    if (($auth_view < 2) OR ($auth_read < 2)) {
        $content .= "<img src=\"images/arrow.gif\" border=\"0\" alt=\"\" title=\"\" width=\"9\" height=\"9\">&nbsp;<a href=\"modules.php?name=Forums&amp;file=viewtopic&amp;t=$topic_id\">$topic_title</a><br>";
    }
}

$content .= "<br><center><a href=\"modules.php?name=Forums\"><b>$sitename Forums</b></a><br><br></center>";

?>