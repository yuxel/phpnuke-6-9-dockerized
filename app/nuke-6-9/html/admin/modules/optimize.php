<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Optimize your database                                               */
/*                                                                      */
/* Copyright (c) 2001 by Xavier JULIE (webmaster@securite-internet.org  */
/* http://www.securite-internet.org                                     */
/*									*/
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }
$result = sql_query("select name, radminsuper from ".$prefix."_authors where aid='$aid'", $dbi);
list($name, $radminsuper) = sql_fetch_row($result, $dbi);

include("header.php");
GraphicAdmin();
title(""._DBOPTIMIZATION."");
OpenTable();

if ($radminsuper==1) {   
        
    echo "<center><font class=\"title\">"._OPTIMIZINGDB." $dbname</font></center><br><br>"
	."<table border=1 align=\"center\"><tr><td><div align=center>"._TABLE."</div></td><td><div align=center>"._SIZE."</div></td><td><div align=center>"._STATUS."</div></td><td><div align=center>"._SPACESAVED."</div></td></tr>";
    $db_clean = $dbname;
    $tot_data = 0;
    $tot_idx = 0;
    $tot_all = 0;
    $local_query = 'SHOW TABLE STATUS FROM '.$dbname;
    $result = @sql_query($local_query, $dbi);
    if (@sql_num_rows($result, $dbi)) {
	while ($row = sql_fetch_array($result, $dbi)) {
    	    $tot_data = $row['Data_length'];
            $tot_idx  = $row['Index_length'];
            $total = $tot_data + $tot_idx;
            $total = $total / 1024 ;
            $total = round ($total,3);
            $gain= $row['Data_free'];
            $gain = $gain / 1024 ;
            $total_gain += $gain;
            $gain = round ($gain,3);   
            $local_query = 'OPTIMIZE TABLE '.$row[0];
	    $resultat  = sql_query($local_query, $dbi);
       	    if ($gain == 0) {
       		echo "<tr><td>"."$row[0]"."</td>"."<td>"."$total"." Kb"."</td>"."<td>"._ALREADYOPTIMIZED."</td><td>0 Kb</td></tr>";
       	    } else {
       	   	echo "<tr><td><b>"."$row[0]"."</b></td>"."<td><b>"."$total"." Kb"."</b></td>"."<td><b>"._OPTIMIZED."</b></td><td><b>"."$gain"." Kb</b></td></tr>";
       	    }		
	} 
    }
    echo "</table>";
    echo "</center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $total_gain = round ($total_gain,3);
    echo "<center><b>"._OPTIMIZATIONRESULTS."</b><br><br>"
	.""._TOTALSPACESAVED." "."$total_gain"." Kb<br>";
    
    $sql_query = "CREATE TABLE IF NOT EXISTS ".$prefix."_optimize_gain(gain decimal(10,3))";  
    $result = @sql_query($sql_query, $dbi);
       
    $sql_query = "INSERT INTO ".$prefix."_optimize_gain (gain) VALUES ('$total_gain')";
    $result = @sql_query($sql_query, $dbi);
       
    $sql_query = "SELECT * FROM ".$prefix."_optimize_gain";
    $result = sql_query ($sql_query, $dbi);
    while ($row = sql_fetch_row($result, $dbi)) {
	$histo += $row[0];
	$cpt += 1;
    }

    echo ""._YOUHAVERUNSCRIPT." $cpt "._TIMES."<br>"
	."$histo "._KBSAVED."</center>";
    CloseTable();
    include("footer.php");     
}

?>