<?php
session_start();
error_reporting(E_ALL);
header('Content_type:text/html; charset=utf-8');
mb_internal_encoding('utf-8');

define('ROOT_DIR', dirname(__FILE__));
define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);

//numeric, without 0
define('THIS_DAY', (int)date('j')); // current day
define('THIS_YEAR', (int)date('Y')); // current year
define('THIS_MONTH', (int)date('n')); // current month
define('PREVIOUS_MONTH', (THIS_MONTH == 1?12:THIS_MONTH-1) ); // previous month
define('PREV_PREVIOUS_MONTH', (THIS_MONTH == 1?11:(THIS_MONTH == 2?12:THIS_MONTH-2))); // pre-previous month
define('PREVIOUS_YEAR', (THIS_YEAR-1) ); // previous year

include ROOT_DIR . '/app/startup.php'; 

?>