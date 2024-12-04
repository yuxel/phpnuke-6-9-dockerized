<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

if ($multilingual == 1) {
    $queryalang = "AND (s.alanguage='$currentlang' OR s.alanguage='')"; /* stories */
    $queryrlang = "AND rlanguage='$currentlang' "; /* reviews */
    $queryslang = "AND slanguage='$currentlang' "; /* sections */
} else {
    $queryalang = "";
    $queryrlang = "";
    $queryslang = "";
}

switch($op) {

        case "comments":
                break;

        default:
		$ThemeSel = get_theme();
                $offset=10;
                if (!isset($min)) $min=0;
                if (!isset($max)) $max=$min+$offset;
                $query = stripslashes(check_html($query, nohtml));
		$pagetitle = "- "._SEARCH."";
                include("header.php");
		if ($topic>0) {
		    $result = sql_query("select topicimage, topictext from ".$prefix."_topics where topicid='$topic'", $dbi);
		    list($topicimage, $topictext) = sql_fetch_row($result, $dbi);
		    if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
			$topicimage = "themes/$ThemeSel/images/topics/$topicimage";
		    } else {
			$topicimage = "$tipath/$topicimage";
		    }
		} else {
		    $topictext = ""._ALLTOPICS."";
		    if (file_exists("themes/$ThemeSel/images/topics/AllTopics.gif")) {
			$topicimage = "themes/$ThemeSel/images/topics/AllTopics.gif";
		    } else {
			$topicimage = "$tipath/AllTopics.gif";
		    }
		}
		if (file_exists("themes/$ThemeSel/images/topics/AllTopics.gif")) {
		    $alltop = "themes/$ThemeSel/images/topics/AllTopics.gif";
		} else {
		    $alltop = "$tipath/AllTopics.gif";
		}
		OpenTable();
		if ($type == "users") {
		    echo "<center><font class=\"title\"><b>"._SEARCHUSERS."</b></font></center><br>";
		} elseif ($type == "sections") {
		    echo "<center><font class=\"title\"><b>"._SEARCHSECTIONS."</b></font></center><br>";
		} elseif ($type == "reviews") {
		    echo "<center><font class=\"title\"><b>"._SEARCHREVIEWS."</b></font></center><br>";
		} elseif ($type == "comments" AND isset($sid)) {
		    $res = sql_query("select title from ".$prefix."_stories where sid='$sid'", $dbi);
		    list($st_title) = sql_fetch_row($res, $dbi);
	    	    $instory = "AND sid='$sid'";
		    echo "<center><font class=\"title\"><b>"._SEARCHINSTORY." $st_title</b></font></center><br>";
		} else {
		    echo "<center><font class=\"title\"><b>"._SEARCHIN." $topictext</b></font></center><br>";
		}
		
		echo "<table width=\"100%\" border=\"0\"><TR><TD>";
		if (($type == "users") OR ($type == "sections") OR ($type == "reviews")) {
		    echo "<img src=\"$alltop\" align=\"right\" border=\"0\" alt=\"\">";
                } else {
		    echo "<img src=\"$topicimage\" align=\"right\" border=\"0\" alt=\"$topictext\">";
		}
		echo "<form action=\"modules.php?name=$module_name\" method=\"POST\">"
            	    ."<input size=\"25\" type=\"text\" name=\"query\" value=\"$query\">&nbsp;&nbsp;"
		    ."<input type=\"submit\" value=\""._SEARCH."\"><br><br>";
		if (isset($sid)) {
		    echo "<input type='hidden' name='sid' value='$sid'>";
		}
            	echo "<!-- Topic Selection -->";
		$toplist = sql_query("select topicid, topictext from ".$prefix."_topics order by topictext", $dbi);
		echo "<select name=\"topic\">";
                echo "<option value=\"\">"._ALLTOPICS."</option>\n";
                while(list($topicid, $topics) = sql_fetch_row($toplist, $dbi)) {
                        if ($topicid==$topic) { $sel = "selected "; }
                        echo "<option $sel value=\"$topicid\">$topics</option>\n";
			$sel = "";
                }
		echo "</select>";
		/* Category Selection */
		echo "&nbsp;<select name=\"category\">";
                echo "<option value=\"0\">"._ARTICLES."</option>\n";
		$catlist = sql_query("select catid, title from ".$prefix."_stories_cat order by title", $dbi);
                while(list($catid, $title) = sql_fetch_row($catlist, $dbi)) {
                        if ($catid==$category) { $sel = "selected "; }
                        echo "<option $sel value=\"$catid\">$title</option>\n";
			$sel = "";
                }
		echo "</select>";
		/* Authors Selection */
                $thing = sql_query("select aid from ".$prefix."_authors order by aid", $dbi);
		echo "&nbsp;<select name=\"author\">";
                echo "<option value=\"\">"._ALLAUTHORS."</option>\n";
                while(list($authors) = sql_fetch_row($thing, $dbi)) {
                        if ($authors==$author) { $sel = "selected "; }
			echo "<option value=\"$authors\">$authors</option>\n";
			$sel = "";
                }
                echo "</select>";
                /* Date Selection */
                ?>
		&nbsp;<select name="days">
                        <option <?php echo $days == 0 ? "selected " : ""; ?> value="0"><?php echo _ALL ?></option>
                        <option <?php echo $days == 7 ? "selected " : ""; ?> value="7">1 <?php echo _WEEK ?></option>
                        <option <?php echo $days == 14 ? "selected " : ""; ?> value="14">2 <?php echo _WEEKS ?></option>
                        <option <?php echo $days == 30 ? "selected " : ""; ?> value="30">1 <?php echo _MONTH ?></option>
			<option <?php echo $days == 60 ? "selected " : ""; ?> value="60">2 <?php echo _MONTHS ?></option>
                        <option <?php echo $days == 90 ? "selected " : ""; ?> value="90">3 <?php echo _MONTHS ?></option>
                </select><br>
		<?php
		if (($type == "stories") OR ($type == "")) {
		    $sel1 = "checked";
		} elseif ($type == "comments") {
		    $sel2 = "checked";
		} elseif ($type == "sections") {
		    $sel3 = "checked";
		} elseif ($type == "users") {
		    $sel4 = "checked";
		} elseif ($type == "reviews") {
		    $sel5 = "checked";
		}

		$num_sec = sql_num_rows(sql_query("select * from ".$prefix."_sections", $dbi), $dbi);
		$num_rev = sql_num_rows(sql_query("select * from ".$prefix."_reviews", $dbi), $dbi);

		echo ""._SEARCHON."";
		echo "<input type=\"radio\" name=\"type\" value=\"stories\" $sel1> "._SSTORIES."";
		if ($articlecomm == 1) {
		    echo "<input type=\"radio\" name=\"type\" value=\"comments\" $sel2> "._SCOMMENTS."";
		}
		if ($num_sec > 0) {
		    echo "<input type=\"radio\" name=\"type\" value=\"sections\" $sel3> "._SSECTIONS."";
		}
		echo "<input type=\"radio\" name=\"type\" value=\"users\" $sel4> "._SUSERS."";
		if ($num_rev > 0) {
		    echo "<input type=\"radio\" name=\"type\" value=\"reviews\" $sel5> "._REVIEWS."";
                }
		echo "</form></td></tr></table>";
	$query = addslashes($query);
	if ($type=="stories" OR !$type) {

		if ($category > 0) {
		    $categ = "AND catid=$category ";
		} elseif ($category == 0) {
		    $categ = "";
		}
                $q = "select s.sid, s.aid, s.informant, s.title, s.time, s.hometext, s.bodytext, a.url, s.comments, s.topic from ".$prefix."_stories s, ".$prefix."_authors a where s.aid=a.aid $queryalang $categ";
                if (isset($query)) $q .= "AND (s.title LIKE '%$query%' OR s.hometext LIKE '%$query%' OR s.bodytext LIKE '%$query%' OR s.notes LIKE '%$query%') ";
                if ($author != "") $q .= "AND s.aid='$author' ";
                if ($topic != "") $q .= "AND s.topic='$topic' ";
                if ($days != "" && $days!=0) $q .= "AND TO_DAYS(NOW()) - TO_DAYS(time) <= '$days' ";
                $q .= " ORDER BY s.time DESC LIMIT $min,$offset";
		$t = $topic;
                $result = sql_query($q, $dbi);
                $nrows  = sql_num_rows($result, $dbi);
                $x=0;
	    if ($query != "") {
		echo "<br><hr noshade size=\"1\"><center><b>"._SEARCHRESULTS."</b></center><br><br>";
                echo "<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		if ($nrows>0) {
                        while(list($sid, $aid, $informant, $title, $time, $hometext, $bodytext, $url, $comments, $topic) = sql_fetch_row($result, $dbi)) {

			$result2 = sql_query("select topictext from ".$prefix."_topics where topicid='$topic'", $dbi);
			list($topictext) = sql_fetch_row($result2, $dbi);

			        $furl = "modules.php?name=News&file=article&sid=$sid";
                                $datetime = formatTimestamp($time);
				$query = stripslashes($query);
				if ($informant == "") {
				    $informant = $anonymous;
				} else {
				    $informant = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant\">$informant</a>";
				}
				if ($query != "") {
				    if (eregi("$query",$title)) {
					$a = 1;
				    }
				    $text = "$hometext$bodytext";
				    if (eregi("$query",$text)) {
					$a = 2;
				    }
				    if (eregi("$query",$text) AND eregi("$query",$title)) {
					$a = 3;
				    }
				    if ($a == 1) {
					$match = _MATCHTITLE;
				    } elseif ($a == 2) {
					$match = _MATCHTEXT;
				    } elseif ($a == 3) {
					$match = _MATCHBOTH;
				    }
				    if (!isset($a)) {
					$match = "";
				    } else {
					$match = "$match<br>";
				    }
				}
                                printf("<tr><td><img src=\"images/folders.gif\" border=\"0\" alt=\"\">&nbsp;<font class=\"option\"><a href=\"%s\"><b>%s</b></a></font><br><font class=\"content\">"._CONTRIBUTEDBY." $informant<br>"._POSTEDBY." <a href=\"%s\">%s</a>",$furl,$title,$url,$aid,$informant);
                                echo " "._ON." $datetime<br>"
				    ."$match"
				    .""._TOPIC.": <a href=\"modules.php?name=$module_name&amp;query=&amp;topic=$topic\">$topictext</a> ";
				if ($comments == 0) {
				    echo "("._NOCOMMENTS.")";
				} elseif ($comments == 1) {
				    echo "($comments "._UCOMMENT.")";
                                } elseif ($comments >1) {
				    echo "($comments "._UCOMMENTS.")";
				}
				if (is_admin($admin)) {
				    echo " [ <a href=\"admin.php?op=EditStory&amp;sid=$sid\">"._EDIT."</a> | <a href=\"admin.php?op=RemoveStory&amp;sid=$sid\">"._DELETE."</a> ]";
				}
				echo "</font><br><br><br></td></tr>\n";
				$x++;
                        }

		echo "</table>";
		} else {
                        echo "<tr><td><center><font class=\"option\"><b>"._NOMATCHES."</b></font></center><br><br>";
			echo "</td></tr></table>";
                }

                $prev=$min-$offset;
                if ($prev>=0) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type&amp;category=$category\">";
                        print "<b>$min "._PREVMATCHES."</b></a></center>";
                }

                $next=$min+$offset;
		if ($x>=9) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type&amp;category=$category\">";
                        print "<b>"._NEXTMATCHES."</b></a></center>";
                }
	    }

	} elseif ($type=="comments") {
/*
	    if (isset($sid)) {
		$res = sql_query("select title from ".$prefix."_stories where sid='$sid'", $dbi);
		list($st_title) = sql_fetch_row($res, $dbi);
		$instory = "AND sid='$sid'";
	    } else {
		$instory = "";
	    }
*/
            $result = sql_query("select tid, sid, subject, date, name from ".$prefix."_comments where (subject like '%$query%' OR comment like '%$query%') $instory order by date DESC limit $min,$offset", $dbi);
            $nrows  = sql_num_rows($result, $dbi);
            $x=0;
	    if ($query != "") {
		echo "<br><hr noshade size=\"1\"><center><b>"._SEARCHRESULTS."</b></center><br><br>";
		echo "<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		if ($nrows>0) {
                        while(list($tid, $sid, $subject, $date, $name) = sql_fetch_row($result, $dbi)) {
			    $res = sql_query("select title from ".$prefix."_stories where sid='$sid'", $dbi);
			    list($title) = sql_fetch_row($res, $dbi);
			    $reply = sql_num_rows(sql_query("select * from ".$prefix."_comments where pid='$tid'", $dbi), $dbi);
			    $furl = "modules.php?name=News&amp;file=article&amp;thold=-1&amp;mode=flat&amp;order=1&amp;sid=$sid#$tid";
                            if(!$name) {
				$name = "$anonymous";
			    } else {
				$name = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$name\">$name</a>";
			    }
			    $datetime = formatTimestamp($date);
                            echo "<tr><td><img src=\"images/folders.gif\" border=\"0\" alt=\"\">&nbsp;<font class=\"option\"><a href=\"$furl\"><b>$subject</b></a></font><font class=\"content\"><br>"._POSTEDBY." $name"
                        	." "._ON." $datetime<br>"
				.""._ATTACHART.": $title<br>";
			    if ($reply == 1) {
				echo "($reply "._SREPLY.")";
				if (is_admin($admin)) {
				    echo " [ <a href=\"admin.php?op=RemoveComment&amp;tid=$tid&amp;sid=$sid\">"._DELETE."</a> ]";
				}
				echo "<br><br><br></td></tr>\n";
			    } else {
				echo "($reply "._SREPLIES.")";
				if (is_admin($admin)) {
				    echo " [ <a href=\"admin.php?op=RemoveComment&amp;tid=$tid&amp;sid=$sid\">"._DELETE."</a> ]";
				}
				echo "<br><br><br></td></tr>\n";
			    }
                            $x++;
                        }

		echo "</table>";
		} else {
                        echo "<tr><td><center><font class=\"option\"><b>"._NOMATCHES."</b></font></center><br><br>";
			echo "</td></tr></table>";
                }

                $prev=$min-$offset;
                if ($prev>=0) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$topic&amp;min=$prev&amp;query=$query&amp;type=$type\">";
                        print "<b>$min "._PREVMATCHES."</b></a></center>";
                }

                $next=$min+$offset;
		if ($x>=9) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$topic&amp;min=$max&amp;query=$query&amp;type=$type\">";
                        print "<b>"._NEXTMATCHES."</b></a></center>";
                }
	    }
	} elseif ($type=="reviews") {

            $result = sql_query("select id, title, text, reviewer, score from ".$prefix."_reviews where (title like '%$query%' OR text like '%$query%') $queryrlang order by date DESC limit $min,$offset", $dbi);
            $nrows  = sql_num_rows($result, $dbi);
            $x=0;
	    if ($query != "") {
		echo "<br><hr noshade size=\"1\"><center><b>"._SEARCHRESULTS."</b></center><br><br>";
		echo "<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		if ($nrows>0) {
                    while(list($id, $title, $text, $reviewer, $score) = sql_fetch_row($result, $dbi)) {
			$furl = "modules.php?name=Reviews&amp;op=showcontent&amp;id=$id";
			$pages = count(explode( "<!--pagebreak-->", $text ));
                        echo "<tr><td><img src=\"images/folders.gif\" border=\"0\" alt=\"\">&nbsp;<font class=\"option\"><a href=\"$furl\"><b>$title</b></a></font><br>"
			    ."<font class=\"content\">"._POSTEDBY." $reviewer<br>"
			    .""._REVIEWSCORE.": $score/10<br>";
			if ($pages == 1) {
			    echo "($pages "._PAGE.")";
                        } else {
			    echo "($pages "._PAGES.")";
			}
			if (is_admin($admin)) {
			    echo " [ <a href=\"modules.php?name=Reviews&amp;op=mod_review&amp;id=$id\">"._EDIT."</a> | <a href=\"modules.php?name=Reviews.php&amp;op=del_review&amp;id_del=$id\">"._DELETE."</a> ]";
			}
                        print "<br><br><br></font></td></tr>\n";
                        $x++;
                    }
		    echo "</table>";
		} else {
                    echo "<tr><td><center><font class=\"option\"><b>"._NOMATCHES."</b></font></center><br><br>";
		    echo "</td></tr></table>";
                }

                $prev=$min-$offset;
                if ($prev>=0) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\">";
                        print "<b>$min "._PREVMATCHES."</b></a></center>";
                }

                $next=$min+$offset;
		if ($x>=9) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\">";
                        print "<b>"._NEXTMATCHES."</b></a></center>";
                }
	    }
	} elseif ($type=="sections") {

            $result = sql_query("select artid, secid, title, content from ".$prefix."_seccont where (title like '%$query%' OR content like '%$query%') $queryslang order by artid DESC limit $min,$offset", $dbi);
            $nrows  = sql_num_rows($result, $dbi);
            $x=0;
	    if ($query != "") {
		echo "<br><hr noshade size=\"1\"><center><b>"._SEARCHRESULTS."</b></center><br><br>";
		echo "<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		if ($nrows>0) {
                        while(list($artid, $secid, $title, $content) = sql_fetch_row($result, $dbi)) {
			    $pages = count(explode( "<!--pagebreak-->", $content ));
			    $result2 = sql_query("select secname from ".$prefix."_sections where secid='$secid'", $dbi);
			    list($sectitle) = sql_fetch_row($result2, $dbi);
			    $surl = "modules.php?name=Sections&amp;op=listarticles&amp;secid=$secid";
			    $furl = "modules.php?name=Sections&amp;op=viewarticle&amp;artid=$artid";
                            echo "<tr><td><img src=\"images/folders.gif\" border=\"0\" alt=\"\">&nbsp;<font class=\"option\"><a href=\"$furl\"><b>$title</b></a></font><font class=\"content\"><br>"._INSECTION.": <a href=\"$surl\">$sectitle</a><br>";
			    if ($pages == 1) {
				echo "($pages "._PAGE.")";
                            } else {
				echo "($pages "._PAGES.")";
			    }
			    if (is_admin($admin)) {
				echo " [ <a href=\"admin.php?op=secartedit&amp;artid=$artid\">"._EDIT."</a> | <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">"._DELETE."</a> ]";
			    }
			    echo "</font><br><br><br></td></tr>\n";
			    $x++;
                        }

		echo "</table>";
		} else {
                        echo "<tr><td><center><font class=\"option\"><b>"._NOMATCHES."</b></font></center><br><br>";
			echo "</td></tr></table>";
                }

                $prev=$min-$offset;
                if ($prev>=0) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\">";
                        print "<b>$min "._PREVMATCHES."</b></a></center>";
                }

                $next=$min+$offset;
		if ($x>=9) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\">";
                        print "<b>"._NEXTMATCHES."</b></a></center>";
                }
	    }
	} elseif ($type=="users") {

            $result = sql_query("select user_id, username, name from ".$user_prefix."_users where (username like '%$query%' OR name like '%$query%' OR bio like '%$query%') order by username ASC limit $min,$offset", $dbi);
            $nrows  = sql_num_rows($result, $dbi);
            $x=0;
	    if ($query != "") {
		echo "<br><hr noshade size=\"1\"><center><b>"._SEARCHRESULTS."</b></center><br><br>";
		echo "<table width=\"99%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		if ($nrows>0) {
                        while(list($uid, $uname, $name) = sql_fetch_row($result, $dbi)) {
			    $furl = "modules.php?name=Your_Account&amp;op=userinfo&amp;username=$uname";
			    if ($name=="") {
				$name = ""._NONAME."";
			    }
                            echo "<tr><td><img src=\"images/folders.gif\" border=\"0\" alt=\"\">&nbsp;<font class=\"option\"><a href=\"$furl\"><b>$uname</b></a></font><font class=\"content\"> ($name)";
			    if (is_admin($admin)) {
				echo " [ <a href=\"admin.php?chng_uid=$uid&amp;op=modifyUser\">"._EDIT."</a> | <a href=\"admin.php?op=delUser&amp;chng_uid=$uid\">"._DELETE."</a> ]";
			    }
			    echo "</font></td></tr>\n";
                            $x++;
                        }

		echo "</table>";
		} else {
                        echo "<tr><td><center><font class=\"option\"><b>"._NOMATCHES."</b></font></center><br><br>";
			echo "</td></tr></table>";
                }

                $prev=$min-$offset;
                if ($prev>=0) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$prev&amp;query=$query&amp;type=$type\">";
                        print "<b>$min "._PREVMATCHES."</b></a></center>";
                }

                $next=$min+$offset;
		if ($x>=9) {
                        print "<br><br><center><a href=\"modules.php?name=$module_name&amp;author=$author&amp;topic=$t&amp;min=$max&amp;query=$query&amp;type=$type\">";
                        print "<b>"._NEXTMATCHES."</b></a></center>";
                }
	    }
	}
    CloseTable();
    if (isset($query) AND $query != "") {
	echo "<br>";
	if (is_active("Downloads")) { 
	    $dcnt = sql_num_rows(sql_query("select * from ".$prefix."_downloads_downloads WHERE title LIKE '%$query%' OR description LIKE '%$query%'", $dbi), $dbi);
	    $mod1 = "<li> <a href=\"modules.php?name=Downloads&amp;d_op=search&amp;query=$query\">"._DOWNLOADS."</a> ($dcnt "._SEARCHRESULTS.")";
	}
	if (is_active("Web_Links")) {
	    $lcnt = sql_num_rows(sql_query("select * from ".$prefix."_links_links WHERE title LIKE '%$query%' OR description LIKE '%$query%'", $dbi), $dbi);
	    $mod2 = "<li> <a href=\"modules.php?name=Web_Links&amp;l_op=search&amp;query=$query\">"._WEBLINKS."</a> ($lcnt "._SEARCHRESULTS.")";
	}
	if (is_active("Encyclopedia")) {
	    $ecnt1 = sql_query("select eid from ".$prefix."_encyclopedia WHERE active='1'", $dbi);
	    $ecnt = 0;
	    while(list($eid) = sql_fetch_row($ecnt1, $dbi)) {
		$ecnt2 = sql_num_rows(sql_query("select * from ".$prefix."_encyclopedia WHERE title LIKE '%$query%' OR description LIKE '%$query%' AND eid='$eid'", $dbi), $dbi);
		$ecnt3 = sql_num_rows(sql_query("select * from ".$prefix."_encyclopedia_text WHERE title LIKE '%$query%' OR text LIKE '%$query%' AND eid='$eid'", $dbi), $dbi);
		$ecnt = $ecnt+$ecnt2+$ecnt3;
	    }
	    $mod3 = "<li> <a href=\"modules.php?name=Encyclopedia&amp;file=search&amp;query=$query\">"._ENCYCLOPEDIA."</a> ($ecnt "._SEARCHRESULTS.")";
	}
	OpenTable();
	echo "<font class=\"title\">"._FINDMORE."<br><br>"
	    .""._DIDNOTFIND."</font><br><br>"
	    .""._SEARCH." \"<b>$query</b>\" "._ON.":<br><br>"
	    ."<ul>"
	    ."$mod1"
	    ."$mod2"
	    ."$mod3"
	    ."<li> <a href=\"http://www.google.com/search?q=$query\" target=\"new\">Google</a>"
	    ."<li> <a href=\"http://groups.google.com/groups?q=$query\" target=\"new\">Google Groups</a>"
	    ."</ul>";
	CloseTable();
    }
    include("footer.php");
    break;
}

?>