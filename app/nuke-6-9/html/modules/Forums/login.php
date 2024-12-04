<?php
/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: login.php,v 1.47.2.12 2003/05/06 20:18:42 acydburn Exp $
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
if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}
$module_name = basename(dirname(__FILE__));
require("modules/".$module_name."/nukebb.php");

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN, $nukeuser);
init_userprefs($userdata);
//
// End session management
//

$header_location = ( @preg_match('/Microsoft|WebSTAR|Xitami/', $_SERVER['SERVER_SOFTWARE']) ) ? 'Refresh: 0; URL=' : 'Location: ';
// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
        $sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
        $sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
        if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && !$userdata['session_logged_in'] )
        {
                $username = isset($HTTP_POST_VARS['username']) ? trim(htmlspecialchars($HTTP_POST_VARS['username'])) : '';
                $username = substr(str_replace("\'", "'", $username), 0, 25);
                $password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

                $sql = "SELECT user_id, username, user_password, user_active, user_level
                        FROM " . USERS_TABLE . "
                        WHERE username = '" . str_replace("\'", "''", $username) . "'";
                if ( !($result = $db->sql_query($sql)) )
                {
                        message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
                }

                if( $row = $db->sql_fetchrow($result) )
                {
                        if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
                        {
                                header($header_location . append_sid("index.$phpEx", true));
                                exit;
                        }
                        else
                        {
                                if( ($row['user_password'] == 'Reset2WhateverTheyFirstLoginWith') && ($password != '') )
                                {
                                        $row['user_password'] = md5($password);
                                        $sql = "UPDATE " . USERS_TABLE . "
                                                SET user_password = '" . $row['user_password'] . "'
                                                WHERE user_id = " . $row['user_id'];
                                        if ( !($result = $db->sql_query($sql)) )
                                        {
                                                message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql_update);
                                        }
                                }
                                if( md5($password) == $row['user_password'] && $row['user_active'] )
                                {
                                        $autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

                                        $session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin);

                                        if( $session_id )
                                        {
                                                if( !empty($HTTP_POST_VARS['redirect']) )
                                                {
                                                        header($header_location . append_sid($HTTP_POST_VARS['redirect'], true));
                                                        exit;
                                                }
                                                else
                                                {
                                                        header($header_location . append_sid("index.$phpEx", true));
                                                        exit;
                                                }
                                        }
                                        else
                                        {
                                                message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
                                        }
                                }
                                else
                                {
                                        $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : '';
                                        $redirect = str_replace("?", "&", $redirect);

                                        $template->assign_vars(array(
                                                'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("login.$phpEx?redirect=$redirect") . '">')
                                        );

                                        $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . append_sid("login.$phpEx?redirect=$redirect") . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

                                        message_die(GENERAL_MESSAGE, $message);
                                }
                        }
                }
                else
                {
                        $redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? $HTTP_POST_VARS['redirect'] : "";
                        $redirect = str_replace("?", "&", $redirect);

                        $template->assign_vars(array(
                                'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("login.$phpEx?redirect=$redirect") . '">')
                        );

                        $message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="' . append_sid("login.$phpEx?redirect=$redirect") . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

                        message_die(GENERAL_MESSAGE, $message);
                }
        }
        else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
        {
                if( $userdata['session_logged_in'] )
                {
                        session_end($userdata['session_id'], $userdata['user_id']);
                }

                if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
                {
                        header($header_location . append_sid($HTTP_POST_VARS['redirect'], true));
                        exit;
                }
                else
                {
                        header($header_location . append_sid("index.$phpEx", true));
                        exit;
                }
        }
        else
        {
                if( !empty($HTTP_POST_VARS['redirect']) )
                {
                        header($header_location . append_sid($HTTP_POST_VARS['redirect'], true));
                        exit;
                }
                else
                {
                        header($header_location . append_sid("index.$phpEx", true));
                        exit;
                }
        }
}
else
{
        //
        // Do a full login page dohickey if
        // user not already logged in
        //
        if( !$userdata['session_logged_in'] )
        {
                $page_title = $lang['Login'];
                include("includes/page_header.php");

                $template->set_filenames(array(
                        'body' => 'login_body.tpl')
                );

                if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
                {
                        $forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

                        if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
                        {
                                $forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
                                $forward_match = explode('&', $forward_to);

                                if(count($forward_match) > 1)
                                {
                                        $forward_page = '';

                                        for($i = 1; $i < count($forward_match); $i++)
                                        {
                                                if( !ereg("sid=", $forward_match[$i]) )
                                                {
                                                        if( $forward_page != '' )
                                                        {
                                                                $forward_page .= '&';
                                                        }
                                                        $forward_page .= $forward_match[$i];
                                                }
                                        }
                                        $forward_page = $forward_match[0] . '?' . $forward_page;
                                }
                                else
                                {
                                        $forward_page = $forward_match[0];
                                }
                        }
                }
                else
                {
                        $forward_page = '';
                }

                Header("Location: modules.php?name=Your_Account&redirect=$forward_page");
                $username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

                $s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';

                make_jumpbox('viewforum.'.$phpEx, $forum_id);
                $template->assign_vars(array(
                        'USERNAME' => $username,

                        'L_ENTER_PASSWORD' => $lang['Enter_password'],
                        'L_SEND_PASSWORD' => $lang['Forgotten_password'],

                        'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

                        'S_HIDDEN_FIELDS' => $s_hidden_fields)
                );

                $template->pparse('body');

                include("includes/page_tail.php");
        }
        else
        {
                header($header_location . append_sid("index.$phpEx", true));
                exit;
        }

}

?>