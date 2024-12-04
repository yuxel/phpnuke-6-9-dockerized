<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("footer.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

$footer = 1;

function footmsg() {
    global $foot1, $foot2, $foot3, $copyright, $total_time, $start_time;
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
    $end_time = $mtime;
    $total_time = ($end_time - $start_time);
    $total_time = ""._PAGEGENERATION." ".substr($total_time,0,5)." "._SECONDS."";
    echo "<font class=\"footmsg\">\n";
    if ($foot1 != "") {
	echo "$foot1<br>\n";
    }
    if ($foot2 != "") {
	echo "$foot2<br>\n";
    }
    if ($foot3 != "") {
	echo "$foot3<br>\n";
    }
    // DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE. YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS.
    echo "$copyright<br>$total_time<br>\n</font>\n";
}

function foot() {
    global $prefix, $user_prefix, $db, $index, $user, $cookie, $storynum, $user, $cookie, $Default_Theme, $foot1, $foot2, $foot3, $foot4, $home, $module, $name;
    if ($home == 1) {
	blocks(Down);
    }
    if ($module == 1 AND file_exists("modules/$name/copyright.php")) {
	$cpname = ereg_replace("_", " ", $name);
	echo "<div align=\"right\"><a href=\"javascript:openwindow()\">$cpname &copy;</a></div>";
    }
    themefooter();
    echo "</body>\n"
	."</html>";
    die();
}

foot();

?>