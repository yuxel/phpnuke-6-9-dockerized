<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (eregi("block-Amazon.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

/***************************************************************/
/* To use this block you only need to download .jpg or .gif    */
/* images from amazon.com and copy them to the /images/amazon  */
/* directory, then edit the $amazon_id variable to fit your ID */
/* of the Associates program. If you don't change the ID, all  */
/* the comissions ($) will go to my account! You're adviced.   */
/* But if you want to help PHP-Nuke project, you can leave the */
/* $amazon_id variable intact.                                 */
/*                                                             */
/* You need to know that any image in the amazon's directory   */
/* has the same ASIN name as its filename given by Amazon. If  */
/* you don't know what this is, leave it as is or disable it.  */
/***************************************************************/

$amazon_id = "phpnuke-20";

mt_srand((double)microtime()*1000000);
$imgs = dir('images/amazon');
while ($file = $imgs->read()) {
    if (eregi("gif", $file) || eregi("jpg", $file)) {
	$imglist .= "$file ";
    }
}
closedir($imgs->handle);
$imglist = explode(" ", $imglist);
$a = sizeof($imglist)-2;
$random = mt_rand(0, $a);
$image = $imglist[$random];
$asin = explode(".", $image);
$content = "<br><center><a href=\"http://www.amazon.com/exec/obidos/ASIN/$asin[0]/$amazon_id\" target=\"_blank\">";
$content .= "<img src=\"images/amazon/$image\" border=\"0\" alt=\"\"><br><br></center>";

?>