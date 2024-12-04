<?php

/**************************************************************************/
/* PHP-NUKE: Advanced Content Management System                           */
/* ============================================                           */
/*                                                                        */
/* This is the language module with all the system messages               */
/*                                                                        */
/* If you made a translation, please go to the site and send to me        */
/* the translated file. Please keep the original text order by modules,   */
/* and just one message per line, also double check your translation!     */
/*                                                                        */
/* You need to change the second quoted phrase, not the capital one!      */
/*                                                                        */
/* If you need to use double quotes (") remember to add a backslash (\),  */
/* so your entry will look like: This is \"double quoted\" text.          */
/* And, if you use HTML code, please double check it.                     */
/**************************************************************************/

define("_CHARSET","BIG5");
define("_SEARCH","站內搜尋");
define("_LOGIN","登入");
define("_WRITES","寫著");
define("_POSTEDON","發表於");
define("_NICKNAME","登入名稱");
define("_PASSWORD","密碼");
define("_WELCOMETO","歡迎來");
define("_EDIT","編輯");
define("_DELETE","刪除");
define("_POSTEDBY","貼出者為");
define("_READS","閱讀");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">回到上一頁</a> ]");
define("_COMMENTS","意見");
define("_PASTARTICLES","以前的文章");
define("_OLDERARTICLES","更舊的文章");
define("_BY","由");
define("_ON","於");
define("_LOGOUT","登出");
define("_WAITINGCONT","內容等待中");
define("_SUBMISSIONS","投遞");
define("_WREVIEWS","作品評論等待中");
define("_WLINKS","鏈結等待中");
define("_EPHEMERIDS","臨時公告");
define("_ONEDAY","歷史上的今天...");
define("_ASREGISTERED","還沒有帳號嗎？<a href=\"modules.php?name=Your_Account\">請建立帳號</a>。完成註冊的使用者可以享受佈景主題管理，評註的組態調整及具名發表評論等功能。");
define("_MENUFOR","選單於");
define("_NOBIGSTORY","今日尚無大事發生");
define("_BIGSTORY","本日點閱率最高的報導：");
define("_SURVEY","投票");
define("_POLLS","投票一覽");
define("_PCOMMENTS","評論：");
define("_RESULTS","結果");
define("_HREADMORE","閱讀詳細內容...");
define("_CURRENTLY","目前有");
define("_GUESTS","位來賓和");
define("_MEMBERS","會員在線上");
define("_YOUARELOGGED","您的登入身分為");
define("_YOUHAVE","您有");
define("_PRIVATEMSG","個私人訊息");
define("_YOUAREANON","您正匿名使用中, 請來<a href=\"modules.php?name=Your_Account\">免費註冊</a>");
define("_NOTE","註：");
define("_ADMIN","管理");
define("_WERECEIVED","本站共有");
define("_PAGESVIEWS","次瀏覽。起始日期：");
define("_TOPIC","新聞主題");
define("_UDOWNLOADS","下載資源");
define("_VOTE","投票");
define("_VOTES","投票");
define("_MVIEWADMIN","查看：只有管理員能看到");
define("_MVIEWUSERS","查看：只有註冊使用者能看到");
define("_MVIEWANON","查看：只有匿名使用者能看到");
define("_MVIEWALL","查看：所有訪客");
define("_EXPIRELESSHOUR","期限：小於一小時");
define("_EXPIREIN","期限為");
define("_HTTPREFERERS","HTTP 來源");
define("_UNLIMITED","無限");
define("_HOURS","小時");
define("_RSSPROBLEM","目前該網站新聞標題有些問題");
define("_SELECTLANGUAGE","選擇語言");
define("_SELECTGUILANG","選擇語言：");
define("_NONE","無");
define("_BLOCKPROBLEM","<center>這個區塊發生問題。</center>");
define("_BLOCKPROBLEM2","<center>這個區塊現在沒有內容.</center>");
define("_MODULENOTACTIVE","對不起, 這個模組沒有啟動!");
define("_NOACTIVEMODULES","不啟動模組");
define("_FORADMINTESTS","(管理者測試用)");
define("_BBFORUMS","討論區管理");
define("_ACCESSDENIED", "拒絕存取");
define("_RESTRICTEDAREA", "你正嘗試著去存取一個受限制的區域.");
define("_MODULEUSERS", "很抱歉,我們網站的這個標題只開於給 <i>註冊會員</i><br><br>你可以按 <a href=\"modules.php?name=Your_Account&op=new_user\">這裏</a>免費註冊,然後你將可以<br>存取這個標題而不受限制.謝謝.<br><br>");
define("_MODULESADMINS", "很抱歉,我們網站的這個標題只開於給<i>管理者</i><br><br>");
define("_HOME","回到首頁");
define("_HOMEPROBLEM","發生重大問題: 我們沒有一個首頁!!!");
define("_ADDAHOME","在首頁新增一個模組");
define("_HOMEPROBLEMUSER","首頁發生了一個問題. 請稍後查看以往記錄.");
define("_MORENEWS","更多的新聞標題");
define("_ALLCATEGORIES","所有類別");
define("_DATESTRING","%A, %B %d @ %T %Z");
define("_DATESTRING2","%A, %B %d");
define("_CHAT","Chat");
define("_REGISTERED","Registered");
define("_CHATGUESTS","Guests");
define("_USERSTALKINGNOW","Users Talking Now");
define("_ENTERTOCHAT","Enter To Chat");
define("_CHATROOMS","Available Rooms");
define("_SECURITYCODE","Security Code");
define("_TYPESECCODE","Type Security Code");
define("_ASSOTOPIC","Associated Topics");

/*****************************************************/
/* Function to translate Datestrings                 */
/*****************************************************/

function translate($phrase) {
    switch($phrase) {
	case "xdatestring":	$tmp = "%A, %B %d @ %T %Z"; break;
	case "linksdatestring":	$tmp = "%d-%b-%Y"; break;
	case "xdatestring2":	$tmp = "%A, %B %d"; break;
	default:		$tmp = "$phrase"; break;
    }
    return $tmp;
}

?>