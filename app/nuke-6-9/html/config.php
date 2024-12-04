<?php

######################################################################
# PHP-NUKE: Advanced Content Management System
# ============================================
#
# Copyright (c) 2002 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This module is to configure the main options for your site
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

######################################################################
# Database & System Config
#
# dbhost:       SQL Database Hostname
# dbuname:      SQL Username
# dbpass:       SQL Password
# dbname:       SQL Database Name
# $prefix:      Your Database table's prefix
# $user_prefix: Your Users' Database table's prefix (To share it)
# $dbtype:      Your Database Server type. Supported servers are:
#               MySQL, mysql4, postgres, mssql, oracle, msaccess,
#               db2 and mssql-odbc
#               Be sure to write it exactly as above, case SeNsItIvE!
# $sitekey:	Security Key. CHANGE it to whatever you want, as long
#               as you want. Just don't use quotes.
# $gfx_chk:	Set the graphic security code on every login screen,
#		You need to have GD extension installed:
#		0: No check
#		1: Administrators login only
#		2: Users login only
#		3: New users registration only
#		4: Both, users login and new users registration only
#		5: Administrators and users login only
#		6: Administrators and new users registration only
#		7: Everywhere on all login options (Admins and Users)
#		NOTE: If you aren't sure set this value to 0
######################################################################

$dbhost = "localhost";
$dbuname = "root";
$dbpass = "IAmRoot";
$dbname = "nuke";
$prefix = "nuke";
$user_prefix = "nuke";
$dbtype = "MySQL";
$sitekey = "SdFk*fa28367-dm56w69.3a2fDS+e9";
$gfx_chk = 7;

/*********************************************************************/
/* You finished to configure the Database. Now you can change all    */
/* you want in the Administration Section.   To enter just launch    */
/* you web browser pointing to http://yourdomain.com/admin.php       */
/*                                                                   */
/* Remeber to go to Settings section where you can configure your    */
/* new site. In that menu you can change all you need to change.     */
/*                                                                   */
/* Congratulations! now you have an automated news portal!           */
/* Thanks for choose PHP-Nuke: The Future of the Web                 */
/*********************************************************************/

// DO NOT TOUCH ANYTHING BELOW THIS LINE UNTIL YOU KNOW WHAT YOU'RE DOING

$reasons = array("As Is",
		    "Offtopic",
		    "Flamebait",
		    "Troll",
		    "Redundant",
		    "Insighful",
		    "Interesting",
		    "Informative",
		    "Funny",
		    "Overrated",
		    "Underrated");
$badreasons = 4;
$AllowableHTML = array("b"=>1,
		    "i"=>1,
		    "a"=>2,
		    "em"=>1,
		    "br"=>1,
		    "strong"=>1,
		    "blockquote"=>1,
                    "tt"=>1,
                    "li"=>1,
                    "ol"=>1,
                    "ul"=>1);
$CensorList = array("fuck",
		    "cunt",
		    "fucker",
		    "fucking",
		    "pussy",
		    "cock",
		    "c0ck",
		    "cum",
		    "twat",
		    "clit",
		    "bitch",
		    "fuk",
		    "fuking",
		    "motherfucker");
$tipath = "images/topics/";
if (eregi("config.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}

?>
