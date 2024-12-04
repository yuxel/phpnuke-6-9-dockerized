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

if(!is_user($user)) {
    Header("Location: modules.php?name=Your_Account");
    exit();
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$pagetitle = "- "._WEBMAILSERVICE."";

include ("header.php");
parse_str(base64_decode($pop3_cookie));
require ("modules/$module_name/pop3.php");
require ("modules/$module_name/decodemessage.php");
include ("modules/$module_name/mailheader.php");
include ("modules/$module_name/class.rc4crypt.php");
$user1 = base64_decode($user);
$userdata = explode(":", $user1);
$userid = $userdata[0];

if ($numaccounts == -1 OR $numaccounts > 1) {
    $welcome_msg = _MAILWELCOME1;
} elseif ($numaccounts == 1) {
    $welcome_msg = _MAILWELCOME2;
}

$query = "select * from $prefix"._popsettings." where uid = $userid";
$res = sql_query($query, $dbi);
if(sql_num_rows($res, $dbi) < 1) {
    OpenTable();
    echo "<table width=\"95%\" border=\"0\" align=\"center\"><tr><td>"
	."<b>"._MAILWELCOME3." $sitename!</b><br><br>"
        .""._CLICKONSETTINGS."<br><br>$welcome_msg"
        ."</td></tr></table>";
    CloseTable();
    include ("modules/$module_name/mailfooter.php");
    return;
}
echo "<script language=javascript>
    function mailbox(num) {
	formname = 'inbox' + num;
	window.document.forms[formname].submit();
    }
    </script>";
$count = 0;
OpenTable();
echo "<center><b>"._MAILBOXESFOR." $userdata[1]</b></center>";
echo "<br><table border=\"1\" align=\"center\" width=\"80%\">"
    ."<tr><td bgcolor=\"$bgcolor2\" width=\"33%\">&nbsp;<b>"._ACCOUNT."</b></td><td bgcolor=\"$bgcolor2\" width=\"33%\" align=\"center\">&nbsp;<b>"._EMAILS."</b></td><td bgcolor=\"$bgcolor2\" width=\"33%\" align=\"center\">&nbsp;<b>"._TOTALSIZE."</b></td></tr>";
while($row = sql_fetch_array($res, $dbi)) {
    $count++;
    $server = $row[popserver];
    $port = $row[port];
    $username = $row[uname];
    $rc4 = new rc4crypt();
    $password = $rc4->endecrypt($username,$row[passwd],"de");
    $account = $row[account];
    $serverid = $row[id];
    $pop3=new POP3($server,$username,$password);
    $pop3->Open();
    $stats = $pop3->Stats();
    $mailsum = $stats["message"];
    $mailmem = round($stats["size"]/1024);
    echo "<tr>"
	."<td align=\"left\">&nbsp;"
        ."<a href=\"modules.php?name=$module_name&file=inbox&id=$serverid\">$account</a></td>"
        ."<td align=\"center\">$mailsum</td>"
        ."<td align=\"center\">$mailmem Kbytes</td></tr>";
    $pop3->Close();
}
echo "</table><br><br>"
    ."<center>"._SELECTACCOUNT."</center>";
CloseTable();
include ("modules/$module_name/mailfooter.php");

?>