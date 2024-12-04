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

    case "encyclopedia":
    case "move_terms":
    case "encyclopedia_terms":
    case "encyclopedia_edit":
    case "encyclopedia_delete":
    case "encyclopedia_save":
    case "encyclopedia_save_edit":
    case "encyclopedia_text_edit":
    case "encyclopedia_text_delete":
    case "encyclopedia_text_save":
    case "encyclopedia_text_save_edit":
    case "encyclopedia_change_status":
    include("admin/modules/encyclopedia.php");
    break;
		
}

?>