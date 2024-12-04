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

if (!eregi("admin.php", $_SERVER['PHP_SELF'])) { die ("Access Denied"); }

switch($op) {

    case "FaqCatSave":
    case "FaqCatGoSave":
    case "FaqCatAdd":
    case "FaqCatGoAdd":
    case "FaqCatEdit":
    case "FaqCatGoEdit":
    case "FaqCatDel":
    case "FaqCatGoDel":
    case "FaqAdmin":
    case "FaqCatGo":
    include ("admin/modules/adminfaq.php");
    break;

}

?>
