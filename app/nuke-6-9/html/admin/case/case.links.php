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

    case "Links":
    case "LinksDelNew":
    case "LinksAddCat":
    case "LinksAddSubCat":
    case "LinksAddLink":
    case "LinksAddEditorial":
    case "LinksModEditorial":
    case "LinksLinkCheck":
    case "LinksValidate":
    case "LinksDelEditorial":
    case "LinksCleanVotes":
    case "LinksListBrokenLinks":
    case "LinksEditBrokenLinks":
    case "LinksDelBrokenLinks":
    case "LinksIgnoreBrokenLinks":
    case "LinksListModRequests":
    case "LinksChangeModRequests":
    case "LinksChangeIgnoreRequests":
    case "LinksDelCat":
    case "LinksModCat":
    case "LinksModCatS":
    case "LinksModLink":
    case "LinksModLinkS":
    case "LinksDelLink":
    case "LinksDelVote":
    case "LinksDelComment":
    case "LinksTransfer":
    include("admin/modules/links.php");
    break;

}

?>
