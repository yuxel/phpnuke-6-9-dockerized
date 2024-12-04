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

switch($op) {

    case "content":
    case "content_edit":
    case "content_delete":
    case "content_save":
    case "content_save_edit":
    case "content_change_status":
    case "add_category":
    case "edit_category":
    case "save_category":
    case "del_content_cat":
    include("admin/modules/content.php");
    break;

}

?>