<?php

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
    die ("You can't access this file directly...");
}

$module_name = basename(dirname(__FILE__));

$index = 1;

function one() {
    global $module_name;
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"one\"<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?name=$module_name&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}

function two() {
    global $module_name;
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"two\"";
    echo "<ul>";
    echo "<li><a href=\"modules.php?name=$module_name&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}


function AddonSample() {
    global $module_name;
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php)<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?name=$module_name&amp;file=index&amp;func=one\">Function One</a>";
    echo "<li><a href=\"modules.php?name=$module_name&amp;file=index&amp;func=two\">Function Two</a>";
    echo "<li><a href=\"modules.php?name=$module_name&amp;file=f2\">Call to file f2.php</a>";
    echo "</ul>";
    echo "You can now use Administration interface to activate or deactivate any module. As an Admin you can always "
         ."access to your Inactive modules for testing purpouses.";
    CloseTable();
    include("footer.php");
}

switch($func) {

    default:
    AddonSample();
    break;
    
    case "one":
    one();
    break;

    case "two":
    two();
    break;

}

?>