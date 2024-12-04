<?php
/***************************************************************************
 * phpbb2 forums port version 2.1 (c) 2003 - Nuke Cops (http://nukecops.com)
 *
 * Ported by Paul Laudanski (Zhen-Xjell) to phpbb2 standalone 2.0.4.  Test
 * and debugging completed by the Elite Nukers at Nuke Cops: ArtificialIntel,
 * Chatserv, MikeM, sixonetonoffun, Zhen-Xjell.  Thanks to some heavy debug
 * work by AI in Nuke 6.5.
 *
 * You run this package at your sole risk.  Nuke Cops and affiliates cannot
 * be held liable if anything goes wrong.  You are advised to test this
 * package on a development system.  Backup everything before implementing
 * in a production environment.  If something goes wrong, you can always
 * backout and restore your backups.
 *
 * Installing and running this also means you agree to the terms of the AUP 
 * found at Nuke Cops.
 *
 * This is version 2.1 of the phpbb2 forum port for PHP-Nuke.  Work is based
 * on Tom Nitzschner's forum port version 2.0.6.  Tom's 2.0.6 port was based
 * on the phpbb2 standalone version 2.0.3.  Our version 2.1 from Nuke Cops is
 * now reflecting phpbb2 standalone 2.0.4 that fixes some major SQL 
 * injection exploits.
 ***************************************************************************/

/***************************************************************************
 *   This file is part of the phpBB2 port to Nuke 6.0 (c) copyright 2002
 *   by Tom Nitzschner (tom@toms-home.com)
 *   http://bbtonuke.sourceforge.net (or http://www.toms-home.com)
 *
 *   As always, make a backup before messing with anything. All code
 *   release by me is considered sample code only. It may be fully
 *   functual, but you use it at your own risk, if you break it,
 *   you get to fix it too. No waranty is given or implied.
 *
 *   Please post all questions/request about this port on http://bbtonuke.sourceforge.net first,
 *   then on my site. All original header code and copyright messages will be maintained
 *   to give credit where credit is due. If you modify this, the only requirement is
 *   that you also maintain all original copyright messages. All my work is released
 *   under the GNU GENERAL PUBLIC LICENSE. Please see the README for more information.
 *
 ***************************************************************************/

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}
    
global $phpbb_root_path, $nuke_root_path, $nuke_file_path, $php_root_dir, $module_name, $nukename, $pass, $nukename;
$module_name = "Forums";
$nuke_root_path = "modules.php?name=".$module_name;
$nuke_file_path = "modules.php?name=".$module_name."&file=";
$phpbb_root_path = "modules/".$module_name."/";
$phpbb_root_dir = "./../";
require_once("mainfile.php");
get_lang($module_name);

include("header.php");
?>
