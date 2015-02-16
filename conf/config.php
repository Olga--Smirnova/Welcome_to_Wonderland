<?php
/**
  * config. php
  * Author: Olga Smirnova
  * November 2014
  * 1 -set up timezone
  * 2 -set up files' pathes
  * 3 -set up JS files' paths as variables
*/
 
    date_default_timezone_set('Pacific/Auckland');
    error_reporting(0);
    ini_set("display_errors", 0);

//Client side
    define("HOME_PATH", "http://olga.smirnova.yoobee.net.nz/_Assignments/WE03/public_html");
    define("CSS_PATH", HOME_PATH.'/assets/css');
    define("IMG_ORIGINAL_PATH", 'assets/images/original/');
    define("IMG_THUMBS_PATH", 'assets/images/thumbs/');
    define("IMG_DISPLAY_PATH", 'assets/images/display/');
       
// Server side
    define("DOC_PATH", $_SERVER['DOCUMENT_ROOT'].'/_Assignments/WE03');
    define("CLASS_PATH", DOC_PATH.'/classes');

// Script names and paths
    $script_common = '<script src="assets/js/script.js"></script>';
    $script_scroll1 = '<script src="assets/js/dist/js/bind-polyfill.min.js"></script>';
    $script_scroll2 = '<script src="assets/js/dist/js/smooth-scroll.min.js"></script>';
    $script_index = '<script src="assets/js/index.js"></script>';
    $script_signin = '<script src="assets/js/signin.js"></script>';
    $script_profile = '<script src="assets/js/profile.js"></script>';
    $script_leavecomment = '<script src="assets/js/leavecomment.js"></script>';
    $script_friends = '<script src="assets/js/friends.js"></script>';
    $script_wrpages = '<script src="assets/js/wrpages.js"></script>';    
//EOF config.php