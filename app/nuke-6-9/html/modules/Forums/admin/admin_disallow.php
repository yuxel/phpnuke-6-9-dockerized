<?php
/***************************************************************************
 *                            admin_disallow.php
 *                            -------------------
 *   begin                : Tuesday, Oct 05, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_disallow.php,v 1.9.2.2 2002/11/26 11:42:11 psotfx Exp $
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
        $filename = basename(__FILE__);
        $module['Users']['Disallow'] = append_sid($filename);

        return;
}

//
// Include required files, get $phpEx and check permissions
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if( isset($HTTP_POST_VARS['add_name']) )
{
        include("../../../includes/functions_validate.php");

        $disallowed_user = ( isset($HTTP_POST_VARS['disallowed_user']) ) ? trim($HTTP_POST_VARS['disallowed_user']) : trim($HTTP_GET_VARS['disallowed_user']);

        if ($disallowed_user == '')
        {
                message_die(MESSAGE, $lang['Fields_empty']);
        }
        if( !validate_username($disallowed_user) )
        {
                $message = $lang['Disallowed_already'];
        }
        else
        {
                $sql = "INSERT INTO " . DISALLOW_TABLE . " (disallow_username)
                        VALUES('" . str_replace("\'", "''", $disallowed_user) . "')";
                $result = $db->sql_query( $sql );
                if ( !$result )
                {
                        message_die(GENERAL_ERROR, "Could not add disallowed user.", "",__LINE__, __FILE__, $sql);
                }
                $message = $lang['Disallow_successful'];
        }

        $message .= "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
}
else if( isset($HTTP_POST_VARS['delete_name']) )
{
        $disallowed_id = ( isset($HTTP_POST_VARS['disallowed_id']) ) ? intval( $HTTP_POST_VARS['disallowed_id'] ) : intval( $HTTP_GET_VARS['disallowed_id'] );

        $sql = "DELETE FROM " . DISALLOW_TABLE . "
                WHERE disallow_id = $disallowed_id";
        $result = $db->sql_query($sql);
        if( !$result )
        {
                message_die(GENERAL_ERROR, "Couldn't removed disallowed user.", "",__LINE__, __FILE__, $sql);
        }

        $message .= $lang['Disallowed_deleted'] . "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);

}

//
// Grab the current list of disallowed usernames...
//
$sql = "SELECT *
        FROM " . DISALLOW_TABLE;
$result = $db->sql_query($sql);
if( !$result )
{
        message_die(GENERAL_ERROR, "Couldn't get disallowed users.", "", __LINE__, __FILE__, $sql );
}

$disallowed = $db->sql_fetchrowset($result);

//
// Ok now generate the info for the template, which will be put out no matter
// what mode we are in.
//
$disallow_select = '<select name="disallowed_id">';

if( trim($disallowed) == "" )
{
        $disallow_select .= '<option value="">' . $lang['no_disallowed'] . '</option>';
}
else
{
        $user = array();
        for( $i = 0; $i < count($disallowed); $i++ )
        {
                $disallow_select .= '<option value="' . $disallowed[$i]['disallow_id'] . '">' . $disallowed[$i]['disallow_username'] . '</option>';
        }
}

$disallow_select .= '</select>';

$template->set_filenames(array(
        "body" => "admin/disallow_body.tpl")
);

$template->assign_vars(array(
        "S_DISALLOW_SELECT" => $disallow_select,
        "S_FORM_ACTION" => append_sid("admin_disallow.$phpEx"),

        "L_INFO" => $output_info,
        "L_DISALLOW_TITLE" => $lang['Disallow_control'],
        "L_DISALLOW_EXPLAIN" => $lang['Disallow_explain'],
        "L_DELETE" => $lang['Delete_disallow'],
        "L_DELETE_DISALLOW" => $lang['Delete_disallow_title'],
        "L_DELETE_EXPLAIN" => $lang['Delete_disallow_explain'],
        "L_ADD" => $lang['Add_disallow'],
        "L_ADD_DISALLOW" => $lang['Add_disallow_title'],
        "L_ADD_EXPLAIN" => $lang['Add_disallow_explain'],
        "L_USERNAME" => $lang['Username'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>