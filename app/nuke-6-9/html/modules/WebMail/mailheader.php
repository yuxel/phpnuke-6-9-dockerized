<?php

/*************************************************************************/
 #  Mailbox 0.9.2a   by Sivaprasad R.L (http://netlogger.net)             #
 #  eMailBox 0.9.3   by Don Grabowski  (http://ecomjunk.com)              #
 #          --  A pop3 client addon for phpnuked websites --              #
 #                                                                        #
 # This program is distributed in the hope that it will be useful,        #
 # but WITHOUT ANY WARRANTY; without even the implied warranty of         #
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          #
 # GNU General Public License for more details.                           #
 #                                                                        #
 # You should have received a copy of the GNU General Public License      #
 # along with this program; if not, write to the Free Software            #
 # Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.              #
 #                                                                        #
 #             Copyright (C) by Sivaprasad R.L                            #
 #            Script completed by Ecomjunk.com 2001                       #
/*************************************************************************/

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

title("$sitename: "._WEBMAILSERVICE."");

if (is_user($user)) {
    include("modules/Your_Account/navbar.php");
    OpenTable();
    nav();
    CloseTable();
    echo "<br>";
}

function mailimg($gfile) {
    global $ThemeSel, $Default_Theme, $user, $cookie, $module_name;
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/images/webmail/$gfile")) {
	$mailimg = "themes/$ThemeSel/images/webmail/$gfile";
    } else {
	$mailimg = "modules/$module_name/images/$gfile";
    }
    return($mailimg);
}

OpenTable();
echo "<b><font class=\"option\"><center>"._WEBMAILMAINMENU."</center></font></b>"
    ."<br><br>"
    ."<table align=\"center\" width=\"100%\"><tr><td width=\"15%\" align=\"center\">";

$mailimg = mailimg("mailbox.gif");
echo "<a href=\"modules.php?name=$module_name\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._MAILBOX."\" title=\""._MAILBOX."\"></a></td>";

if ($email_send == 1) {
    $mailimg = mailimg("compose.gif");
    echo "<td align=\"center\" width=\"15%\">"
	."<a href=\"modules.php?name=$module_name&file=compose\"><img src=\"$mailimg\" border=\"0\" alt=\""._COMPOSE."\" title=\""._COMPOSE."\"></a></td>";
}

$mailimg = mailimg("settings.gif");
echo "<td width=\"15%\" align=\"center\">"
    ."<a href=\"modules.php?name=$module_name&file=settings\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._SETTINGS."\" title=\""._SETTINGS."\"></a></td>";

$mailimg = mailimg("contact.gif");
echo "<td align=\"center\" width=\"15%\">"
    ."<a href=\"modules.php?name=$module_name&file=contactbook\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._ADDRESSBOOK."\" title=\""._ADDRESSBOOK."\"></a></td>";

$mailimg = mailimg("search.gif");
echo "<td width=\"15%\" align=\"center\">"
    ."<a href=\"modules.php?name=$module_name&file=contactbook&op=search\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._SEARCHCONTACT."\" title=\""._SEARCHCONTACT."\"></a></td>"
    ."<td width=\"15%\" align=\"center\">";

$mailimg = mailimg("logout.gif");
if (is_user($user) AND is_active("Your_Account")) {
    echo "<a href=\"modules.php?name=Your_Account\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._EXIT."\" title=\""._EXIT."\"></a></td>";
} else {
    echo "<a href=\"index.php\"><IMG SRC=\"$mailimg\" border=\"0\" alt=\""._EXIT."\" title=\""._EXIT."\"></a></td>";
}
echo "<tr>"
    ."<td align=\"center\">"._MAILBOX."</td>";
if ($email_send == 1) {
    echo "<td align=\"center\">"._COMPOSE."</td>";
}
echo "<td align=\"center\">"._SETTINGS."</td>"
    ."<td align=\"center\">"._ADDRESSBOOK."</td>"
    ."<td align=\"center\">"._SEARCHCONTACT."</td>"
    ."<td align=\"center\">"._EXIT."</td></tr>"
    ."</table>";
CloseTable();
echo "<br>";

?>