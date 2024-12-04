<?php
/***************************************************************************
 *                                  faq.php
 *                            -------------------
 *   begin                : Sunday, Jul 8, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: faq.php,v 1.14 2002/03/31 00:06:33 psotfx Exp $
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

define('IN_PHPBB', true);
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FAQ, $nukeuser);
init_userprefs($userdata);
//
// End session management
//

//
// Load the appropriate faq file
//
if( isset($HTTP_GET_VARS['mode']) )
{
        switch( $HTTP_GET_VARS['mode'] )
        {
                case 'bbcode':
                        $lang_file = 'lang_bbcode';
                        $l_title = $lang['BBCode_guide'];
                        break;
                default:
                        $lang_file = 'lang_faq';
                        $l_title = $lang['FAQ'];
                        break;
        }
}
else
{
        $lang_file = 'lang_faq';
        $l_title = $lang['FAQ'];
}
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/' . $lang_file . '.' . $phpEx);

//
// Pull the array data from the lang pack
//
$j = 0;
$counter = 0;
$counter_2 = 0;
$faq_block = array();
$faq_block_titles = array();

for($i = 0; $i < count($faq); $i++)
{
        if( $faq[$i][0] != '--' )
        {
                $faq_block[$j][$counter]['id'] = $counter_2;
                $faq_block[$j][$counter]['question'] = $faq[$i][0];
                $faq_block[$j][$counter]['answer'] = $faq[$i][1];

                $counter++;
                $counter_2++;
        }
        else
        {
                $j = ( $counter != 0 ) ? $j + 1 : 0;

                $faq_block_titles[$j] = $faq[$i][1];

                $counter = 0;
        }
}

//
// Lets build a page ...
//
$page_title = $l_title;
include("includes/page_header.php");

$template->set_filenames(array(
        'body' => 'faq_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx, $forum_id);

$template->assign_vars(array(
        'L_FAQ_TITLE' => $l_title,
        'L_BACK_TO_TOP' => $lang['Back_to_top'])
);

for($i = 0; $i < count($faq_block); $i++)
{
        if( count($faq_block[$i]) )
        {
                $template->assign_block_vars('faq_block', array(
                        'BLOCK_TITLE' => $faq_block_titles[$i])
                );
                $template->assign_block_vars('faq_block_link', array(
                        'BLOCK_TITLE' => $faq_block_titles[$i])
                );

                for($j = 0; $j < count($faq_block[$i]); $j++)
                {
                        $row_color = ( !($j % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
                        $row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

                        $template->assign_block_vars('faq_block.faq_row', array(
                                'ROW_COLOR' => '#' . $row_color,
                                'ROW_CLASS' => $row_class,
                                'FAQ_QUESTION' => $faq_block[$i][$j]['question'],
                                'FAQ_ANSWER' => $faq_block[$i][$j]['answer'],

                                'U_FAQ_ID' => $faq_block[$i][$j]['id'])
                        );

                        $template->assign_block_vars('faq_block_link.faq_row_link', array(
                                'ROW_COLOR' => '#' . $row_color,
                                'ROW_CLASS' => $row_class,
                                'FAQ_LINK' => $faq_block[$i][$j]['question'],

                                'U_FAQ_LINK' => '#' . $faq_block[$i][$j]['id'])
                        );
                }
        }
}

$template->pparse('body');

include("includes/page_tail.php");

?>