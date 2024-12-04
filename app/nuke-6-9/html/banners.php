<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

require_once("mainfile.php");

/********************************************/
/* Function to display banners in all pages */
/********************************************/

function viewbanner() {
    global $prefix, $db, $admin, $sitename, $adminmail, $nukeurl;
    $numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_banner WHERE type='0' AND active='1'"));

/* Get a random banner if exist any. */
/* More efficient random stuff, thanks to Cristian Arroyo from http://www.planetalinux.com.ar */

    if ($numrows>1) {
	$numrows = $numrows-1;
	mt_srand((double)microtime()*1000000);
	$bannum = mt_rand(0, $numrows);
    } else {
	$bannum = 0;
    }
    $sql = "SELECT bid, imageurl, clickurl, alttext FROM ".$prefix."_banner WHERE type='0' AND active='1' LIMIT $bannum,1";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $bid = $row[bid];
    $imageurl = $row[imageurl];
    $clickurl = $row[clickurl];
    $alttext = $row[alttext];
    
    if (!is_admin($admin)) {
    	$db->sql_query("UPDATE ".$prefix."_banner SET impmade=impmade+1 WHERE bid='$bid'");
    }
    if($numrows>0) {
	$sql2 = "SELECT cid, imptotal, impmade, clicks, date FROM ".$prefix."_banner WHERE bid='$bid'";
	$result2 = $db->sql_query($sql2);
	$row2 = $db->sql_fetchrow($result2);
	$cid = $row2[cid];
	$imptotal = $row2[imptotal];
	$impmade = $row2[impmade];
	$clicks = $row2[clicks];
	$date = $row2[date];

/* Check if this impression is the last one and print the banner */

	if (($imptotal <= $impmade) AND ($imptotal != 0)) {
	    $db->sql_query("UPDATE ".$prefix."_banner SET active='0' WHERE bid='$bid'");
	    $sql3 = "SELECT name, contact, email FROM ".$prefix."_bannerclient WHERE cid='$cid'";
	    $result3 = $db->sql_query($sql3);
	    $row3 = $db->sql_fetchrow($result3);
	    $c_name = $row3[name];
	    $c_contact = $row3[contact];
	    $c_email = $row3[email];
	    if ($c_email != "") {
		$from = "$sitename <$adminmail>";
		$to = "$c_contact <$c_email>";
		$message = ""._HELLO." $c_contact:\n\n";
		$message .= ""._THISISAUTOMATED."\n\n";
		$message .= ""._THERESULTS."\n\n";
		$message .= ""._TOTALIMPRESSIONS." $imptotal\n";
		$message .= ""._CLICKSRECEIVED." $clicks\n";
		$message .= ""._IMAGEURL." $imageurl\n";
		$message .= ""._CLICKURL." $clickurl\n";
		$message .= ""._ALTERNATETEXT." $alttext\n\n";
		$message .= ""._HOPEYOULIKED."\n\n";
		$message .= ""._THANKSUPPORT."\n\n";
		$message .= "- $sitename "._TEAM."\n";
		$message .= "$nukeurl";
		$subject = "$sitename: "._BANNERSFINNISHED."";
		mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
	    }
	}
    echo"<center><a href=\"banners.php?op=click&amp;bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt='$alttext' title='$alttext'></a></center>";
    }
}

/********************************************/
/* Function to redirect the clicks to the   */
/* correct url and add 1 click              */
/********************************************/

function clickbanner($bid) {
    global $prefix, $db;
    $sql = "SELECT clickurl FROM ".$prefix."_banner WHERE bid='$bid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_query("UPDATE ".$prefix."_banner SET clicks=clicks+1 WHERE bid='$bid'");
    Header("Location: $row[clickurl]");
}

/********************************************/
/* Function to let your client login to see */
/* the stats                                */
/********************************************/

function clientlogin() {
    echo"
    <html>
    <body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
    <center><br><br><br><br>
    <table width=\"200\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\" bgcolor=\"#000000\"><tr><td>
    <table width=\"100%\" cellpadding=\"5\" cellspacing=\"1\" border=\"0\" bgcolor=\"#FFFFFF\"><tr><td bgcolor=\"#EECFA1\">
    <center><b>Advertising Statistics</b></center>
    </td></tr><tr><td bgcolor=\"#FFFACD\">
    <form action=\"banners.php\" method=\"post\">
    Login: <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\"><br>
    Password: <input type=\"password\" name=\"pass\" size=\"12\" maxlength=\"10\"><br>
    <input type=\"hidden\" name=\"op\" value=\"Ok\">
    <input type=\"submit\" value=\"Login\">
    </td></tr><tr><td bgcolor=\"#EECFA1\">
    <font class=\"content\">
    <center>Please type your client information</center>
    </font></form>
    </td></tr></table></td></tr></table>
    </body>
    </html>
    ";
}

/*********************************************/
/* Function to display the banners stats for */
/* each client                               */
/*********************************************/

function bannerstats($login, $pass) {
    global $prefix, $db, $sitename;
    $sql = "SELECT cid, name, passwd FROM ".$prefix."_bannerclient WHERE login='$login'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $cid = $row[cid];
    $name = $row[name];
    $passwd = $row[passwd];
    if($login=="" AND $pass=="" OR $pass=="") {
	echo "<center><br>Login Incorrect!!!<br><br><a href=\"javascript:history.go(-1)\">Back to Login Screen</a></center>";
    } else {
    
    if ($pass==$passwd) {
    
    echo"
    <html>
    <body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
    <center>
    <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td>
    <font class=\"option\">
    <center><b>Current Active Banners for $name</b></center><br>
    </font>
    <table width=\"100%\" border=\"0\"><tr>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Made</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Total</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Left</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Functions</b></td><tr>";
    $sql = "SELECT bid, imptotal, impmade, clicks, date FROM ".$prefix."_banner WHERE cid='$cid' AND active='1'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$bid = $row[bid];
	$imptotal = $row[imptotal];
	$impmade = $row[impmade];
	$clicks = $row[clicks];
	$date = $row[date];
        if($impmade==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $clicks / $impmade, 0, 5);
        }
        if($imptotal==0) {
    	    $left = "Unlimited";
        } else {
    	    $left = $imptotal-$impmade;
        }
        echo "
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impmade</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$imptotal</td>
	<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$left</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\"><a href=\"banners.php?op=EmailStats&login=$login&cid=$cid&bid=$bid\">E-mail Stats</a></td><tr>";
    }
    echo "
    </table>
    <center><br><br>
    Following are your running Banners in $sitename<br><br>";

    $sql = "SELECT bid, imageurl, clickurl, alttext FROM ".$prefix."_banner WHERE cid='$cid' AND active='1'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$bid = $row[bid];
	$imageurl = $row[imageurl];
	$clickurl = $row[clickurl];
	$alttext = $row[alttext];

	$numrows = $db->sql_numrows($result);
	if ($numrows>1) {
	    echo "<hr noshade width=\"80%\"><br>";
	}

	echo "<img src=\"$imageurl\" border=\"1\"><br>
	<font class=\"content\">Banner ID: $bid<br>
	Send <a href=\"banners.php?op=EmailStats&login=$login&cid=$cid&bid=$bid\">E-Mail Stats</a> for this Banner<br>
	This Banners points to <a href=\"$clickurl\">this URL</a><br>
	<form action=\"banners.php\" method=\"submit\">
	Change URL: <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"200\" value=\"$clickurl\"><br>
	Change Text: <input type=\"text\" name=\"alttext\" size=\"50\" maxlength=\"255\" value=\"$alttext\"><br>
	<input type=\"hidden\" name=\"login\" value=\"$login\">
	<input type=\"hidden\" name=\"bid\" value=\"$bid\">
	<input type=\"hidden\" name=\"pass\" value=\"$pass\">
	<input type=\"hidden\" name=\"cid\" value=\"$cid\">
	<input type=\"submit\" name=\"op\" value=\"Change\"></form></font>";
    }
    echo "
    </td></tr></table></td></tr></table>
    ";


/* Finnished Banners */
    
    echo "
    <center><br>
    <table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"000000\"><tr><td>
    <table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td>
    <font class=\"option\">
    <center><b>Banners Finished for $name</b></center><br>
    </font>
    <table width=\"100%\" border=\"0\"><tr>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Impressions</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Start Date</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>End Date</b></td>
    <td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Functions</b></td></tr>";
    $sql = "SELECT bid, impmade, clicks, imageurl, date, dateend FROM ".$prefix."_banner WHERE cid='$cid' AND active='0'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
	$bid = $row[bid];
	$impmade = $row[impmade];
	$clicks = $row[clicks];
	$imageurl = $row[imageurl];
	$date = $row[date];
	$dateend = $row[dateend];
        $percent = substr(100 * $clicks / $impmade, 0, 5);
	echo "
        <tr><td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impmade</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td>
	<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$date</td>
        <td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$dateend</td>
	<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\"><a href=\"$imageurl\" target=\"new\">View Banner</a></td></tr>";
    }
echo "
</table></td></tr></table></td></tr></table>
<br><a href=\"http://phpnuke.org\"><img src=\"images/powered/phpnuke.gif\" border=\"0\" Alt=\"This site Powered by PHP-Nuke\"></a>
</body>
</html>
";
    
    } else {
	echo "<center><br>Login Incorrect!!!<br><br><a href=\"javascript:history.go(-1)\">Back to Login Screen</a></center>";
    }
}
}

/*********************************************/
/* Function to let the client E-mail his     */
/* banner Stats                              */
/*********************************************/

function EmailStats($login, $cid, $bid, $pass) {
    global $prefix, $db;
    $sql = "SELECT name, email FROM ".$prefix."_bannerclient WHERE cid='$cid'";
    $rsult = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $name = $row[name];
    $email = $row[email];
    if ($email=="") {
	echo "
	<html>
	<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
	<center><br><br><br>
	<b>Statistics for Banner No. $bid can't be send because<br>
	there isn't an email associated with client $name<br>
	Please contact the Administrator<br><br></b>
	<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>
	";
    } else {
	$sql2 = "SELECT bid, imptotal, impmade, clicks, imageurl, clickurl, date FROM ".$prefix."_banner WHERE bid='$bid' AND cid='$cid'";
	$result2 = $db->sql_query($sql2);
	$row2 = $db->sql_fetchrow($result2);
	$bid = $row2[bid];
	$imptotal = $row2[imptotal];
	$impmade = $row2[impmade];
	$clicks = $row2[clicks];
	$imageurl = $row2[imageurl];
	$clickurl = $row2[clickurl];
	$date = $row2[date];
        if($impmade==0) {
    	    $percent = 0;
        } else {
    	    $percent = substr(100 * $clicks / $impmade, 0, 5);
        }
        if($imptotal==0) {
    	    $left = "Unlimited";
	    $imptotal = "Unlimited";
        } else {
    	    $left = $imptotal-$impmade;
        }
	$fecha = date("F jS Y, h:iA.");
	$subject = "Your Banner Statistics at $sitename";
	$message = "Following are the complete stats for your advertising investment at $sitename:\n\n\nClient Name: $name\nBanner ID: $bid\nBanner Image: $imageurl\nBanner URL: $clickurl\n\nImpressions Purchased: $imptotal\nImpressions Made: $impmade\nImpressions Left: $left\nClicks Received: $clicks\nClicks Percent: $percent%\n\n\nReport Generated on: $fecha";
	$from = "$sitename";
	mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
	echo "
	<html>
	<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\">
	<center><br><br><br>
	<b>Statistics for Banner No. $bid has been send to<br>
	<i>$email</i> of $name<br><br></b>
	<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>
	";
    }
}

/*********************************************/
/* Function to let the client to change the  */
/* url for his banner                        */
/*********************************************/

function change_banner_url_by_client($login, $pass, $cid, $bid, $url, $alttext) {
    global $prefix, $db;
    $sql = "SELECT passwd FROM ".$prefix."_bannerclient WHERE cid='$cid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $passwd = $row[passwd];
    if (!empty($pass) AND $pass==$passwd) {
	$alttext = ereg_replace("\"", "", $alttext);
	$alttext = ereg_replace("'", "", $alttext);
	$db->sql_query("UPDATE ".$prefix."_banner SET clickurl='$url', alttext='$alttext' WHERE bid='$bid'");
	echo "<br><center>";
	if ($url != "") {
	    echo "You changed the URL<br>";
	}
	if ($alttext != "") {
	    echo "You changed the Alternate Text";
	}
	echo "<br><br><a href=\"javascript:history.go(-1)\">Back to Stats Page</a></center>";
    } else {
	echo "<center><br>Your login/password doesn't match.<br><br>Please <a href=\"banners.php?op=login\">login again</a></center>";
    }
    
}

switch($op) {

    case "click":
	clickbanner($bid);
	break;

    case "login":
	clientlogin();
	break;

    case "Ok":
	bannerstats($login, $pass);
	break;

    case "Change":
	change_banner_url_by_client($login, $pass, $cid, $bid, $url, $alttext);
	break;

    case "EmailStats":
	EmailStats($login, $cid, $bid, $pass);
	break;
	
    default:
	viewbanner();
	break;
}

?>