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

include("../../config.php");

if (isset($userfile) AND $userfile != "none" AND !ereg("/", $userfile) AND !ereg("\.\.", $userfile) AND !ereg("%", $userfile)) {
    if (ini_get(file_uploads) AND $attachments == 1) {
	$updir = "tmp";
	@copy($userfile, "$updir/$userfile_name");
	@unlink($userfile);
    }
}

echo "<html>\n"
    ."<title>$sitename: Attach Files</title>\n"
    ."<body text=\"#63627f\">\n"
    ."<form action=\"mailattach.php\" method=\"post\" ENCTYPE=\"multipart/form-data\" name=\"attchform\">\n"
    ."<center>\n"
    ."<b>Attach Files</b><br><br>\n"
    ."File: <input type=\"file\" name=\"userfile\">&nbsp;&nbsp;<input type=\"submit\" value=\"Attach File\">\n"
    ."</center>\n"
    ."</form>\n"
    ."</body>\n"
    ."</html>";

?>