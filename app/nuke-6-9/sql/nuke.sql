-- MySQL dump 8.22
--
-- Host: localhost    Database: nuke
---------------------------------------------------------
-- Server version	3.23.54

--
-- Table structure for table 'confirm'
--

CREATE TABLE confirm (
  confirm_id char(32) NOT NULL default '',
  session_id char(32) NOT NULL default '',
  code char(6) NOT NULL default '',
  PRIMARY KEY  (session_id,confirm_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'confirm'
--



--
-- Table structure for table 'nuke_authors'
--

CREATE TABLE nuke_authors (
  aid varchar(25) NOT NULL default '',
  name varchar(50) default NULL,
  url varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  pwd varchar(40) default NULL,
  counter int(11) NOT NULL default '0',
  radminarticle tinyint(2) NOT NULL default '0',
  radmintopic tinyint(2) NOT NULL default '0',
  radminuser tinyint(2) NOT NULL default '0',
  radminsurvey tinyint(2) NOT NULL default '0',
  radminsection tinyint(2) NOT NULL default '0',
  radminlink tinyint(2) NOT NULL default '0',
  radminephem tinyint(2) NOT NULL default '0',
  radminfaq tinyint(2) NOT NULL default '0',
  radmindownload tinyint(2) NOT NULL default '0',
  radminreviews tinyint(2) NOT NULL default '0',
  radminnewsletter tinyint(2) NOT NULL default '0',
  radminforum tinyint(2) NOT NULL default '0',
  radmincontent tinyint(2) NOT NULL default '0',
  radminency tinyint(2) NOT NULL default '0',
  radminsuper tinyint(2) NOT NULL default '1',
  admlanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (aid),
  KEY aid (aid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_authors'
--



--
-- Table structure for table 'nuke_autonews'
--

CREATE TABLE nuke_autonews (
  anid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(80) NOT NULL default '',
  time varchar(19) NOT NULL default '',
  hometext text NOT NULL,
  bodytext text NOT NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  alanguage varchar(30) NOT NULL default '',
  acomm int(1) NOT NULL default '0',
  associated text NOT NULL,
  PRIMARY KEY  (anid),
  KEY anid (anid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_autonews'
--



--
-- Table structure for table 'nuke_banner'
--

CREATE TABLE nuke_banner (
  bid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  imptotal int(11) NOT NULL default '0',
  impmade int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  imageurl varchar(100) NOT NULL default '',
  clickurl varchar(200) NOT NULL default '',
  alttext varchar(255) NOT NULL default '',
  date datetime default NULL,
  dateend datetime default NULL,
  type tinyint(1) NOT NULL default '0',
  active tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (bid),
  KEY bid (bid),
  KEY cid (cid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_banner'
--



--
-- Table structure for table 'nuke_bannerclient'
--

CREATE TABLE nuke_bannerclient (
  cid int(11) NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  contact varchar(60) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  login varchar(10) NOT NULL default '',
  passwd varchar(10) NOT NULL default '',
  extrainfo text NOT NULL,
  PRIMARY KEY  (cid),
  KEY cid (cid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bannerclient'
--



--
-- Table structure for table 'nuke_bbauth_access'
--

CREATE TABLE nuke_bbauth_access (
  group_id mediumint(8) NOT NULL default '0',
  forum_id smallint(5) unsigned NOT NULL default '0',
  auth_view tinyint(1) NOT NULL default '0',
  auth_read tinyint(1) NOT NULL default '0',
  auth_post tinyint(1) NOT NULL default '0',
  auth_reply tinyint(1) NOT NULL default '0',
  auth_edit tinyint(1) NOT NULL default '0',
  auth_delete tinyint(1) NOT NULL default '0',
  auth_sticky tinyint(1) NOT NULL default '0',
  auth_announce tinyint(1) NOT NULL default '0',
  auth_vote tinyint(1) NOT NULL default '0',
  auth_pollcreate tinyint(1) NOT NULL default '0',
  auth_attachments tinyint(1) NOT NULL default '0',
  auth_mod tinyint(1) NOT NULL default '0',
  KEY group_id (group_id),
  KEY forum_id (forum_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbauth_access'
--



--
-- Table structure for table 'nuke_bbbanlist'
--

CREATE TABLE nuke_bbbanlist (
  ban_id mediumint(8) unsigned NOT NULL auto_increment,
  ban_userid mediumint(8) NOT NULL default '0',
  ban_ip varchar(8) NOT NULL default '',
  ban_email varchar(255) default NULL,
  PRIMARY KEY  (ban_id),
  KEY ban_ip_user_id (ban_ip,ban_userid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbbanlist'
--



--
-- Table structure for table 'nuke_bbcategories'
--

CREATE TABLE nuke_bbcategories (
  cat_id mediumint(8) unsigned NOT NULL auto_increment,
  cat_title varchar(100) default NULL,
  cat_order mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (cat_id),
  KEY cat_order (cat_order)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbcategories'
--



--
-- Table structure for table 'nuke_bbconfig'
--

CREATE TABLE nuke_bbconfig (
  config_name varchar(255) NOT NULL default '',
  config_value varchar(255) NOT NULL default '',
  PRIMARY KEY  (config_name)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbconfig'
--


INSERT INTO nuke_bbconfig VALUES ('config_id','1');
INSERT INTO nuke_bbconfig VALUES ('board_disable','0');
INSERT INTO nuke_bbconfig VALUES ('sitename','MySite.com');
INSERT INTO nuke_bbconfig VALUES ('site_desc','');
INSERT INTO nuke_bbconfig VALUES ('cookie_name','phpbb2mysql');
INSERT INTO nuke_bbconfig VALUES ('cookie_path','/');
INSERT INTO nuke_bbconfig VALUES ('cookie_domain','MySite.com');
INSERT INTO nuke_bbconfig VALUES ('cookie_secure','0');
INSERT INTO nuke_bbconfig VALUES ('session_length','3600');
INSERT INTO nuke_bbconfig VALUES ('allow_html','1');
INSERT INTO nuke_bbconfig VALUES ('allow_html_tags','b,i,u,pre');
INSERT INTO nuke_bbconfig VALUES ('allow_bbcode','1');
INSERT INTO nuke_bbconfig VALUES ('allow_smilies','1');
INSERT INTO nuke_bbconfig VALUES ('allow_sig','1');
INSERT INTO nuke_bbconfig VALUES ('allow_namechange','0');
INSERT INTO nuke_bbconfig VALUES ('allow_theme_create','0');
INSERT INTO nuke_bbconfig VALUES ('allow_avatar_local','1');
INSERT INTO nuke_bbconfig VALUES ('allow_avatar_remote','0');
INSERT INTO nuke_bbconfig VALUES ('allow_avatar_upload','0');
INSERT INTO nuke_bbconfig VALUES ('override_user_style','1');
INSERT INTO nuke_bbconfig VALUES ('posts_per_page','15');
INSERT INTO nuke_bbconfig VALUES ('topics_per_page','50');
INSERT INTO nuke_bbconfig VALUES ('hot_threshold','25');
INSERT INTO nuke_bbconfig VALUES ('max_poll_options','10');
INSERT INTO nuke_bbconfig VALUES ('max_sig_chars','255');
INSERT INTO nuke_bbconfig VALUES ('max_inbox_privmsgs','100');
INSERT INTO nuke_bbconfig VALUES ('max_sentbox_privmsgs','100');
INSERT INTO nuke_bbconfig VALUES ('max_savebox_privmsgs','100');
INSERT INTO nuke_bbconfig VALUES ('board_email_sig','Thanks, Webmaster@MySite.com');
INSERT INTO nuke_bbconfig VALUES ('board_email','Webmaster@MySite.com');
INSERT INTO nuke_bbconfig VALUES ('smtp_delivery','0');
INSERT INTO nuke_bbconfig VALUES ('smtp_host','');
INSERT INTO nuke_bbconfig VALUES ('require_activation','0');
INSERT INTO nuke_bbconfig VALUES ('flood_interval','15');
INSERT INTO nuke_bbconfig VALUES ('board_email_form','0');
INSERT INTO nuke_bbconfig VALUES ('avatar_filesize','6144');
INSERT INTO nuke_bbconfig VALUES ('avatar_max_width','80');
INSERT INTO nuke_bbconfig VALUES ('avatar_max_height','80');
INSERT INTO nuke_bbconfig VALUES ('avatar_path','modules/Forums/images/avatars');
INSERT INTO nuke_bbconfig VALUES ('avatar_gallery_path','modules/Forums/images/avatars');
INSERT INTO nuke_bbconfig VALUES ('smilies_path','modules/Forums/images/smiles');
INSERT INTO nuke_bbconfig VALUES ('default_style','1');
INSERT INTO nuke_bbconfig VALUES ('default_dateformat','D M d, Y g:i a');
INSERT INTO nuke_bbconfig VALUES ('board_timezone','10');
INSERT INTO nuke_bbconfig VALUES ('prune_enable','0');
INSERT INTO nuke_bbconfig VALUES ('privmsg_disable','0');
INSERT INTO nuke_bbconfig VALUES ('gzip_compress','0');
INSERT INTO nuke_bbconfig VALUES ('coppa_fax','');
INSERT INTO nuke_bbconfig VALUES ('coppa_mail','');
INSERT INTO nuke_bbconfig VALUES ('board_startdate','1013908210');
INSERT INTO nuke_bbconfig VALUES ('default_lang','english');
INSERT INTO nuke_bbconfig VALUES ('smtp_username','');
INSERT INTO nuke_bbconfig VALUES ('smtp_password','');
INSERT INTO nuke_bbconfig VALUES ('record_online_users','2');
INSERT INTO nuke_bbconfig VALUES ('record_online_date','1034668530');
INSERT INTO nuke_bbconfig VALUES ('server_name','MySite.com');
INSERT INTO nuke_bbconfig VALUES ('server_port','80');
INSERT INTO nuke_bbconfig VALUES ('script_path','/modules/Forums/');
INSERT INTO nuke_bbconfig VALUES ('version','.0.5');
INSERT INTO nuke_bbconfig VALUES ('enable_confirm','0');
INSERT INTO nuke_bbconfig VALUES ('sendmail_fix','0');

--
-- Table structure for table 'nuke_bbdisallow'
--

CREATE TABLE nuke_bbdisallow (
  disallow_id mediumint(8) unsigned NOT NULL auto_increment,
  disallow_username varchar(25) default NULL,
  PRIMARY KEY  (disallow_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbdisallow'
--



--
-- Table structure for table 'nuke_bbforum_prune'
--

CREATE TABLE nuke_bbforum_prune (
  prune_id mediumint(8) unsigned NOT NULL auto_increment,
  forum_id smallint(5) unsigned NOT NULL default '0',
  prune_days tinyint(4) unsigned NOT NULL default '0',
  prune_freq tinyint(4) unsigned NOT NULL default '0',
  PRIMARY KEY  (prune_id),
  KEY forum_id (forum_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbforum_prune'
--



--
-- Table structure for table 'nuke_bbforums'
--

CREATE TABLE nuke_bbforums (
  forum_id smallint(5) unsigned NOT NULL auto_increment,
  cat_id mediumint(8) unsigned NOT NULL default '0',
  forum_name varchar(150) default NULL,
  forum_desc text,
  forum_status tinyint(4) NOT NULL default '0',
  forum_order mediumint(8) unsigned NOT NULL default '1',
  forum_posts mediumint(8) unsigned NOT NULL default '0',
  forum_topics mediumint(8) unsigned NOT NULL default '0',
  forum_last_post_id mediumint(8) unsigned NOT NULL default '0',
  prune_next int(11) default NULL,
  prune_enable tinyint(1) NOT NULL default '1',
  auth_view tinyint(2) NOT NULL default '0',
  auth_read tinyint(2) NOT NULL default '0',
  auth_post tinyint(2) NOT NULL default '0',
  auth_reply tinyint(2) NOT NULL default '0',
  auth_edit tinyint(2) NOT NULL default '0',
  auth_delete tinyint(2) NOT NULL default '0',
  auth_sticky tinyint(2) NOT NULL default '0',
  auth_announce tinyint(2) NOT NULL default '0',
  auth_vote tinyint(2) NOT NULL default '0',
  auth_pollcreate tinyint(2) NOT NULL default '0',
  auth_attachments tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (forum_id),
  KEY forums_order (forum_order),
  KEY cat_id (cat_id),
  KEY forum_last_post_id (forum_last_post_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbforums'
--



--
-- Table structure for table 'nuke_bbgroups'
--

CREATE TABLE nuke_bbgroups (
  group_id mediumint(8) NOT NULL auto_increment,
  group_type tinyint(4) NOT NULL default '1',
  group_name varchar(40) NOT NULL default '',
  group_description varchar(255) NOT NULL default '',
  group_moderator mediumint(8) NOT NULL default '0',
  group_single_user tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (group_id),
  KEY group_single_user (group_single_user)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbgroups'
--


INSERT INTO nuke_bbgroups VALUES (1,1,'Anonymous','Personal User',0,1);
INSERT INTO nuke_bbgroups VALUES (3,2,'Moderators','Moderators of this Forum',5,0);

--
-- Table structure for table 'nuke_bbposts'
--

CREATE TABLE nuke_bbposts (
  post_id mediumint(8) unsigned NOT NULL auto_increment,
  topic_id mediumint(8) unsigned NOT NULL default '0',
  forum_id smallint(5) unsigned NOT NULL default '0',
  poster_id mediumint(8) NOT NULL default '0',
  post_time int(11) NOT NULL default '0',
  poster_ip varchar(8) NOT NULL default '',
  post_username varchar(25) default NULL,
  enable_bbcode tinyint(1) NOT NULL default '1',
  enable_html tinyint(1) NOT NULL default '0',
  enable_smilies tinyint(1) NOT NULL default '1',
  enable_sig tinyint(1) NOT NULL default '1',
  post_edit_time int(11) default NULL,
  post_edit_count smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (post_id),
  KEY forum_id (forum_id),
  KEY topic_id (topic_id),
  KEY poster_id (poster_id),
  KEY post_time (post_time)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbposts'
--



--
-- Table structure for table 'nuke_bbposts_text'
--

CREATE TABLE nuke_bbposts_text (
  post_id mediumint(8) unsigned NOT NULL default '0',
  bbcode_uid varchar(10) NOT NULL default '',
  post_subject varchar(60) default NULL,
  post_text text,
  PRIMARY KEY  (post_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbposts_text'
--



--
-- Table structure for table 'nuke_bbprivmsgs'
--

CREATE TABLE nuke_bbprivmsgs (
  privmsgs_id mediumint(8) unsigned NOT NULL auto_increment,
  privmsgs_type tinyint(4) NOT NULL default '0',
  privmsgs_subject varchar(255) NOT NULL default '0',
  privmsgs_from_userid mediumint(8) NOT NULL default '0',
  privmsgs_to_userid mediumint(8) NOT NULL default '0',
  privmsgs_date int(11) NOT NULL default '0',
  privmsgs_ip varchar(8) NOT NULL default '',
  privmsgs_enable_bbcode tinyint(1) NOT NULL default '1',
  privmsgs_enable_html tinyint(1) NOT NULL default '0',
  privmsgs_enable_smilies tinyint(1) NOT NULL default '1',
  privmsgs_attach_sig tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (privmsgs_id),
  KEY privmsgs_from_userid (privmsgs_from_userid),
  KEY privmsgs_to_userid (privmsgs_to_userid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbprivmsgs'
--



--
-- Table structure for table 'nuke_bbprivmsgs_text'
--

CREATE TABLE nuke_bbprivmsgs_text (
  privmsgs_text_id mediumint(8) unsigned NOT NULL default '0',
  privmsgs_bbcode_uid varchar(10) NOT NULL default '0',
  privmsgs_text text,
  PRIMARY KEY  (privmsgs_text_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbprivmsgs_text'
--



--
-- Table structure for table 'nuke_bbranks'
--

CREATE TABLE nuke_bbranks (
  rank_id smallint(5) unsigned NOT NULL auto_increment,
  rank_title varchar(50) NOT NULL default '',
  rank_min mediumint(8) NOT NULL default '0',
  rank_max mediumint(8) NOT NULL default '0',
  rank_special tinyint(1) default '0',
  rank_image varchar(255) default NULL,
  PRIMARY KEY  (rank_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbranks'
--


INSERT INTO nuke_bbranks VALUES (1,'Site Admin',-1,-1,1,'modules/Forums/images/ranks/6stars.gif');
INSERT INTO nuke_bbranks VALUES (2,'Newbie',1,0,0,'modules/Forums/images/ranks/1star.gif');

--
-- Table structure for table 'nuke_bbsearch_results'
--

CREATE TABLE nuke_bbsearch_results (
  search_id int(11) unsigned NOT NULL default '0',
  session_id varchar(32) NOT NULL default '',
  search_array text NOT NULL,
  PRIMARY KEY  (search_id),
  KEY session_id (session_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbsearch_results'
--



--
-- Table structure for table 'nuke_bbsearch_wordlist'
--

CREATE TABLE nuke_bbsearch_wordlist (
  word_text varchar(50) binary NOT NULL default '',
  word_id mediumint(8) unsigned NOT NULL auto_increment,
  word_common tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (word_text),
  KEY word_id (word_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbsearch_wordlist'
--



--
-- Table structure for table 'nuke_bbsearch_wordmatch'
--

CREATE TABLE nuke_bbsearch_wordmatch (
  post_id mediumint(8) unsigned NOT NULL default '0',
  word_id mediumint(8) unsigned NOT NULL default '0',
  title_match tinyint(1) NOT NULL default '0',
  KEY word_id (word_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbsearch_wordmatch'
--



--
-- Table structure for table 'nuke_bbsessions'
--

CREATE TABLE nuke_bbsessions (
  session_id char(32) NOT NULL default '',
  session_user_id mediumint(8) NOT NULL default '0',
  session_start int(11) NOT NULL default '0',
  session_time int(11) NOT NULL default '0',
  session_ip char(8) NOT NULL default '0',
  session_page int(11) NOT NULL default '0',
  session_logged_in tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (session_id),
  KEY session_user_id (session_user_id),
  KEY session_id_ip_user_id (session_id,session_ip,session_user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbsessions'
--



--
-- Table structure for table 'nuke_bbsmilies'
--

CREATE TABLE nuke_bbsmilies (
  smilies_id smallint(5) unsigned NOT NULL auto_increment,
  code varchar(50) default NULL,
  smile_url varchar(100) default NULL,
  emoticon varchar(75) default NULL,
  PRIMARY KEY  (smilies_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbsmilies'
--


INSERT INTO nuke_bbsmilies VALUES (1,':D','icon_biggrin.gif','Very Happy');
INSERT INTO nuke_bbsmilies VALUES (2,':-D','icon_biggrin.gif','Very Happy');
INSERT INTO nuke_bbsmilies VALUES (3,':grin:','icon_biggrin.gif','Very Happy');
INSERT INTO nuke_bbsmilies VALUES (4,':)','icon_smile.gif','Smile');
INSERT INTO nuke_bbsmilies VALUES (5,':-)','icon_smile.gif','Smile');
INSERT INTO nuke_bbsmilies VALUES (6,':smile:','icon_smile.gif','Smile');
INSERT INTO nuke_bbsmilies VALUES (7,':(','icon_sad.gif','Sad');
INSERT INTO nuke_bbsmilies VALUES (8,':-(','icon_sad.gif','Sad');
INSERT INTO nuke_bbsmilies VALUES (9,':sad:','icon_sad.gif','Sad');
INSERT INTO nuke_bbsmilies VALUES (10,':o','icon_surprised.gif','Surprised');
INSERT INTO nuke_bbsmilies VALUES (11,':-o','icon_surprised.gif','Surprised');
INSERT INTO nuke_bbsmilies VALUES (12,':eek:','icon_surprised.gif','Surprised');
INSERT INTO nuke_bbsmilies VALUES (13,'8O','icon_eek.gif','Shocked');
INSERT INTO nuke_bbsmilies VALUES (14,'8-O','icon_eek.gif','Shocked');
INSERT INTO nuke_bbsmilies VALUES (15,':shock:','icon_eek.gif','Shocked');
INSERT INTO nuke_bbsmilies VALUES (16,':?','icon_confused.gif','Confused');
INSERT INTO nuke_bbsmilies VALUES (17,':-?','icon_confused.gif','Confused');
INSERT INTO nuke_bbsmilies VALUES (18,':???:','icon_confused.gif','Confused');
INSERT INTO nuke_bbsmilies VALUES (19,'8)','icon_cool.gif','Cool');
INSERT INTO nuke_bbsmilies VALUES (20,'8-)','icon_cool.gif','Cool');
INSERT INTO nuke_bbsmilies VALUES (21,':cool:','icon_cool.gif','Cool');
INSERT INTO nuke_bbsmilies VALUES (22,':lol:','icon_lol.gif','Laughing');
INSERT INTO nuke_bbsmilies VALUES (23,':x','icon_mad.gif','Mad');
INSERT INTO nuke_bbsmilies VALUES (24,':-x','icon_mad.gif','Mad');
INSERT INTO nuke_bbsmilies VALUES (25,':mad:','icon_mad.gif','Mad');
INSERT INTO nuke_bbsmilies VALUES (26,':P','icon_razz.gif','Razz');
INSERT INTO nuke_bbsmilies VALUES (27,':-P','icon_razz.gif','Razz');
INSERT INTO nuke_bbsmilies VALUES (28,':razz:','icon_razz.gif','Razz');
INSERT INTO nuke_bbsmilies VALUES (29,':oops:','icon_redface.gif','Embarassed');
INSERT INTO nuke_bbsmilies VALUES (30,':cry:','icon_cry.gif','Crying or Very sad');
INSERT INTO nuke_bbsmilies VALUES (31,':evil:','icon_evil.gif','Evil or Very Mad');
INSERT INTO nuke_bbsmilies VALUES (32,':twisted:','icon_twisted.gif','Twisted Evil');
INSERT INTO nuke_bbsmilies VALUES (33,':roll:','icon_rolleyes.gif','Rolling Eyes');
INSERT INTO nuke_bbsmilies VALUES (34,':wink:','icon_wink.gif','Wink');
INSERT INTO nuke_bbsmilies VALUES (35,';)','icon_wink.gif','Wink');
INSERT INTO nuke_bbsmilies VALUES (36,';-)','icon_wink.gif','Wink');
INSERT INTO nuke_bbsmilies VALUES (37,':!:','icon_exclaim.gif','Exclamation');
INSERT INTO nuke_bbsmilies VALUES (38,':?:','icon_question.gif','Question');
INSERT INTO nuke_bbsmilies VALUES (39,':idea:','icon_idea.gif','Idea');
INSERT INTO nuke_bbsmilies VALUES (40,':arrow:','icon_arrow.gif','Arrow');
INSERT INTO nuke_bbsmilies VALUES (41,':|','icon_neutral.gif','Neutral');
INSERT INTO nuke_bbsmilies VALUES (42,':-|','icon_neutral.gif','Neutral');
INSERT INTO nuke_bbsmilies VALUES (43,':neutral:','icon_neutral.gif','Neutral');
INSERT INTO nuke_bbsmilies VALUES (44,':mrgreen:','icon_mrgreen.gif','Mr. Green');

--
-- Table structure for table 'nuke_bbthemes'
--

CREATE TABLE nuke_bbthemes (
  themes_id mediumint(8) unsigned NOT NULL auto_increment,
  template_name varchar(30) NOT NULL default '',
  style_name varchar(30) NOT NULL default '',
  head_stylesheet varchar(100) default NULL,
  body_background varchar(100) default NULL,
  body_bgcolor varchar(6) default NULL,
  body_text varchar(6) default NULL,
  body_link varchar(6) default NULL,
  body_vlink varchar(6) default NULL,
  body_alink varchar(6) default NULL,
  body_hlink varchar(6) default NULL,
  tr_color1 varchar(6) default NULL,
  tr_color2 varchar(6) default NULL,
  tr_color3 varchar(6) default NULL,
  tr_class1 varchar(25) default NULL,
  tr_class2 varchar(25) default NULL,
  tr_class3 varchar(25) default NULL,
  th_color1 varchar(6) default NULL,
  th_color2 varchar(6) default NULL,
  th_color3 varchar(6) default NULL,
  th_class1 varchar(25) default NULL,
  th_class2 varchar(25) default NULL,
  th_class3 varchar(25) default NULL,
  td_color1 varchar(6) default NULL,
  td_color2 varchar(6) default NULL,
  td_color3 varchar(6) default NULL,
  td_class1 varchar(25) default NULL,
  td_class2 varchar(25) default NULL,
  td_class3 varchar(25) default NULL,
  fontface1 varchar(50) default NULL,
  fontface2 varchar(50) default NULL,
  fontface3 varchar(50) default NULL,
  fontsize1 tinyint(4) default NULL,
  fontsize2 tinyint(4) default NULL,
  fontsize3 tinyint(4) default NULL,
  fontcolor1 varchar(6) default NULL,
  fontcolor2 varchar(6) default NULL,
  fontcolor3 varchar(6) default NULL,
  span_class1 varchar(25) default NULL,
  span_class2 varchar(25) default NULL,
  span_class3 varchar(25) default NULL,
  img_size_poll smallint(5) unsigned default NULL,
  img_size_privmsg smallint(5) unsigned default NULL,
  PRIMARY KEY  (themes_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbthemes'
--


INSERT INTO nuke_bbthemes VALUES (1,'subSilver','subSilver','subSilver.css','','0E3259','000000','006699','5493B4','','DD6900','EFEFEF','DEE3E7','D1D7DC','','','','98AAB1','006699','FFFFFF','cellpic1.gif','cellpic3.gif','cellpic2.jpg','FAFAFA','FFFFFF','','row1','row2','','Verdana, Arial, Helvetica, sans-serif','Trebuchet MS','Courier, \'Courier New\', sans-serif',10,11,12,'444444','006600','FFA34F','','','',NULL,NULL);

--
-- Table structure for table 'nuke_bbthemes_name'
--

CREATE TABLE nuke_bbthemes_name (
  themes_id smallint(5) unsigned NOT NULL default '0',
  tr_color1_name char(50) default NULL,
  tr_color2_name char(50) default NULL,
  tr_color3_name char(50) default NULL,
  tr_class1_name char(50) default NULL,
  tr_class2_name char(50) default NULL,
  tr_class3_name char(50) default NULL,
  th_color1_name char(50) default NULL,
  th_color2_name char(50) default NULL,
  th_color3_name char(50) default NULL,
  th_class1_name char(50) default NULL,
  th_class2_name char(50) default NULL,
  th_class3_name char(50) default NULL,
  td_color1_name char(50) default NULL,
  td_color2_name char(50) default NULL,
  td_color3_name char(50) default NULL,
  td_class1_name char(50) default NULL,
  td_class2_name char(50) default NULL,
  td_class3_name char(50) default NULL,
  fontface1_name char(50) default NULL,
  fontface2_name char(50) default NULL,
  fontface3_name char(50) default NULL,
  fontsize1_name char(50) default NULL,
  fontsize2_name char(50) default NULL,
  fontsize3_name char(50) default NULL,
  fontcolor1_name char(50) default NULL,
  fontcolor2_name char(50) default NULL,
  fontcolor3_name char(50) default NULL,
  span_class1_name char(50) default NULL,
  span_class2_name char(50) default NULL,
  span_class3_name char(50) default NULL,
  PRIMARY KEY  (themes_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbthemes_name'
--


INSERT INTO nuke_bbthemes_name VALUES (1,'The lightest row colour','The medium row color','The darkest row colour','','','','Border round the whole page','Outer table border','Inner table border','Silver gradient picture','Blue gradient picture','Fade-out gradient on index','Background for quote boxes','All white areas','','Background for topic posts','2nd background for topic posts','','Main fonts','Additional topic title font','Form fonts','Smallest font size','Medium font size','Normal font size (post body etc)','Quote & copyright text','Code text colour','Main table header text colour','','','');

--
-- Table structure for table 'nuke_bbtopics'
--

CREATE TABLE nuke_bbtopics (
  topic_id mediumint(8) unsigned NOT NULL auto_increment,
  forum_id smallint(8) unsigned NOT NULL default '0',
  topic_title char(60) NOT NULL default '',
  topic_poster mediumint(8) NOT NULL default '0',
  topic_time int(11) NOT NULL default '0',
  topic_views mediumint(8) unsigned NOT NULL default '0',
  topic_replies mediumint(8) unsigned NOT NULL default '0',
  topic_status tinyint(3) NOT NULL default '0',
  topic_vote tinyint(1) NOT NULL default '0',
  topic_type tinyint(3) NOT NULL default '0',
  topic_last_post_id mediumint(8) unsigned NOT NULL default '0',
  topic_first_post_id mediumint(8) unsigned NOT NULL default '0',
  topic_moved_id mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (topic_id),
  KEY forum_id (forum_id),
  KEY topic_moved_id (topic_moved_id),
  KEY topic_status (topic_status),
  KEY topic_type (topic_type)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbtopics'
--



--
-- Table structure for table 'nuke_bbtopics_watch'
--

CREATE TABLE nuke_bbtopics_watch (
  topic_id mediumint(8) unsigned NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  notify_status tinyint(1) NOT NULL default '0',
  KEY topic_id (topic_id),
  KEY user_id (user_id),
  KEY notify_status (notify_status)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbtopics_watch'
--



--
-- Table structure for table 'nuke_bbuser_group'
--

CREATE TABLE nuke_bbuser_group (
  group_id mediumint(8) NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  user_pending tinyint(1) default NULL,
  KEY group_id (group_id),
  KEY user_id (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbuser_group'
--


INSERT INTO nuke_bbuser_group VALUES (1,-1,0);
INSERT INTO nuke_bbuser_group VALUES (3,5,0);

--
-- Table structure for table 'nuke_bbvote_desc'
--

CREATE TABLE nuke_bbvote_desc (
  vote_id mediumint(8) unsigned NOT NULL auto_increment,
  topic_id mediumint(8) unsigned NOT NULL default '0',
  vote_text text NOT NULL,
  vote_start int(11) NOT NULL default '0',
  vote_length int(11) NOT NULL default '0',
  PRIMARY KEY  (vote_id),
  KEY topic_id (topic_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbvote_desc'
--



--
-- Table structure for table 'nuke_bbvote_results'
--

CREATE TABLE nuke_bbvote_results (
  vote_id mediumint(8) unsigned NOT NULL default '0',
  vote_option_id tinyint(4) unsigned NOT NULL default '0',
  vote_option_text varchar(255) NOT NULL default '',
  vote_result int(11) NOT NULL default '0',
  KEY vote_option_id (vote_option_id),
  KEY vote_id (vote_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbvote_results'
--



--
-- Table structure for table 'nuke_bbvote_voters'
--

CREATE TABLE nuke_bbvote_voters (
  vote_id mediumint(8) unsigned NOT NULL default '0',
  vote_user_id mediumint(8) NOT NULL default '0',
  vote_user_ip char(8) NOT NULL default '',
  KEY vote_id (vote_id),
  KEY vote_user_id (vote_user_id),
  KEY vote_user_ip (vote_user_ip)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbvote_voters'
--



--
-- Table structure for table 'nuke_bbwords'
--

CREATE TABLE nuke_bbwords (
  word_id mediumint(8) unsigned NOT NULL auto_increment,
  word char(100) NOT NULL default '',
  replacement char(100) NOT NULL default '',
  PRIMARY KEY  (word_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_bbwords'
--



--
-- Table structure for table 'nuke_blocks'
--

CREATE TABLE nuke_blocks (
  bid int(10) NOT NULL auto_increment,
  bkey varchar(15) NOT NULL default '',
  title varchar(60) NOT NULL default '',
  content text NOT NULL,
  url varchar(200) NOT NULL default '',
  bposition char(1) NOT NULL default '',
  weight int(10) NOT NULL default '1',
  active int(1) NOT NULL default '1',
  refresh int(10) NOT NULL default '0',
  time varchar(14) NOT NULL default '0',
  blanguage varchar(30) NOT NULL default '',
  blockfile varchar(255) NOT NULL default '',
  view int(1) NOT NULL default '0',
  PRIMARY KEY  (bid),
  KEY bid (bid),
  KEY title (title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_blocks'
--


INSERT INTO nuke_blocks VALUES (1,'','Modules','','','l',1,1,0,'','','block-Modules.php',0);
INSERT INTO nuke_blocks VALUES (2,'admin','Administration','<strong><big>·</big></strong> <a href=\"admin.php\">Administration</a><br>\r\n<strong><big>·</big></strong> <a href=\"admin.php?op=adminStory\">NEW Story</a><br>\r\n<strong><big>·</big></strong> <a href=\"admin.php?op=create\">Change Survey</a><br>\r\n<strong><big>·</big></strong> <a href=\"admin.php?op=content\">Content</a><br>\r\n<strong><big>·</big></strong> <a href=\"admin.php?op=logout\">Logout</a>','','l',2,1,0,'985591188','','',2);
INSERT INTO nuke_blocks VALUES (3,'','Who\'s Online','','','l',3,1,0,'','','block-Who_is_Online.php',0);
INSERT INTO nuke_blocks VALUES (4,'','Search','','','l',4,0,3600,'','','block-Search.php',0);
INSERT INTO nuke_blocks VALUES (5,'','Languages','','','l',5,1,3600,'','','block-Languages.php',0);
INSERT INTO nuke_blocks VALUES (6,'','Random Headlines','','','l',6,0,3600,'','','block-Random_Headlines.php',0);
INSERT INTO nuke_blocks VALUES (7,'','Amazon','','','l',7,1,3600,'','','block-Amazon.php',0);
INSERT INTO nuke_blocks VALUES (8,'userbox','User\'s Custom Box','','','r',1,1,0,'','','',1);
INSERT INTO nuke_blocks VALUES (9,'','Categories Menu','','','r',2,0,0,'','','block-Categories.php',0);
INSERT INTO nuke_blocks VALUES (10,'','Survey','','','r',3,1,3600,'','','block-Survey.php',0);
INSERT INTO nuke_blocks VALUES (11,'','Login','','','r',4,1,3600,'','','block-Login.php',3);
INSERT INTO nuke_blocks VALUES (12,'','Big Story of Today','','','r',5,1,3600,'','','block-Big_Story_of_Today.php',0);
INSERT INTO nuke_blocks VALUES (13,'','Old Articles','','','r',6,1,3600,'','','block-Old_Articles.php',0);
INSERT INTO nuke_blocks VALUES (14,'','Information','<br><center><font class=\"content\">\r\n<a href=\"http://phpnuke.org\"><img src=\"images/powered/phpnuke.gif\" border=\"0\" alt=\"Powered by PHP-Nuke\" title=\"Powered by PHP-Nuke\" width=\"88\" height=\"31\"></a>\r\n<br><br>\r\n<a href=\"http://validator.w3.org/check/referer\"><img src=\"images/html401.gif\" width=\"88\" height=\"31\" alt=\"Valid HTML 4.01!\" title=\"Valid HTML 4.01!\" border=\"0\"></a>\r\n<br><br>\r\n<a href=\"http://jigsaw.w3.org/css-validator\"><img src=\"images/css.gif\" width=\"88\" height=\"31\" alt=\"Valid CSS!\" title=\"Valid CSS!\" border=\"0\"></a></font></center><br>','','r',7,1,0,'','','',0);

--
-- Table structure for table 'nuke_comments'
--

CREATE TABLE nuke_comments (
  tid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  date datetime default NULL,
  name varchar(60) NOT NULL default '',
  email varchar(60) default NULL,
  url varchar(60) default NULL,
  host_name varchar(60) default NULL,
  subject varchar(85) NOT NULL default '',
  comment text NOT NULL,
  score tinyint(4) NOT NULL default '0',
  reason tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (tid),
  KEY tid (tid),
  KEY pid (pid),
  KEY sid (sid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_comments'
--



--
-- Table structure for table 'nuke_config'
--

CREATE TABLE nuke_config (
  sitename varchar(255) NOT NULL default '',
  nukeurl varchar(255) NOT NULL default '',
  site_logo varchar(255) NOT NULL default '',
  slogan varchar(255) NOT NULL default '',
  startdate varchar(50) NOT NULL default '',
  adminmail varchar(255) NOT NULL default '',
  anonpost tinyint(1) NOT NULL default '0',
  Default_Theme varchar(255) NOT NULL default '',
  foot1 text NOT NULL,
  foot2 text NOT NULL,
  foot3 text NOT NULL,
  commentlimit int(9) NOT NULL default '4096',
  anonymous varchar(255) NOT NULL default '',
  minpass tinyint(1) NOT NULL default '5',
  pollcomm tinyint(1) NOT NULL default '1',
  articlecomm tinyint(1) NOT NULL default '1',
  broadcast_msg tinyint(1) NOT NULL default '1',
  my_headlines tinyint(1) NOT NULL default '1',
  top int(3) NOT NULL default '10',
  storyhome int(2) NOT NULL default '10',
  user_news tinyint(1) NOT NULL default '1',
  oldnum int(2) NOT NULL default '30',
  ultramode tinyint(1) NOT NULL default '0',
  banners tinyint(1) NOT NULL default '1',
  backend_title varchar(255) NOT NULL default '',
  backend_language varchar(10) NOT NULL default '',
  language varchar(100) NOT NULL default '',
  locale varchar(10) NOT NULL default '',
  multilingual tinyint(1) NOT NULL default '0',
  useflags tinyint(1) NOT NULL default '0',
  notify tinyint(1) NOT NULL default '0',
  notify_email varchar(255) NOT NULL default '',
  notify_subject varchar(255) NOT NULL default '',
  notify_message varchar(255) NOT NULL default '',
  notify_from varchar(255) NOT NULL default '',
  footermsgtxt text NOT NULL,
  email_send tinyint(1) NOT NULL default '1',
  attachmentdir varchar(255) NOT NULL default '',
  attachments tinyint(1) NOT NULL default '0',
  attachments_view tinyint(1) NOT NULL default '0',
  download_dir varchar(255) NOT NULL default '',
  defaultpopserver varchar(255) NOT NULL default '',
  singleaccount tinyint(1) NOT NULL default '0',
  singleaccountname varchar(255) NOT NULL default '',
  numaccounts tinyint(2) NOT NULL default '-1',
  imgpath varchar(255) NOT NULL default '',
  filter_forward tinyint(1) NOT NULL default '1',
  moderate tinyint(1) NOT NULL default '0',
  admingraphic tinyint(1) NOT NULL default '1',
  httpref tinyint(1) NOT NULL default '1',
  httprefmax int(5) NOT NULL default '1000',
  CensorMode tinyint(1) NOT NULL default '3',
  CensorReplace varchar(10) NOT NULL default '',
  copyright text NOT NULL,
  Version_Num varchar(10) NOT NULL default ''
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_config'
--


INSERT INTO nuke_config VALUES ('PHP-Nuke Powered Site','http://yoursite.com','logo.gif','Your slogan here','September 2002','webmaster@yoursite.com',0,'DeepBlue','<a href=\"http://phpnuke.org\" target=\"blank\"><img src=\"images/powered/nuke.gif\" border=\"0\" Alt=\"Web site powered by PHP-Nuke\" hspace=\"10\"></a><br>','All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest © 2002 by me','You can syndicate our news using the file <a href=\"backend.php\"><font class=\"footmsg_l\">backend.php</font></a> or <a href=\"ultramode.txt\"><font class=\"footmsg_l\">ultramode.txt</font></a>',4096,'Anonymous',5,1,1,1,1,10,10,1,30,0,1,'PHP-Nuke Powered Site','en-us','english','en_US',0,0,0,'me@yoursite.com','NEWS for my site','Hey! You got a new submission for your site.','webmaster','Mail sent from WebMail service at PHP-Nuke Powered Site\r\n- http://yoursite.com',1,'/var/www/html/modules/WebMail/tmp/',0,0,'modules/WebMail/attachments/','',0,'Your account',-1,'modules/WebMail/images',1,0,1,1,1000,3,'*****','Web site engine\'s code is Copyright &copy; 2003 by <a href=\"http://phpnuke.org\"><font class=\"footmsg_l\">PHP-Nuke</font></a>. All Rights Reserved. PHP-Nuke is Free Software released under the <a href=\"http://www.gnu.org\"><font class=\"footmsg_l\">GNU/GPL license</font></a>.','6.9');

--
-- Table structure for table 'nuke_contactbook'
--

CREATE TABLE nuke_contactbook (
  uid int(11) NOT NULL default '0',
  contactid int(11) NOT NULL auto_increment,
  firstname varchar(50) default NULL,
  lastname varchar(50) default NULL,
  email varchar(255) default NULL,
  company varchar(255) default NULL,
  homeaddress varchar(255) default NULL,
  city varchar(80) default NULL,
  homephone varchar(255) default NULL,
  workphone varchar(255) default NULL,
  homepage varchar(255) default NULL,
  IM varchar(255) default NULL,
  events text,
  reminders int(11) default NULL,
  notes text,
  PRIMARY KEY  (contactid),
  KEY uid (uid),
  KEY contactid (contactid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_contactbook'
--



--
-- Table structure for table 'nuke_counter'
--

CREATE TABLE nuke_counter (
  type varchar(80) NOT NULL default '',
  var varchar(80) NOT NULL default '',
  count int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_counter'
--


INSERT INTO nuke_counter VALUES ('total','hits',1);
INSERT INTO nuke_counter VALUES ('browser','WebTV',0);
INSERT INTO nuke_counter VALUES ('browser','Lynx',0);
INSERT INTO nuke_counter VALUES ('browser','MSIE',0);
INSERT INTO nuke_counter VALUES ('browser','Opera',0);
INSERT INTO nuke_counter VALUES ('browser','Konqueror',0);
INSERT INTO nuke_counter VALUES ('browser','Netscape',1);
INSERT INTO nuke_counter VALUES ('browser','Bot',0);
INSERT INTO nuke_counter VALUES ('browser','Other',0);
INSERT INTO nuke_counter VALUES ('os','Windows',0);
INSERT INTO nuke_counter VALUES ('os','Linux',1);
INSERT INTO nuke_counter VALUES ('os','Mac',0);
INSERT INTO nuke_counter VALUES ('os','FreeBSD',0);
INSERT INTO nuke_counter VALUES ('os','SunOS',0);
INSERT INTO nuke_counter VALUES ('os','IRIX',0);
INSERT INTO nuke_counter VALUES ('os','BeOS',0);
INSERT INTO nuke_counter VALUES ('os','OS/2',0);
INSERT INTO nuke_counter VALUES ('os','AIX',0);
INSERT INTO nuke_counter VALUES ('os','Other',0);

--
-- Table structure for table 'nuke_downloads_categories'
--

CREATE TABLE nuke_downloads_categories (
  cid int(11) NOT NULL auto_increment,
  title varchar(50) NOT NULL default '',
  cdescription text NOT NULL,
  parentid int(11) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY cid (cid),
  KEY title (title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_categories'
--



--
-- Table structure for table 'nuke_downloads_downloads'
--

CREATE TABLE nuke_downloads_downloads (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  date datetime default NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  hits int(11) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  downloadratingsummary double(6,4) NOT NULL default '0.0000',
  totalvotes int(11) NOT NULL default '0',
  totalcomments int(11) NOT NULL default '0',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY lid (lid),
  KEY cid (cid),
  KEY sid (sid),
  KEY title (title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_downloads'
--



--
-- Table structure for table 'nuke_downloads_editorials'
--

CREATE TABLE nuke_downloads_editorials (
  downloadid int(11) NOT NULL default '0',
  adminid varchar(60) NOT NULL default '',
  editorialtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL default '',
  PRIMARY KEY  (downloadid),
  KEY downloadid (downloadid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_editorials'
--



--
-- Table structure for table 'nuke_downloads_modrequest'
--

CREATE TABLE nuke_downloads_modrequest (
  requestid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL default '',
  brokendownload int(3) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (requestid),
  UNIQUE KEY requestid (requestid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_modrequest'
--



--
-- Table structure for table 'nuke_downloads_newdownload'
--

CREATE TABLE nuke_downloads_newdownload (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY lid (lid),
  KEY cid (cid),
  KEY sid (sid),
  KEY title (title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_newdownload'
--



--
-- Table structure for table 'nuke_downloads_votedata'
--

CREATE TABLE nuke_downloads_votedata (
  ratingdbid int(11) NOT NULL auto_increment,
  ratinglid int(11) NOT NULL default '0',
  ratinguser varchar(60) NOT NULL default '',
  rating int(11) NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingcomments text NOT NULL,
  ratingtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (ratingdbid),
  KEY ratingdbid (ratingdbid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_downloads_votedata'
--



--
-- Table structure for table 'nuke_encyclopedia'
--

CREATE TABLE nuke_encyclopedia (
  eid int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  description text NOT NULL,
  elanguage varchar(30) NOT NULL default '',
  active int(1) NOT NULL default '0',
  PRIMARY KEY  (eid),
  KEY eid (eid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_encyclopedia'
--



--
-- Table structure for table 'nuke_encyclopedia_text'
--

CREATE TABLE nuke_encyclopedia_text (
  tid int(10) NOT NULL auto_increment,
  eid int(10) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  text text NOT NULL,
  counter int(10) NOT NULL default '0',
  PRIMARY KEY  (tid),
  KEY tid (tid),
  KEY eid (eid),
  KEY title (title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_encyclopedia_text'
--



--
-- Table structure for table 'nuke_ephem'
--

CREATE TABLE nuke_ephem (
  eid int(11) NOT NULL auto_increment,
  did int(2) NOT NULL default '0',
  mid int(2) NOT NULL default '0',
  yid int(4) NOT NULL default '0',
  content text NOT NULL,
  elanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (eid),
  KEY eid (eid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_ephem'
--



--
-- Table structure for table 'nuke_faqAnswer'
--

CREATE TABLE nuke_faqAnswer (
  id tinyint(4) NOT NULL auto_increment,
  id_cat tinyint(4) NOT NULL default '0',
  question varchar(255) default '',
  answer text,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY id_cat (id_cat)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_faqAnswer'
--



--
-- Table structure for table 'nuke_faqCategories'
--

CREATE TABLE nuke_faqCategories (
  id_cat tinyint(3) NOT NULL auto_increment,
  categories varchar(255) default NULL,
  flanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (id_cat),
  KEY id_cat (id_cat)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_faqCategories'
--



--
-- Table structure for table 'nuke_headlines'
--

CREATE TABLE nuke_headlines (
  hid int(11) NOT NULL auto_increment,
  sitename varchar(30) NOT NULL default '',
  headlinesurl varchar(200) NOT NULL default '',
  PRIMARY KEY  (hid),
  KEY hid (hid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_headlines'
--


INSERT INTO nuke_headlines VALUES (1,'PHP-Nuke','http://phpnuke.org/backend.php');
INSERT INTO nuke_headlines VALUES (2,'LinuxCentral','http://linuxcentral.com/backend/lcnew.rdf');
INSERT INTO nuke_headlines VALUES (3,'NewsForge','http://www.newsforge.com/newsforge.rdf');
INSERT INTO nuke_headlines VALUES (4,'PHPBuilder','http://phpbuilder.com/rss_feed.php');
INSERT INTO nuke_headlines VALUES (5,'PHP-Nuke Español','http://phpnuke-espanol.org/backend.php');
INSERT INTO nuke_headlines VALUES (6,'Freshmeat','http://freshmeat.net/backend/fm.rdf');
INSERT INTO nuke_headlines VALUES (7,'LinuxWeelyNews','http://lwn.net/headlines/rss');
INSERT INTO nuke_headlines VALUES (8,'HappyPenguin','http://happypenguin.org/html/news.rdf');
INSERT INTO nuke_headlines VALUES (9,'BrunchingShuttlecocks','http://www.brunching.com/brunching.rdf');
INSERT INTO nuke_headlines VALUES (10,'MozillaNewsBot','http://www.mozilla.org/newsbot/newsbot.rdf');
INSERT INTO nuke_headlines VALUES (11,'AbsoluteGames','http://files.gameaholic.com/agfa.rdf');
INSERT INTO nuke_headlines VALUES (12,'HollywoodBitchslap','http://hollywoodbitchslap.com/hbs.rdf');
INSERT INTO nuke_headlines VALUES (13,'Listology','http://listology.com/recent.rdf');
INSERT INTO nuke_headlines VALUES (14,'PDABuzz','http://www.pdabuzz.com/netscape.txt');
INSERT INTO nuke_headlines VALUES (15,'TheNextLevel','http://www.the-nextlevel.com/rdf/tnl.rdf');
INSERT INTO nuke_headlines VALUES (16,'Gnome Desktop','http://www.gnomedesktop.org/backend.php');
INSERT INTO nuke_headlines VALUES (17,'DailyDaemonNews','http://daily.daemonnews.org/ddn.rdf.php3');
INSERT INTO nuke_headlines VALUES (18,'PerlMonks','http://www.perlmonks.org/headlines.rdf');
INSERT INTO nuke_headlines VALUES (19,'BSDToday','http://www.bsdtoday.com/backend/bt.rdf');
INSERT INTO nuke_headlines VALUES (20,'DotKDE','http://dot.kde.org/rdf');
INSERT INTO nuke_headlines VALUES (21,'DigitalTheatre','http://www.dtheatre.com/backend.php3?xml=yes');
INSERT INTO nuke_headlines VALUES (22,'WebReference','http://webreference.com/webreference.rdf');
INSERT INTO nuke_headlines VALUES (23,'FreeDOS','http://www.freedos.org/channels/rss.cgi');
INSERT INTO nuke_headlines VALUES (24,'PHP.net','http://www.php.net/news.rss');
INSERT INTO nuke_headlines VALUES (25,'LinuxJournal','http://www.linuxjournal.com/news.rss');
INSERT INTO nuke_headlines VALUES (26,'ComputerCops','http://www.computercops.com/backend.php');
INSERT INTO nuke_headlines VALUES (27,'NukeCops','http://www.nukecops.com/backend.php');
INSERT INTO nuke_headlines VALUES (28,'Learning Linux','http://www.learninglinux.com/backend.php');
INSERT INTO nuke_headlines VALUES (29,'Nuke Resources','http://www.nukeresources.com/backend.php');

--
-- Table structure for table 'nuke_journal'
--

CREATE TABLE nuke_journal (
  jid int(11) NOT NULL auto_increment,
  aid varchar(30) NOT NULL default '',
  title varchar(80) default NULL,
  bodytext text NOT NULL,
  mood varchar(48) NOT NULL default '',
  pdate varchar(48) NOT NULL default '',
  ptime varchar(48) NOT NULL default '',
  status varchar(48) NOT NULL default '',
  mtime varchar(48) NOT NULL default '',
  mdate varchar(48) NOT NULL default '',
  PRIMARY KEY  (jid),
  KEY jid (jid),
  KEY aid (aid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_journal'
--



--
-- Table structure for table 'nuke_journal_comments'
--

CREATE TABLE nuke_journal_comments (
  cid int(11) NOT NULL auto_increment,
  rid varchar(48) NOT NULL default '',
  aid varchar(30) NOT NULL default '',
  comment text NOT NULL,
  pdate varchar(48) NOT NULL default '',
  ptime varchar(48) NOT NULL default '',
  PRIMARY KEY  (cid),
  KEY cid (cid),
  KEY rid (rid),
  KEY aid (aid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_journal_comments'
--



--
-- Table structure for table 'nuke_journal_stats'
--

CREATE TABLE nuke_journal_stats (
  id int(11) NOT NULL auto_increment,
  joid varchar(48) NOT NULL default '',
  nop varchar(48) NOT NULL default '',
  ldp varchar(24) NOT NULL default '',
  ltp varchar(24) NOT NULL default '',
  micro varchar(128) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_journal_stats'
--



--
-- Table structure for table 'nuke_links_categories'
--

CREATE TABLE nuke_links_categories (
  cid int(11) NOT NULL auto_increment,
  title varchar(50) NOT NULL default '',
  cdescription text NOT NULL,
  parentid int(11) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY cid (cid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_categories'
--



--
-- Table structure for table 'nuke_links_editorials'
--

CREATE TABLE nuke_links_editorials (
  linkid int(11) NOT NULL default '0',
  adminid varchar(60) NOT NULL default '',
  editorialtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL default '',
  PRIMARY KEY  (linkid),
  KEY linkid (linkid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_editorials'
--



--
-- Table structure for table 'nuke_links_links'
--

CREATE TABLE nuke_links_links (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  date datetime default NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  hits int(11) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  linkratingsummary double(6,4) NOT NULL default '0.0000',
  totalvotes int(11) NOT NULL default '0',
  totalcomments int(11) NOT NULL default '0',
  PRIMARY KEY  (lid),
  KEY lid (lid),
  KEY cid (cid),
  KEY sid (sid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_links'
--



--
-- Table structure for table 'nuke_links_modrequest'
--

CREATE TABLE nuke_links_modrequest (
  requestid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL default '',
  brokenlink int(3) NOT NULL default '0',
  PRIMARY KEY  (requestid),
  UNIQUE KEY requestid (requestid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_modrequest'
--



--
-- Table structure for table 'nuke_links_newlink'
--

CREATE TABLE nuke_links_newlink (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  PRIMARY KEY  (lid),
  KEY lid (lid),
  KEY cid (cid),
  KEY sid (sid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_newlink'
--



--
-- Table structure for table 'nuke_links_votedata'
--

CREATE TABLE nuke_links_votedata (
  ratingdbid int(11) NOT NULL auto_increment,
  ratinglid int(11) NOT NULL default '0',
  ratinguser varchar(60) NOT NULL default '',
  rating int(11) NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingcomments text NOT NULL,
  ratingtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (ratingdbid),
  KEY ratingdbid (ratingdbid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_links_votedata'
--



--
-- Table structure for table 'nuke_main'
--

CREATE TABLE nuke_main (
  main_module varchar(255) NOT NULL default ''
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_main'
--


INSERT INTO nuke_main VALUES ('News');

--
-- Table structure for table 'nuke_message'
--

CREATE TABLE nuke_message (
  mid int(11) NOT NULL auto_increment,
  title varchar(100) NOT NULL default '',
  content text NOT NULL,
  date varchar(14) NOT NULL default '',
  expire int(7) NOT NULL default '0',
  active int(1) NOT NULL default '1',
  view int(1) NOT NULL default '1',
  mlanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (mid),
  UNIQUE KEY mid (mid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_message'
--


INSERT INTO nuke_message VALUES (1,'Welcome to PHP-Nuke!','<br>Congratulations! You have now a web portal installed!. You can edit or change this message from the <a href=\"admin.php\">Administration</a> page.\r\n<br><br>\r\n<center><b>For security reasons the best idea is to create the Super User right NOW by clicking <a href=\"admin.php\">HERE</a></b></center>\r\n<br><br>\r\nYou can also create a user for you from the same page. Please read carefully the README file, CREDITS file to see from where comes the things and remember that this is free software released under the GPL License (read COPYING file for details). Hope you enjoy this software. Please report any bug you find when one of this annoying things happens and I\'ll try to fix it for the next release.\r\n<br><br>\r\nIf you like this software and want to make a contribution you can purchase me something from my <a href=\"http://www.amazon.com/exec/obidos/wishlist/1N51JTF344VHI\">Wish List</a>, you can also donate some money to PHP-Nuke project by clicking <a href=\"https://secure.reg.net/product.asp?ID=11155\">here</a> or if you prefer you can become a PHP-Nuke\'s Club Member by clicking <a href=\"http://phpnuke.org/modules.php?name=Club\">here</a> and obtain extra goodies for your system.\r\n<br><br>\r\nPHP-Nuke is an advanced and <i>intelligent</i> content management system designed and programmed with very hard work. PHP-Nuke has the biggest user\'s community in the world for this kind of application, thousands people (users and programmers) are waiting for you to join the revolution at <a href=\"http://phpnuke.org\">http://phpnuke.org</a> where you can find thousands of modules/addons, themes, blocks, graphics, utilities and much more...\r\n<br><br>If you want to have written authorization to remove all visible copyright messages and any reference to PHP-Nuke, you can now acquire it by clicking <a href=\"http://phpnuke.org/modules.php?name=Commercial_License\" target=\"new\">here</a>, at the same time this will be a great support.\r\n<br><br>\r\nThanks for your support and for select PHP-Nuke as you web site\'s code! Hope you can can enjoy this application as much as we enjoy developing it!','993373194',0,1,1,'');

--
-- Table structure for table 'nuke_modules'
--

CREATE TABLE nuke_modules (
  mid int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  custom_title varchar(255) NOT NULL default '',
  active int(1) NOT NULL default '0',
  view int(1) NOT NULL default '0',
  inmenu tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (mid),
  KEY mid (mid),
  KEY title (title),
  KEY custom_title (custom_title)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_modules'
--


INSERT INTO nuke_modules VALUES (1,'AvantGo','AvantGo',1,0,1);
INSERT INTO nuke_modules VALUES (2,'Downloads','Downloads',1,0,1);
INSERT INTO nuke_modules VALUES (3,'Feedback','Feedback',1,0,1);
INSERT INTO nuke_modules VALUES (4,'Journal','Journal',1,0,1);
INSERT INTO nuke_modules VALUES (5,'News','News',1,0,1);
INSERT INTO nuke_modules VALUES (6,'Private_Messages','Private Messages',1,0,1);
INSERT INTO nuke_modules VALUES (7,'Recommend_Us','Recommend Us',1,0,1);
INSERT INTO nuke_modules VALUES (8,'Search','Search',1,0,1);
INSERT INTO nuke_modules VALUES (9,'Statistics','Statistics',1,0,1);
INSERT INTO nuke_modules VALUES (10,'Stories_Archive','Stories Archive',1,0,1);
INSERT INTO nuke_modules VALUES (11,'Submit_News','Submit News',1,0,1);
INSERT INTO nuke_modules VALUES (12,'Surveys','Surveys',1,0,1);
INSERT INTO nuke_modules VALUES (13,'Top','Top 10',1,0,1);
INSERT INTO nuke_modules VALUES (14,'Topics','Topics',1,0,1);
INSERT INTO nuke_modules VALUES (15,'Web_Links','Web Links',1,0,1);
INSERT INTO nuke_modules VALUES (16,'WebMail','WebMail',1,1,1);
INSERT INTO nuke_modules VALUES (17,'Your_Account','Your Account',1,0,1);
INSERT INTO nuke_modules VALUES (18,'Addon_Sample','Addon Sample',0,2,1);
INSERT INTO nuke_modules VALUES (19,'Content','Content',0,0,1);
INSERT INTO nuke_modules VALUES (20,'Encyclopedia','Encyclopedia',0,0,1);
INSERT INTO nuke_modules VALUES (21,'FAQ','FAQ',0,0,1);
INSERT INTO nuke_modules VALUES (22,'Forums','Forums',0,0,1);
INSERT INTO nuke_modules VALUES (23,'Members_List','Members List',0,1,1);
INSERT INTO nuke_modules VALUES (24,'Reviews','Reviews',0,0,1);
INSERT INTO nuke_modules VALUES (25,'Sections','Sections',0,0,1);

--
-- Table structure for table 'nuke_pages'
--

CREATE TABLE nuke_pages (
  pid int(10) NOT NULL auto_increment,
  cid int(10) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  subtitle varchar(255) NOT NULL default '',
  active int(1) NOT NULL default '0',
  page_header text NOT NULL,
  text text NOT NULL,
  page_footer text NOT NULL,
  signature text NOT NULL,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  counter int(10) NOT NULL default '0',
  clanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (pid),
  KEY pid (pid),
  KEY cid (cid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_pages'
--



--
-- Table structure for table 'nuke_pages_categories'
--

CREATE TABLE nuke_pages_categories (
  cid int(10) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  description text NOT NULL,
  PRIMARY KEY  (cid),
  KEY cid (cid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_pages_categories'
--



--
-- Table structure for table 'nuke_poll_check'
--

CREATE TABLE nuke_poll_check (
  ip varchar(20) NOT NULL default '',
  time varchar(14) NOT NULL default '',
  pollID int(10) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_poll_check'
--



--
-- Table structure for table 'nuke_poll_data'
--

CREATE TABLE nuke_poll_data (
  pollID int(11) NOT NULL default '0',
  optionText char(50) NOT NULL default '',
  optionCount int(11) NOT NULL default '0',
  voteID int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_poll_data'
--


INSERT INTO nuke_poll_data VALUES (1,'Ummmm, not bad',0,1);
INSERT INTO nuke_poll_data VALUES (1,'Cool',0,2);
INSERT INTO nuke_poll_data VALUES (1,'Terrific',0,3);
INSERT INTO nuke_poll_data VALUES (1,'The best one!',0,4);
INSERT INTO nuke_poll_data VALUES (1,'what the hell is this?',0,5);
INSERT INTO nuke_poll_data VALUES (1,'',0,6);
INSERT INTO nuke_poll_data VALUES (1,'',0,7);
INSERT INTO nuke_poll_data VALUES (1,'',0,8);
INSERT INTO nuke_poll_data VALUES (1,'',0,9);
INSERT INTO nuke_poll_data VALUES (1,'',0,10);
INSERT INTO nuke_poll_data VALUES (1,'',0,11);
INSERT INTO nuke_poll_data VALUES (1,'',0,12);

--
-- Table structure for table 'nuke_poll_desc'
--

CREATE TABLE nuke_poll_desc (
  pollID int(11) NOT NULL auto_increment,
  pollTitle varchar(100) NOT NULL default '',
  timeStamp int(11) NOT NULL default '0',
  voters mediumint(9) NOT NULL default '0',
  planguage varchar(30) NOT NULL default '',
  artid int(10) NOT NULL default '0',
  PRIMARY KEY  (pollID),
  KEY pollID (pollID)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_poll_desc'
--


INSERT INTO nuke_poll_desc VALUES (1,'What do you think about this site?',961405160,0,'english',0);

--
-- Table structure for table 'nuke_pollcomments'
--

CREATE TABLE nuke_pollcomments (
  tid int(11) NOT NULL auto_increment,
  pid int(11) NOT NULL default '0',
  pollID int(11) NOT NULL default '0',
  date datetime default NULL,
  name varchar(60) NOT NULL default '',
  email varchar(60) default NULL,
  url varchar(60) default NULL,
  host_name varchar(60) default NULL,
  subject varchar(60) NOT NULL default '',
  comment text NOT NULL,
  score tinyint(4) NOT NULL default '0',
  reason tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (tid),
  KEY tid (tid),
  KEY pid (pid),
  KEY pollID (pollID)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_pollcomments'
--



--
-- Table structure for table 'nuke_popsettings'
--

CREATE TABLE nuke_popsettings (
  id int(11) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  account varchar(50) default '',
  popserver varchar(255) default '',
  port int(5) default '0',
  uname varchar(100) default '',
  passwd varchar(20) default '',
  numshow int(11) default '0',
  deletefromserver char(1) default '',
  refresh int(11) default '0',
  timeout int(11) default '0',
  PRIMARY KEY  (id),
  KEY id (id),
  KEY uid (uid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_popsettings'
--



--
-- Table structure for table 'nuke_priv_msgs'
--

CREATE TABLE nuke_priv_msgs (
  msg_id int(10) NOT NULL auto_increment,
  msg_image varchar(100) default NULL,
  subject varchar(100) default NULL,
  from_userid int(10) NOT NULL default '0',
  to_userid int(10) NOT NULL default '0',
  msg_time varchar(20) default NULL,
  msg_text text,
  read_msg tinyint(10) NOT NULL default '0',
  PRIMARY KEY  (msg_id),
  KEY msg_id (msg_id),
  KEY to_userid (to_userid),
  KEY from_userid (from_userid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_priv_msgs'
--



--
-- Table structure for table 'nuke_public_messages'
--

CREATE TABLE nuke_public_messages (
  mid int(10) NOT NULL auto_increment,
  content varchar(255) NOT NULL default '',
  date varchar(14) default NULL,
  who varchar(25) NOT NULL default '',
  PRIMARY KEY  (mid),
  KEY mid (mid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_public_messages'
--



--
-- Table structure for table 'nuke_queue'
--

CREATE TABLE nuke_queue (
  qid smallint(5) unsigned NOT NULL auto_increment,
  uid mediumint(9) NOT NULL default '0',
  uname varchar(40) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  story text,
  storyext text NOT NULL,
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL default '',
  alanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (qid),
  KEY qid (qid),
  KEY uid (uid),
  KEY uname (uname)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_queue'
--



--
-- Table structure for table 'nuke_quotes'
--

CREATE TABLE nuke_quotes (
  qid int(10) unsigned NOT NULL auto_increment,
  quote text,
  PRIMARY KEY  (qid),
  KEY qid (qid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_quotes'
--


INSERT INTO nuke_quotes VALUES (1,'Nos morituri te salutamus - CBHS');

--
-- Table structure for table 'nuke_referer'
--

CREATE TABLE nuke_referer (
  rid int(11) NOT NULL auto_increment,
  url varchar(100) NOT NULL default '',
  PRIMARY KEY  (rid),
  KEY rid (rid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_referer'
--



--
-- Table structure for table 'nuke_related'
--

CREATE TABLE nuke_related (
  rid int(11) NOT NULL auto_increment,
  tid int(11) NOT NULL default '0',
  name varchar(30) NOT NULL default '',
  url varchar(200) NOT NULL default '',
  PRIMARY KEY  (rid),
  KEY rid (rid),
  KEY tid (tid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_related'
--



--
-- Table structure for table 'nuke_reviews'
--

CREATE TABLE nuke_reviews (
  id int(10) NOT NULL auto_increment,
  date date NOT NULL default '0000-00-00',
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) default NULL,
  email varchar(60) default NULL,
  score int(10) NOT NULL default '0',
  cover varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  hits int(10) NOT NULL default '0',
  rlanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_reviews'
--



--
-- Table structure for table 'nuke_reviews_add'
--

CREATE TABLE nuke_reviews_add (
  id int(10) NOT NULL auto_increment,
  date date default NULL,
  title varchar(150) NOT NULL default '',
  text text NOT NULL,
  reviewer varchar(20) NOT NULL default '',
  email varchar(60) default NULL,
  score int(10) NOT NULL default '0',
  url varchar(100) NOT NULL default '',
  url_title varchar(50) NOT NULL default '',
  rlanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_reviews_add'
--



--
-- Table structure for table 'nuke_reviews_comments'
--

CREATE TABLE nuke_reviews_comments (
  cid int(10) NOT NULL auto_increment,
  rid int(10) NOT NULL default '0',
  userid varchar(25) NOT NULL default '',
  date datetime default NULL,
  comments text,
  score int(10) NOT NULL default '0',
  PRIMARY KEY  (cid),
  KEY cid (cid),
  KEY rid (rid),
  KEY userid (userid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_reviews_comments'
--



--
-- Table structure for table 'nuke_reviews_main'
--

CREATE TABLE nuke_reviews_main (
  title varchar(100) default NULL,
  description text
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_reviews_main'
--


INSERT INTO nuke_reviews_main VALUES ('Reviews Section Title','Reviews Section Long Description');

--
-- Table structure for table 'nuke_seccont'
--

CREATE TABLE nuke_seccont (
  artid int(11) NOT NULL auto_increment,
  secid int(11) NOT NULL default '0',
  title text NOT NULL,
  content text NOT NULL,
  counter int(11) NOT NULL default '0',
  slanguage varchar(30) NOT NULL default '',
  PRIMARY KEY  (artid),
  KEY artid (artid),
  KEY secid (secid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_seccont'
--



--
-- Table structure for table 'nuke_sections'
--

CREATE TABLE nuke_sections (
  secid int(11) NOT NULL auto_increment,
  secname varchar(40) NOT NULL default '',
  image varchar(50) NOT NULL default '',
  PRIMARY KEY  (secid),
  KEY secid (secid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_sections'
--



--
-- Table structure for table 'nuke_session'
--

CREATE TABLE nuke_session (
  uname varchar(25) NOT NULL default '',
  time varchar(14) NOT NULL default '',
  host_addr varchar(48) NOT NULL default '',
  guest int(1) NOT NULL default '0',
  KEY time (time),
  KEY guest (guest)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_session'
--


--
-- Table structure for table 'nuke_stats_date'
--

CREATE TABLE nuke_stats_date (
  year smallint(6) NOT NULL default '0',
  month tinyint(4) NOT NULL default '0',
  date tinyint(4) NOT NULL default '0',
  hits bigint(20) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stats_date'
--


--
-- Table structure for table 'nuke_stats_hour'
--

CREATE TABLE nuke_stats_hour (
  year smallint(6) NOT NULL default '0',
  month tinyint(4) NOT NULL default '0',
  date tinyint(4) NOT NULL default '0',
  hour tinyint(4) NOT NULL default '0',
  hits int(11) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stats_hour'
--


--
-- Table structure for table 'nuke_stats_month'
--

CREATE TABLE nuke_stats_month (
  year smallint(6) NOT NULL default '0',
  month tinyint(4) NOT NULL default '0',
  hits bigint(20) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stats_month'
--


--
-- Table structure for table 'nuke_stats_year'
--

CREATE TABLE nuke_stats_year (
  year smallint(6) NOT NULL default '0',
  hits bigint(20) NOT NULL default '0'
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stats_year'
--


--
-- Table structure for table 'nuke_stories'
--

CREATE TABLE nuke_stories (
  sid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  aid varchar(30) NOT NULL default '',
  title varchar(80) default NULL,
  time datetime default NULL,
  hometext text,
  bodytext text NOT NULL,
  comments int(11) default '0',
  counter mediumint(8) unsigned default NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  notes text NOT NULL,
  ihome int(1) NOT NULL default '0',
  alanguage varchar(30) NOT NULL default '',
  acomm int(1) NOT NULL default '0',
  haspoll int(1) NOT NULL default '0',
  pollID int(10) NOT NULL default '0',
  score int(10) NOT NULL default '0',
  ratings int(10) NOT NULL default '0',
  associated text NOT NULL,
  PRIMARY KEY  (sid),
  KEY sid (sid),
  KEY catid (catid),
  KEY counter (counter),
  KEY topic (topic)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stories'
--



--
-- Table structure for table 'nuke_stories_cat'
--

CREATE TABLE nuke_stories_cat (
  catid int(11) NOT NULL auto_increment,
  title varchar(20) NOT NULL default '',
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (catid),
  KEY catid (catid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_stories_cat'
--



--
-- Table structure for table 'nuke_topics'
--

CREATE TABLE nuke_topics (
  topicid int(3) NOT NULL auto_increment,
  topicname varchar(20) default NULL,
  topicimage varchar(20) default NULL,
  topictext varchar(40) default NULL,
  counter int(11) NOT NULL default '0',
  PRIMARY KEY  (topicid),
  KEY topicid (topicid)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_topics'
--


INSERT INTO nuke_topics VALUES (1,'phpnuke','phpnuke.gif','PHP-Nuke',0);

--
-- Table structure for table 'nuke_users'
--

CREATE TABLE nuke_users (
  user_id int(11) NOT NULL auto_increment,
  name varchar(60) NOT NULL default '',
  username varchar(25) NOT NULL default '',
  user_email varchar(255) NOT NULL default '',
  femail varchar(255) NOT NULL default '',
  user_website varchar(255) NOT NULL default '',
  user_avatar varchar(255) NOT NULL default '',
  user_regdate varchar(20) NOT NULL default '',
  user_icq varchar(15) default NULL,
  user_occ varchar(100) default NULL,
  user_from varchar(100) default NULL,
  user_interests varchar(150) NOT NULL default '',
  user_sig varchar(255) default NULL,
  user_viewemail tinyint(2) default NULL,
  user_theme int(3) default NULL,
  user_aim varchar(18) default NULL,
  user_yim varchar(25) default NULL,
  user_msnm varchar(25) default NULL,
  user_password varchar(40) NOT NULL default '',
  storynum tinyint(4) NOT NULL default '10',
  umode varchar(10) NOT NULL default '',
  uorder tinyint(1) NOT NULL default '0',
  thold tinyint(1) NOT NULL default '0',
  noscore tinyint(1) NOT NULL default '0',
  bio tinytext NOT NULL,
  ublockon tinyint(1) NOT NULL default '0',
  ublock tinytext NOT NULL,
  theme varchar(255) NOT NULL default '',
  commentmax int(11) NOT NULL default '4096',
  counter int(11) NOT NULL default '0',
  newsletter int(1) NOT NULL default '0',
  user_posts int(10) NOT NULL default '0',
  user_attachsig int(2) NOT NULL default '0',
  user_rank int(10) NOT NULL default '0',
  user_level int(10) NOT NULL default '1',
  broadcast tinyint(1) NOT NULL default '1',
  popmeson tinyint(1) NOT NULL default '0',
  user_active tinyint(1) default '1',
  user_session_time int(11) NOT NULL default '0',
  user_session_page smallint(5) NOT NULL default '0',
  user_lastvisit int(11) NOT NULL default '0',
  user_timezone tinyint(4) NOT NULL default '10',
  user_style tinyint(4) default NULL,
  user_lang varchar(255) NOT NULL default 'english',
  user_dateformat varchar(14) NOT NULL default 'D M d, Y g:i a',
  user_new_privmsg smallint(5) unsigned NOT NULL default '0',
  user_unread_privmsg smallint(5) unsigned NOT NULL default '0',
  user_last_privmsg int(11) NOT NULL default '0',
  user_emailtime int(11) default NULL,
  user_allowhtml tinyint(1) default '1',
  user_allowbbcode tinyint(1) default '1',
  user_allowsmile tinyint(1) default '1',
  user_allowavatar tinyint(1) NOT NULL default '1',
  user_allow_pm tinyint(1) NOT NULL default '1',
  user_allow_viewonline tinyint(1) NOT NULL default '1',
  user_notify tinyint(1) NOT NULL default '0',
  user_notify_pm tinyint(1) NOT NULL default '0',
  user_popup_pm tinyint(1) NOT NULL default '0',
  user_avatar_type tinyint(4) NOT NULL default '3',
  user_sig_bbcode_uid varchar(10) default NULL,
  user_actkey varchar(32) default NULL,
  user_newpasswd varchar(32) default NULL,
  PRIMARY KEY  (user_id),
  KEY uid (user_id),
  KEY uname (username),
  KEY user_session_time (user_session_time)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_users'
--


INSERT INTO nuke_users VALUES (1,'','Anonymous','','','','blank.gif','Nov 10, 2000','','','','','',0,0,'','','','',10,'',0,0,0,'',0,'','',4096,0,0,0,0,0,1,0,0,1,0,0,0,10,NULL,'english','D M d, Y g:i a',0,0,0,NULL,1,1,1,1,1,1,1,1,0,3,NULL,NULL,NULL);

--
-- Table structure for table 'nuke_users_temp'
--

CREATE TABLE nuke_users_temp (
  user_id int(10) NOT NULL auto_increment,
  username varchar(25) NOT NULL default '',
  user_email varchar(255) NOT NULL default '',
  user_password varchar(40) NOT NULL default '',
  user_regdate varchar(20) NOT NULL default '',
  check_num varchar(50) NOT NULL default '',
  time varchar(14) NOT NULL default '',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

--
-- Dumping data for table 'nuke_users_temp'
--



