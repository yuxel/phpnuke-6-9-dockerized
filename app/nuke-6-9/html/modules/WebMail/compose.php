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

if ($email_send == 1) {

if(!isset($user)) {
    Header("Location: modules.php?name=Your_Account");
    exit();
}
$user2 = base64_decode($user);
$userdata = explode(":", $user2);
$userid = $userdata[0];
$numrows = sql_num_rows(sql_query("select * from ".$prefix."_popsettings where uid='$userid'", $dbi), $dbi);
if ($numrows == 0 OR $numrows == "") {
    Header("Location: modules.php?name=$module_name");
}

$pagetitle = "- "._COMPOSEEMAIL."";

include ("header.php");
include ("modules/$module_name/mailheader.php");
$body = stripslashes($body);
$to = stripslashes($to);
$subject = stripslashes($subject);

if(isset($op)) {
    if($op == "reply") $subject = "Re: ".$subject;
    else if($op == "forward") $subject = "Fwd: ".$subject;
    if (eregi($body,"<br>",$out)) {
	$bodytext = explode("<br>",$body);
        foreach($bodytext as $bt) {
	    $content .= "> ".$bt;
        }
    } else {
        $bodytext = explode("\n",$body);
        foreach($bodytext as $bt) {
            $content .= "> ".$bt."\n";
        }
    }
}

title(""._COMPOSEEMAIL."");
OpenTable();

if (ini_get(file_uploads) AND $attachments == 1) {
    echo "<script language=\"javascript\">\n"
	."function open_w(file) {\n"
	."    newwin = window.open(file,'Attachments','width=450, height=250, scrollbars=no, toolbar=no');\n"
	."}\n"
	."\n"
	."function attachfiles(files,types) {\n"
	."    window.document.all.Atts.innerText = files;\n"
	."    document.emailform.attachment.value = files;\n"
	."    document.emailform.attchtype.value = types;\n"
	."}\n"
	."</script>\n";
}

echo "<b>"._SENDANEMAIL."</b><br><br>"
    ."<form method=\"post\" action=\"modules.php?name=$module_name&file=nlmail\" name=\"emailform\">"
    ."<table align=\"center\" width=\"98%\">"
    ."<tr><td>"._TO.":</td><td width=100%><input type=text name=\"to\" size=47 value='$to'></td></tr>"
    ."<tr><td>&nbsp;</td><td><font class=\"tiny\"><i>"._SEPARATEEMAILS."</i></font></td></tr>"
    ."<tr><td>"._SUBJECT.":</td><td><input type=text name=\"subject\" size=47 value='$subject'></td></tr>"
    ."<tr><td><i>Cc:</i></td><td><input type=text name=\"cc\" size=20>&nbsp;&nbsp;<i>Bcc:</i> <input type=text name=\"bcc\" size=19></td></tr>"
    ."<tr><td>"._PRIORITY.":</td><td><select name=\"prior\">"
    ."<option value=\"1\">"._HIGH."</option>"
    ."<option value=\"3\" selected>"._NORMAL."</option>"
    ."<option value=\"4\">"._LOW."</option>"
    ."</select>"
    ."</td>"
    ."</tr>"
    ."<tr><td><br>"._MESSAGE.":</td></tr>"
    ."<tr><td colspan=\"2\">"
    ."<textarea name=\"message\" rows=\"15\" cols=\"70\" wrap=\"virtual\">$content</textarea>"
    ."</td></tr>";

if (ini_get(file_uploads) AND $attachments == 1) {
    echo "<tr><td colspan=2>";
    OpenTable();
    echo ""._ATTACHMENTS.": <span style=\"background-color:#ffffcc\" id=\"Atts\">"._NONE."</span> &nbsp;<br><br><a href=\"javascript: open_w('modules/$module_name/mailattach.php')\">"._CLICKTOATTACH."</a><br>";
    CloseTable();
}

echo "<tr><td colspan=\"2\">"
    ."<input type=\"submit\" name=\"send\" value=\""._SENDMESSAGE."\">&nbsp;&nbsp;<input type=\"reset\" value=\""._CLEARALL."\">"
    ."</td></tr>"
    ."</table>"
    ."</center>"
    ."<input type=hidden name=\"attachment\">"
    ."<input type=hidden name=\"attchtype\">"
    ."</form>";

Closetable();
include ("modules/$module_name/mailfooter.php");

} else {
    Header("Location: modules.php?name=$module_name");
    exit();
}

?>