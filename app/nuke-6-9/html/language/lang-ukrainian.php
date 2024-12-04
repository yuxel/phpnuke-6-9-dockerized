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

define("_CHARSET","windows-1251");
define("_SEARCH","Пошук");
define("_LOGIN","Вхід");
define("_WRITES","пише");
define("_POSTEDON","Опубліковано");
define("_NICKNAME","Логін");
define("_PASSWORD","Пароль");
define("_WELCOMETO","Ласкаво просимо на");
define("_EDIT","Редагувати");
define("_DELETE","Витерти");
define("_POSTEDBY","Опублікував");
define("_READS","переглядів");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Назад</a> ]");
define("_COMMENTS","коментарів ");
define("_PASTARTICLES","Старі публікації");
define("_OLDERARTICLES","Старі статті");
define("_BY","");
define("_ON","");
define("_LOGOUT","Вихід");
define("_WAITINGCONT","Надходження");
define("_SUBMISSIONS","Нові статті");
define("_WREVIEWS","Нові огляди");
define("_WLINKS","Нові ресурси");
define("_EPHEMERIDS","Вислови");
define("_ONEDAY","В такий день як сьогодні...");
define("_ASREGISTERED","<a href=\"modules.php?name=Your_Account&op=RegNewUser\">Зареєструватись</a>");
define("_MENUFOR","Меню");
define("_NOBIGSTORY","Сьогодні статті не поступали. <a href=\"submit.php\">Напишіть свою!</a>");
define("_BIGSTORY","Сама популярна сьогодні стаття: ");
define("_SURVEY","Голосування");
define("_POLLS","Голосування");
define("_PCOMMENTS","Коментарів:");
define("_RESULTS","Результати");
define("_HREADMORE","читати далі...");
define("_CURRENTLY","");
define("_GUESTS","гостей і");
define("_MEMBERS","авторів.");
define("_YOUARELOGGED","Ви зайшли як");
define("_YOUHAVE","В вас");
define("_PRIVATEMSG","повідомлень.");
define("_YOUAREANON","Вибачте, система вас не впізнала. Ви можете <a href=\"modules.php?name=Your_Account\">зареєструватись тут</a>.");
define("_NOTE","Примітки:");
define("_ADMIN","Адмін:");
define("_WERECEIVED","");
define("_PAGESVIEWS","переглянутих сторінки починаючи з");
define("_TOPIC","Тема");
define("_UDOWNLOADS","Скачувань");
define("_VOTE"," голос");
define("_VOTES","голосів");
define("_MVIEWADMIN","Перегляд: тільки для адміністраторів");
define("_MVIEWUSERS","Перегляд: тільки для зареєстрованих");
define("_MVIEWANON","Перегляд: тільки для анонімних");
define("_MVIEWALL","Перегляд: для всіх");
define("_EXPIRELESSHOUR","Expiration: менш ніж за годину");
define("_EXPIREIN","Expiration in");
define("_HTTPREFERERS","HTTP звернення");
define("_UNLIMITED","Необмежений");
define("_HOURS","Годин");
define("_RSSPROBLEM","Виникли проблеми з заголовками цього сайту");
define("_SELECTLANGUAGE","Вибрати мову");
define("_SELECTGUILANG","Вибрати мову інтерфейсу:");
define("_NONE","Немає");
define("_BLOCKPROBLEM","<center>There is a problem right now with this block.</center>");
define("_BLOCKPROBLEM2","<center>There isn't content right now for this block.</center>");
define("_MODULENOTACTIVE","Sorry, this Module isn't active!");
define("_NOACTIVEMODULES","Inactive Modules");
define("_FORADMINTESTS","(for Admin tests)");
define("_BBFORUMS","Forums");
define("_ACCESSDENIED", "Access Denied");
define("_RESTRICTEDAREA", "You are trying to access to a restricted area.");
define("_MODULEUSERS", "We are Sorry but this section of our site is for <i>Registered Users Only</i><br><br>You can register for free by clicking <a href=\"modules.php?name=Your_Account&op=new_user\">here</a>, then you can<br>access to this section without restrictions. Thanks.<br><br>");
define("_MODULESADMINS", "We are Sorry but this section of our site is for <i>Administrators Only</i><br><br>");
define("_HOME","Home");
define("_HOMEPROBLEM","There is a big problem here: we have not a Homepage!!!");
define("_ADDAHOME","Add a Module in your Home");
define("_HOMEPROBLEMUSER","There is a problem right now on the Homepage. Please check it back later.");
define("_MORENEWS","More in News Section");
define("_ALLCATEGORIES","All Categories");
define("_DATESTRING","%d.%m.%Y");
define("_DATESTRING2","%d.%m.%Y");
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