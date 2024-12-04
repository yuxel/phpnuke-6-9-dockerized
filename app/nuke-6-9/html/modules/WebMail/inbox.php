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

include ("header.php");
parse_str(base64_decode($pop3_cookie));
require ("modules/$module_name/pop3.php");
require ("modules/$module_name/decodemessage.php");
include ("modules/$module_name/mailheader.php");
include ("modules/$module_name/class.rc4crypt.php");

getServer($id);
set_time_limit(0);
$pop3=new POP3($server,$username,$password);
$pop3->Open();

if($op == "delete") {
    global $msgid;
    if(is_array($msgid)) {
	foreach($msgid as $mid) {
	    $pop3->DeleteMessage($mid);
	}
    } else {
	$pop3->DeleteMessage($msgid);
    }
    $pop3->Close();
    $pop3->Open();
}

$s = $pop3->Stats() ;
$mailsum = $s["message"];
global $start,$numshow;
if (!isset($start)) $upperlimit = $mailsum; else $upperlimit = $start;
$lowerlimit = $upperlimit - $numshow;
if ($lowerlimit < 0) $lowerlimit = 0;
$showstart =  $mailsum - $upperlimit + 1;
$showend = $mailsum - $lowerlimit;
echo "<form action=modules.php?name=$module_name&file=inbox method=post>
    <input type=hidden name=id value=$id>
    <input type=hidden name=op value='delete'>";
OpenTable();
$result = sql_query("select account from ".$prefix."_popsettings where id='$id' AND uid='$cookie[0]'", $dbi);
list($account) = sql_fetch_row($result, $dbi);
echo "<center><b>$account: "._EMAILINBOX."</b></center><br><br>";
echo "<table border=\"0\" width=100%>"
    ."<tr>"
    ."<td width=\"4%\" bgcolor=\"$bgcolor2\">&nbsp;</td>"
    ."<td width=\"25%\" bgcolor=\"$bgcolor2\"><b>"._FROM."</b></td>"
    ."<td width=\"51%\" bgcolor=\"$bgcolor2\"><b>"._SUBJECT."</b></font></td>"
    ."<td width=\"6%\" bgcolor=\"$bgcolor2\"><b>"._SIZE."</b></font></td>"
    ."<td width=\"14%\" bgcolor=\"$bgcolor2\"><b>"._DATE."</b></font></td>"
    ."</tr>";
for ($i=$upperlimit;$i>$lowerlimit;$i--) {
    $list = $pop3->ListMessage($i);
    echo "<tr><td bgcolor=\"$bgcolor1\" height=\"24\" align=\"center\"><input type=\"checkbox\" name=\"msgid[]\" value=\"$i\"></td>";
    if ($attachments_view == 0) {
	if ($list["has_attachment"]) {
    	    $att_exists = "&amp;attach_nv=1";
	} else {
	    $att_exists = "";
	}
    }
    echo "<td bgcolor=\"$bgcolor1\" height=\"24\"><a href=\"modules.php?name=$module_name&file=readmail&id=$id&msgid=$i$att_exists\">";
    $sender = ($list["sender"]["name"]) ? $list["sender"]["name"] : $list["sender"]["email"];
    echo htmlspecialchars(substr($sender,0,30));
    echo "</a>";
    echo (strlen($sender) > 30) ? "..." : "";
    echo "</a></font></td>";
    echo "<td bgcolor=\"$bgcolor1\"><a href=\"modules.php?name=$module_name&file=readmail&id=$id&msgid=$i$att_exists\">";
    echo chop($list["subject"]) ? htmlspecialchars($list["subject"]) : ""._NOSUBJECT."";
    echo "</td><td bgcolor=\"$bgcolor1\">";
    echo round($list["size"]/1024)."Kb";
    echo $list["has_attachment"] ? "<img src=\"$imgpath/clip.gif\" border=\"0\">" : "";
    echo "</td><td bgcolor=\"$bgcolor1\">";
    echo htmlspecialchars($list["date"]);
    echo "</font></td></tr>";
}
echo "</table>";
navbuttons();
echo "</form>";
$pop3->Close();
CloseTable();
include ("modules/$module_name/mailfooter.php");

function getServer($id) {
    global $user, $server, $port, $username, $password, $numshow, $prefix, $module_name, $dbi;
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
	if($uid != $userid) {
	    echo "<center><h2>Error: Permission Denied</center>";
	    exit();
	}
	$server = $row[popserver];
	$port = $row[port];
	$username = $row[uname];
	$rc4 = new rc4crypt();
	$password = $rc4->endecrypt($username,$row[passwd],"de");
	$numshow = $row[numshow];
    } else {
	echo "Error: POP Server not set properly<br>";
	exit();
    }
}

function navbuttons() {
    global $id, $showstart, $showend, $mailsum, $upperlimit, $lowerlimit, $numshow, $module_name;
    echo "<br>"
        ."<table border=\"0\" width=\"100%\">"
        ."<tr><td width=\"15%\">"
	."<input type=\"submit\" value=\""._DELETESELECTED."\"></td></tr></table>"
	."<table border=\"0\" width=\"100%\" align=\"center\">"
        ."<td width=\"70%\" align=\"center\">"._SHOWING." ($showstart - $showend) "._OF." $mailsum "._EMAILS."</td>";
    if ($upperlimit != $mailsum) {
	$ul = $upperlimit + $numshow;
        if ($ul > $mailsum) $ul = $mailsum;
	echo "<td width=\"7%\"><a href=\"modules.php?name=$module_name&file=inbox&id=$id&start=$ul\">"._PREVIOUS."</a></td>";
    }
    if ($lowerlimit > 0) {
	echo "<td width=\"7%\"><a href=\"modules.php?name=$module_name&file=inbox&id=$id&start=$lowerlimit\">"._NEXT."</a></td>";
    }
    echo "</tr></table>";
}

?>