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

if (!eregi("modules.php", $_SERVER["PHP_SELF"])) {
    die("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$asql = "SELECT associated FROM ".$prefix."_stories WHERE sid='$sid'";
$aresult = $db->sql_query($asql);
$arow = $db->sql_fetchrow($aresult);

if ($arow[associated] != "") {
    OpenTable();
    echo "<center><b>"._ASSOTOPIC."</b><br><br>";
    $asso_t = explode("-",$arow[associated]);
    for ($i=0; $i<sizeof($asso_t); $i++) {
	if ($asso_t[$i] != "") {
	    $sql2 = "SELECT topicimage, topictext from ".$prefix."_topics WHERE topicid='$asso_t[$i]'";
	    $result2 = $db->sql_query($sql2);
	    $atop = $db->sql_fetchrow($result2);
	    echo "<a href=\"modules.php?name=$module_name&new_topic=$asso_t[$i]\"><img src=\"$tipath$atop[topicimage]\" border=\"0\" hspace=\"10\" alt=\"$atop[topictext]\" title=\"$atop[topictext]\"></a>";
	}
    }
    echo "</center>";
    CloseTable();
    echo "<br>";
}

?>