<?php 


//your project path goes here
define("BASE_URL","http://localhost/tonyMCA/");
//define("BASE_PATH", "c:/Users/Tony/Desktop/tonyMCA/");
define("BASE_PATH", "/opt/lampp/htdocs/tonyMCA/"); // Linux path for WSL




//set your timezone here
date_default_timezone_set('asia/kolkata');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require(BASE_PATH.'config/database.php'); 
require( BASE_PATH .'classes/database.php'); 
require( BASE_PATH .'classes/FormAssist.class.php'); 
require(BASE_PATH.'classes/FormValidator.class.php'); 
require( BASE_PATH .'classes/DataAccess.class.php');
 


?>    