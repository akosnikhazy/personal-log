<?php
/***********************
	Nikházy Ákos

musthave.php
***********************/
session_name('personal-log'.date("Y-m-d"));
session_start();

require('functions.php');

if(!isset($_SESSION['login']) 
    && 'login.php' != basename($_SERVER['SCRIPT_FILENAME'])) jumpTo('login.php');

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.class.php';
});

$db = new mysqli('localhost','YOURUSER','YOURPW','personallog');
$db->set_charset("utf8mb4");

$logThis = new Logger($db);

header("X-Frame-Options: DENY");
?>