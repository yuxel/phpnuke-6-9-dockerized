<?php
/***************************************************************************
 *                                common.php
 *                            -------------------
 *   begin                : Saturday, Feb 23, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: common.php,v 1.74.2.10 2003/06/04 17:41:39 acydburn Exp $
 *
 *
 ***************************************************************************/
/***************************************************************************
* phpbb2 forums port version 2.0.5 (c) 2003 - Nuke Cops (http://nukecops.com)
*
* Ported by Nuke Cops to phpbb2 standalone 2.0.5 Test
* and debugging completed by the Elite Nukers and site members.
*
* You run this package at your sole risk. Nuke Cops and affiliates cannot
* be held liable if anything goes wrong. You are advised to test this
* package on a development system. Backup everything before implementing
* in a production environment. If something goes wrong, you can always
* backout and restore your backups.
*
* Installing and running this also means you agree to the terms of the AUP
* found at Nuke Cops.
*
* This is version 2.0.5 of the phpbb2 forum port for PHP-Nuke. Work is based
* on Tom Nitzschner's forum port version 2.0.6. Tom's 2.0.6 port was based
* on the phpbb2 standalone version 2.0.3. Our version 2.0.5 from Nuke Cops is
* now reflecting phpbb2 standalone 2.0.5 that fixes some bugs and the
* invalid_session error message.
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
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
        die("Hacking attempt");
}

error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if( !get_magic_quotes_gpc() )
{
        if( is_array($HTTP_GET_VARS) )
        {
                while( list($k, $v) = each($HTTP_GET_VARS) )
                {
                        if( is_array($HTTP_GET_VARS[$k]) )
                        {
                                while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
                                {
                                        $HTTP_GET_VARS[$k][$k2] = addslashes($v2);
                                }
                                @reset($HTTP_GET_VARS[$k]);
                        }
                        else
                        {
                                $HTTP_GET_VARS[$k] = addslashes($v);
                        }
                }
                @reset($HTTP_GET_VARS);
        }

        if( is_array($HTTP_POST_VARS) )
        {
                while( list($k, $v) = each($HTTP_POST_VARS) )
                {
                        if( is_array($HTTP_POST_VARS[$k]) )
                        {
                                while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
                                {
                                        $HTTP_POST_VARS[$k][$k2] = addslashes($v2);
                                }
                                @reset($HTTP_POST_VARS[$k]);
                        }
                        else
                        {
                                $HTTP_POST_VARS[$k] = addslashes($v);
                        }
                }
                @reset($HTTP_POST_VARS);
        }

        if( is_array($HTTP_COOKIE_VARS) )
        {
                while( list($k, $v) = each($HTTP_COOKIE_VARS) )
                {
                        if( is_array($HTTP_COOKIE_VARS[$k]) )
                        {
                                while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
                                {
                                        $HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
                                }
                                @reset($HTTP_COOKIE_VARS[$k]);
                        }
                        else
                        {
                                $HTTP_COOKIE_VARS[$k] = addslashes($v);
                        }
                }
                @reset($HTTP_COOKIE_VARS);
        }
}

//
// Define some basic configuration arrays this also prevents
// malicious rewriting of language and otherarray values via
// URI params
//
$board_config = array();
$userdata = array();
$theme = array();
$images = array();
$lang = array();
$gen_simple_header = FALSE;

include($phpbb_root_path . 'config.'.$phpEx);

if( !defined("PHPBB_INSTALLED") )
{
        header("Location: modules.php?name=Forums&file=install");
        exit;
}

global $forum_admin;
if ($forum_admin == 1) {
    //include("../../../db/db.php");
    include("../../../includes/constants.php");
    include("../../../includes/template.php");
    include("../../../includes/sessions.php");
    include("../../../includes/auth.php");
    include("../../../includes/functions.php");
} else {
    include("includes/constants.php");
    include("includes/template.php");
    include("includes/sessions.php");
    include("includes/auth.php");
    include("includes/functions.php");
    include("db/db.php");
}

//
// Obtain and encode users IP
//
if( getenv('HTTP_X_FORWARDED_FOR') != '' )
{
        $client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );

        $entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
        reset($entries);
        while (list(, $entry) = each($entries))
        {
                $entry = trim($entry);
                if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
                {
                        $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
                        $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                        if ($client_ip != $found_ip)
                        {
                                $client_ip = $found_ip;
                                break;
                        }
                }
        }
}
else
{
        $client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
}
$user_ip = encode_ip($client_ip);

//
// Setup forum wide options, if this fails
// then we output a CRITICAL_ERROR since
// basic forum information is not available
//
$sql = "SELECT *
        FROM " . CONFIG_TABLE;
if( !($result = $db->sql_query($sql)) )
{
        message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
        $board_config[$row['config_name']] = $row['config_value'];
}


//
// Show 'Board is disabled' message if needed.
//
if( $board_config['board_disable'] && !defined("IN_ADMIN") && !defined("IN_LOGIN") )
{
        message_die(GENERAL_MESSAGE, 'Board_disable', 'Information');
}

?>