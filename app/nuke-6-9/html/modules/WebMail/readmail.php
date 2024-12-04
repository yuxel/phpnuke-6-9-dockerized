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

parse_str(base64_decode($pop3_cookie));
require ("modules/$module_name/pop3.php");
require ("modules/$module_name/decodemessage.php");
include ("header.php");
include ("modules/$module_name/mailheader.php");
include ("modules/$module_name/class.rc4crypt.php");

if(!isset($id)) {
    echo "Error: Invalid Parameter<br>";
    include ("modules/$module_name/mailfooter.php");
    exit();
}

$query = "Select * from $prefix"._popsettings." where id = $id";
if(($res = sql_query($query, $dbi)) && (sql_num_rows($res, $dbi) > 0)) {
    $row = sql_fetch_array($res, $dbi);
    $uid = $row[uid];
    $auser = base64_decode($user);
    $userdata = explode(":", $auser);
    $userid = $userdata[0];
    if ($uid != $userid) {
	echo "<center><h2>Error: Permission denied</center>";
	include ("modules/$module_name/mailfooter.php");
	exit();
    }
    $server = $row[popserver];
    $port = $row[port];
    $username = $row[uname];
    $rc4 = new rc4crypt();
    $password = $rc4->endecrypt($username,$row[passwd],"de");
} else {
    echo "Error: POP Server not set properly<br>";
    include ("modules/$module_name/mailfooter.php");
    exit();
}

$ms = $msgid;
set_time_limit(0);
$pop3=new POP3($server,$username,$password);
$pop3->Open();
$message = $pop3->GetMessage($ms) ;
$s = $pop3->Stats() ;
$body = $message["body"];
$header = $message["header"];
$full = $message["full"];
$pop3->Close();
$d = new DecodeMessage;
$d->InitMessage($full);
$from_address = chop($d->Headers("From"));
$to_address = chop($d->Headers("To"));
$subject = $d->Headers("Subject");
$cc = chop($d->Headers("Cc"));
$replyto = chop($d->Headers("Reply-To:"));
$result = sql_query("select account from ".$prefix."_popsettings where id='$id'", $dbi);
list($account) = sql_fetch_row($result, $dbi);
title(""._MAILBOX." ($account)");
OpenTable();
echo "<table border=\"0\" width=\"100%\">
    <tr>
    <td align=\"left\" bgcolor=\"$bgcolor2\"><b>"._FROM.":</b></td>
    <td>".htmlspecialchars($from_address)."</td>
    </tr>
    <tr>
    <td align=\"left\" bgcolor=\"$bgcolor2\"><b>"._TO.":</b></td>
    <td>".htmlspecialchars($to_address)."</td>
    </tr>";

if ($cc != "") {
    echo "<tr>
	<td align=\"left\" bgcolor=\"$bgcolor2\"><b>Cc:</b></td>
        <td>".htmlspecialchars($cc)."</td>
        </tr>";
}

echo "<tr>
    <td align=\"left\" bgcolor=\"$bgcolor2\"><b>"._SUBJECT.":</b></td>
    <td>".htmlspecialchars($subject)."</td>
    </tr><tr>
    <td align=\"left\" bgcolor=\"$bgcolor2\"><b>"._DATE.":</b></td>
    <td>".htmlspecialchars($d->Headers("Date")) ."</td>
    </tr><tr>
    <td colspan=2>
    <table border=0 width=100% cellspacing=0><tr><td bgcolor=$bgcolor2>
    <table border=0 width=100% cellspacing=5 cellpadding=0><tr><td bgcolor=\"$bgcolor2\">
    <form action=\"modules.php?name=$module_name&file=inbox\" method=\"post\">
    <input type=hidden name=\"id\" value=\"$id\">
    <input type=hidden name=\"op\" value=\"delete\">
    <input type=hidden name=\"msgid\" value=\"$msgid\">
    <input type=submit value=\""._DELETE."\">";
if ($email_send == 1) {
    echo "</form>
	</td><td bgcolor=\"$bgcolor2\">
	<form action=modules.php?name=$module_name&file=compose method=\"post\">
	<input type=hidden name=to value=\"".htmlspecialchars($from_address)."\">
	<input type=hidden name=subject value=\"".htmlspecialchars($subject)."\">
	<input type=hidden name=body value=\"".htmlspecialchars($content)."\">
	<input type=hidden name=op value=\"reply\">
	<input type=submit value=\""._REPLY."\">
	</form>
	</td><td bgcolor=\"$bgcolor2\" width=\"100%\">
	<form action=\"modules.php?name=$module_name&file=compose\" method=\"post\">
	<input type=hidden name=\"to\" value=\"".htmlspecialchars($from_address)."\">
	<input type=hidden name=\"subject\" value=\"".htmlspecialchars($subject)."\">
	<input type=hidden name=\"body\" value=\"".htmlspecialchars($content)."\">
	<input type=hidden name=\"op\" value=\"forward\">
	<input type=submit value=\""._FORWARD."\">
	</form>";
}
echo "</td></tr></table></tr></td></table></td></tr><tr><td colspan=2 bgcolor=\"$bgcolor2\">";
OpenTable();
$message = $d->Result();
$rtext = "";

for ($j=0;$j<count($message);$j++) {
    for ($i=0;$i<count($message[$j]);$i++) {
	if (chop($message[$j][$i]["attachments"]) != '') {
	    $att_txt .= " <a href=\"".$d->attachment_path."/".$message[$j][$i]["attachments"]."\">".$message[$j][$i]["attachments"]."</a>";
	}
    }
    for ($i=0;$i<count($message[$j]);$i++) {
	if (eregi("text/html", $message[$j][$i]["body"]["type"])) {
	    $res = quoted_printable_decode($message[$j][$i]["body"]["body"]);
    	    $res = ereg_replace("(=\n)", "", $res);
    	    $res = eregi_replace("(<body)", "<xbody", $res);
    	    $res = eregi_replace("(<meta)", "<xmeta", $res);
	    $res = filter_text($res); // Security fix by Ulf Harnhammar 2002
	    echo "<br>";
	    echo $res;
	} else {
    	    echo nl2br(htmlspecialchars($message[$j][$i]["body"]["body"]))."<br>";
	}
	$content = $rtext .= strip_tags($message[$j][$i]["body"]["body"]);
    }
}
CloseTable();
echo "</td></tr></table>";

if ($attachments_view == 1) {
    if($att_txt) {
	echo "<table align=\"center\" border=\"0\" width=\"100%\"><tr bgcolor=\"$bgcolor2\"><td>
    	    <b>&nbsp;"._ATTACHMENTS.": </b></td><td width=\"100%\">&nbsp;$att_txt</td></tr></table>";
    }
}

if ($attach_nv == 1) {
	echo "<table align=\"center\" border=\"0\" width=\"100%\"><tr bgcolor=\"$bgcolor2\"><td align=\"center\">"
	    .""._ATTACHSECURITY."</td></tr></table>";
}

CloseTable();
include ("modules/$module_name/mailfooter.php");

?>