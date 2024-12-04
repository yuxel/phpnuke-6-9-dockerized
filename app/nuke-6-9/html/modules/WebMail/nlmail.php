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

if(!$user) {
    header ("Location: modules.php?name=Your_Account");
    exit();
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if ($email_send == 1) {

include "header.php";
include ("modules/$module_name/mailheader.php");
include ("modules/$module_name/libmail.php");

$user = base64_decode($user);
$userdata = explode(":", $user);
$userid = $userdata[0];
srand ((double) microtime() * 1000000);
$messageid = rand();
$result = sql_query("select name, username, user_email from ".$user_prefix."_users where user_id='$userid'", $dbi);
list($name, $uname, $email) = sql_fetch_row($result, $dbi);

/*
$sql = "SELECT * FROM ".$user_prefix."_users WHERE user_id='$userid'";
$resultID = sql_query($sql, $dbi);
$myrow = sql_fetch_array($resultID, $dbi);
$email = $myrow[ser_email];
$name = $myrow[name];
$user = $myrow[username];
*/

if($name == "") {
    $name = $uname;
}

$txtfooter = "\n\n___________________________________________________________________________\n";
$txtfooter .= "$footermsgtxt";
$message = stripslashes($message);
$content = $message.$txtfooter;
$contenttype = "text/plain";
$acknowledge = "N";
$status = "NONE";

if($attachment != "") {
    $attachment = $attachmentdir.$attachment;
}

$from = "$name <$email>";

$m= new Mail;
$m->autoCheck(false);
$m->From($from);
$m->To($to);
$m->Subject($subject);
$m->Body($content);
$m->Cc($cc);
$m->Bcc($bcc);
$m->Priority($prior) ;

if($attachment != "")  {
    $m->Attach($attachment,$attchtype);
}

$m->Send();
OpenTable();
echo "<center><b>"._MESSAGESENT."</b></center>";
CloseTable();
include ("modules/$module_name/mailfooter.php");

} else {
    Header("Location: modules.php?name=$module_name");
    exit();
}

?>