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

global $op, $domain;

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$pagetitle = "- "._MAILBOXESSETTINGS."";

if(!$user) {
    header ("Location: modules.php?name=Your_Account");
    exit();
}

include ("header.php");
include ("modules/$module_name/mailheader.php");
include ("modules/$module_name/class.rc4crypt.php");

title(""._MAILBOXESSETTINGS."");

if(isset($popserver)) {
    global $user, $type, $prefix;
    $auser = base64_decode($user);
    $userdata = explode(":", $auser);
    $userid = $userdata[0];
    $rc4 = new rc4crypt();
    $spasswd = $rc4->endecrypt($uname,$passwd,"en");
    if($leavemsg == "Y") $delete = "N"; else $delete = "Y";
    if($submit == ""._DELETE."") {
	$query = "Delete from ".$prefix."_popsettings where id='$id'";
    } elseif ($type == "new") {
	$query = "Insert into ".$prefix."_popsettings (account,uid,popserver,uname,passwd,port,numshow,deletefromserver) values ('$account','$userid','$popserver','$uname','$spasswd',$port,$numshow,'$delete')";
    } else {
	$query = "Update ".$prefix."_popsettings set account='$account', popserver = '$popserver', uname = '$uname', passwd = '$spasswd', port = $port, numshow = $numshow, deletefromserver = '$delete' where id='$id'";
    }
    $res = sql_query($query, $dbi);
    if(!$res) {
	echo "error: $query";
    }
}

$port = 110;
$show = 20;
$checkbox = "";
$acc_count = 0;
$showflag=true;
global $user;
$user1 = base64_decode($user);
$userdata = explode(":", $user1);
$userid = $userdata[0];
$query = "Select * from $prefix"._popsettings." where uid='$userid'";

if(($res2 = sql_query($query, $dbi)) && (sql_num_rows($res2, $dbi) > 0)) {
    $acc_count = sql_num_rows($res2, $dbi);
    $rc = new rc4crypt();
    while($row = sql_fetch_array($res2, $dbi)) {
	$id = $row[id];
	$account = $row[account];
	$popserver = $row[popserver];
	$port = $row[port];
	$uname = $row[uname];
	$passwd = $rc->endecrypt($uname,$row[passwd],"de");
	$delete = $row[deletefromserver];
	$show = $row[numshow];
	if($delete == "Y") $checkbox = "checked";
	showSettings($account,$popserver, $uname,$passwd, $port,$show,$checkbox,$id);
	if ($popserver == $defaultpopserver) $showflag = false;
    }
}

if (($defaultpopserver != "") && $showflag) {
    showSingle($defaultpopserver, $singleaccountname);
}

if ($singleaccount == 0 && ($numaccounts == -1) || ($acc_count < $numaccounts)) {
    showNew();
}

include ("modules/$module_name/mailfooter.php");

function showSettings($account,$popserver, $uname,$passwd, $port,$show,$checkbox,$id) {
    global $bgcolor1, $bgcolor2, $bgcolor3, $module_name, $singleaccount, $defaultpopserver;
    OpenTable();
    echo "<table width=\"80%\" align=\"center\" border=\"0\">"
	."<form method=\"post\" action=\"modules.php?name=$module_name&file=settings\" name=\"formpost\">"
	."<input type=\"hidden\" name=\"id\" value=\"$id\">"
        ."<input type=\"hidden\" name=\"type\" value=\"$account\">"
        ."<input type=\"hidden\" name=\"account\" value=\"$account\">"
        ."<tr><td bgcolor=\"$bgcolor2\" colspan=\"2\"><img src=\"images/arrow.gif\" border=\"0\" hspace=\"5\"><b>$account</b></td></tr>";
    if ($singleaccount == 1 AND $defaultpopserver != "") {
	echo "<tr><td align=\"left\">"._POPSERVER.":</td><td><input type=\"hidden\" name=\"popserver\" value=\"$popserver\">$popserver</td></tr>";
    } else {
	echo "<tr><td align=\"left\">"._POPSERVER.":</td><td><input type=\"text\" name=\"popserver\" value=\"$popserver\" size=\"40\"></td></tr>";
    }
    echo "<tr><td align=\"left\">"._USERNAME.":</td><td><input type=\"text\" name=\"uname\" size=\"20\" value=\"$uname\"></td></tr>"
        ."<tr><td align=\"left\">"._PASSWORD.":</td><td><input type=\"password\" name=\"passwd\" size=\"20\" value=\"$passwd\"></td></tr>"
        ."<tr><td>&nbsp;</td><td><font class=\"tiny\"><i>"._PASSWORDSECURE."</i></font></td></tr>"
        ."<tr><td align=\"left\">"._PORT.":</td><td><input type=\"text\" name=\"port\" size=\"6\" maxlength=\"5\" value=\"$port\"> </td></tr>"
        ."<tr><td align=\"left\">"._MESSAGESPERPAGE.":</td><td><input type=\"text\" name=\"numshow\" size=\"3\" maxlength=\"2\" value=\"$show\" value=\"10\"></td></tr>"
        ."<tr><td colspan=\"2\"><input type=\"submit\" name=\"submit\" value=\""._SAVE."\">&nbsp;&nbsp;<input type=\"submit\" name=\"submit\" value=\""._DELETE."\"></td></tr>"
        ."</table></form>";
    CloseTable();
    echo "<br>";
}

function showNew() {
    global $bgcolor1, $bgcolor2, $bgcolor3, $module_name;
    OpenTable();
    echo "<table width=80% align=center>
        <form method=post action=\"modules.php?name=$module_name&file=settings\" name=formpost>
        <tr><td bgcolor=\"$bgcolor2\" colspan=2>&nbsp;<b>New Mail Account</b></td></tr>
        <tr><td align=left>"._ACCOUNTNAME.":</td><td><input type=text name=account value=\"\" size=40 maxlength=\"50\"></td></tr>
        <tr><td align=left>"._POPSERVER.":</td><td><input type=text name=popserver value=\"\" size=40></td></tr>
        <tr><td align=left>"._USERNAME.":</td><td><input type=text name=uname size=20 value=\"\"> </td></tr>
        <tr><td align=left>"._PASSWORD.":</td><td><input type=password name=passwd size=20 value=\"\"></td></tr>
        <tr><td align=left>"._PORT.":</td><td><input type=text name=port size=6 maxlength=\"5\" value=\"110\"></td></tr>
        <tr><td align=left>"._MESSAGESPERPAGE.":</td><td><input type=text name=numshow size=3 maxlength=\"2\" value=\"10\"></td></tr>
        <input type=hidden name=type value=\"new\">
        <tr><td colspan=2><input type=submit name=submit value=\""._ADDNEW."\"></form></td></tr></table>";
    CloseTable();
}

function showSingle($defaultpopserver, $singleaccountname) {
    global $bgcolor1, $bgcolor2, $bgcolor3, $module_name;
    OpenTable();
    echo "<br><table width=80% align=center>
          <form method=post action=\"modules.php?name=$module_name&file=settings\" name=formpost>
          <input type=hidden name=type value=\"new\">
	  <input type=hidden name=port value=110>
	  <input type=hidden name=account value=\"$singleaccountname\">
          <input type=hidden name=popserver value=\"$defaultpopserver\">
          <tr><td bgcolor=\"$bgcolor2\" colspan=2>&nbsp;<b>$singleaccountname</b></td><td>&nbsp</td></tr>
          <tr><td align=left>"._USERNAME.":</td><td><input type=text name=uname size=20 value=\"\"></td></tr>
          <tr><td align=left>"._PASSWORD.":</td><td><input type=password name=passwd size=20 value=\"\"></td></tr>
          <tr><td align=left>"._MESSAGESPERPAGE.":</td><td><input type=text name=numshow size=3 maxlength=\"2\" value=\"10\"></td></tr>
          <tr><td colspan=2><input type=submit name=submit value=\""._ADD."\"></form></td></tr></table>";
    CloseTable();
}

?>