<?php

if (!eregi("modules.php", $_SERVER["PHP_SELF"])) {
    die("You can't access this file directly...");
}

if (is_user($user)) {

require_once("mainfile.php");
get_lang("Your_Account");

function menuimg($gfile) {
    $ThemeSel = get_theme();
    if (file_exists("themes/$ThemeSel/images/menu/$gfile")) {
	$menuimg = "themes/$ThemeSel/images/menu/$gfile";
    } else {
	$menuimg = "modules/Your_Account/images/$gfile";
    }
    return($menuimg);
}

function nav($main_up=0) {
    global $module_name;
    $handle=opendir('themes');
    while ($file = readdir($handle)) {
	if ( (!ereg("[.]",$file)) ) {
		$thmcount++;
	}
    }
    closedir($handle);
    echo "<table border=\"0\" width=\"100%\" align=\"center\"><tr><td width=\"10%\">";

    $menuimg = menuimg("info.gif");
    echo "<font class=\"content\">"
	."<center><a href=\"modules.php?name=Your_Account&amp;op=edituser\"><img src=\"$menuimg\" border=\"0\" alt=\""._CHANGEYOURINFO."\" title=\""._CHANGEYOURINFO."\"></a><br>"
	."<a href=\"modules.php?name=Your_Account&amp;op=edituser\">"._CHANGEYOURINFO."</a>"
	."</center></font></td>";

    $menuimg = menuimg("home.gif");
    echo "<td width=\"10%\"><font class=\"content\">"
	."<center><a href=\"modules.php?name=Your_Account&amp;op=edithome\"><img src=\"$menuimg\" border=\"0\" alt=\""._CHANGEHOME."\" title=\""._CHANGEHOME."\"></a><br>"
	."<a href=\"modules.php?name=Your_Account&amp;op=edithome\">"._CHANGEHOME."</a>"
	."</center></form></font></td>";

    if ($articlecomm == 1) {
	$menuimg = menuimg("comments.gif");
	echo "<td width=\"10%\"><font class=\"content\">"
	    ."<center><a href=\"modules.php?name=Your_Account&amp;op=editcomm\"><img src=\"$menuimg\" border=\"0\" alt=\""._CONFIGCOMMENTS."\" title=\""._CONFIGCOMMENTS."\"></a><br>"
	    ."<a href=\"modules.php?name=Your_Account&amp;op=editcomm\">"._CONFIGCOMMENTS."</a>"
	    ."</center></form></font></td>";
    }

    if (is_active("WebMail")) {
	$menuimg = menuimg("mail.gif");
	echo "<td width=\"10%\"><font class=\"content\">"
	    ."<center><a href=\"modules.php?name=WebMail\"><img src=\"$menuimg\" border=\"0\" alt=\""._WEBMAIL."\" title=\""._WEBMAIL."\"></a><br>"
	    ."<a href=\"modules.php?name=WebMail\">"._WEBMAIL."</a>"
	    ."</center></form></font></td>";
    }

    if (is_active("Private_Messages")) {
	$menuimg = menuimg("messages.gif");
	echo "<td width=\"10%\"><font class=\"content\">"
	    ."<center><a href=\"modules.php?name=Private_Messages\"><img src=\"$menuimg\" border=\"0\" alt=\""._PRIVATEMESSAGES."\" title=\""._PRIVATEMESSAGES."\"></a><br>"
	    ."<a href=\"modules.php?name=Private_Messages\">"._MESSAGES."</a>"
	    ."</center></form></font></td>";
    }

    if (is_active("Journal")) {
	$menuimg = menuimg("journal.gif");
	echo "<td width=\"10%\"><font class=\"content\">"
	    ."<center><a href=\"modules.php?name=Journal&amp;file=edit\"><img src=\"$menuimg\" border=\"0\" alt=\""._JOURNAL."\" title=\""._JOURNAL."\"></a><br>"
	    ."<a href=\"modules.php?name=Journal&amp;file=edit\">"._JOURNAL."</a>"
	    ."</center></form></font></td>";
    }

    if ($thmcount > 1) {
	$menuimg = menuimg("themes.gif");
	echo "<td width=\"10%\"><font class=\"content\">"
	    ."<center><a href=\"modules.php?name=Your_Account&amp;op=chgtheme\"><img src=\"$menuimg\" border=\"0\" alt=\""._SELECTTHETHEME."\" title=\""._SELECTTHETHEME."\"></a><br>"
	    ."<a href=\"modules.php?name=Your_Account&amp;op=chgtheme\">"._SELECTTHETHEME."</a>"
	    ."</center></form></font></td>";
    }

    $menuimg = menuimg("exit.gif");
    echo "<td width=\"10%\"><font class=\"content\">"
	."<center><a href=\"modules.php?name=Your_Account&amp;op=logout\"><img src=\"$menuimg\" border=\"0\" alt=\""._LOGOUTEXIT."\" title=\""._LOGOUTEXIT."\"></a><br>"
	."<a href=\"modules.php?name=Your_Account&amp;op=logout\">"._LOGOUTEXIT."</a>"
	."</center></form></font>";

    echo "</td></tr></table>";
    if ($main_up != 1) {
	echo "<br><center>[ <a href=\"modules.php?name=Your_Account\">"._RETURNACCOUNT."</a> ]</center>\n";
    }
}

}

?>