<?php
session_start();
error_reporting(E_ALL);
header('Content_type:text/html; charset=utf-8');
mb_internal_encoding('utf-8');

define('ROOT_DIR', dirname(__FILE__));
define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);

//числовые - без нулей
define('THIS_DAY', (int)date('j')); // current day
define('THIS_YEAR', (int)date('Y')); // current year
define('THIS_MONTH', (int)date('n')); // current month
define('PREVIOUS_MONTH', (THIS_MONTH == 1?12:THIS_MONTH-1) ); // previous month
define('PREV_PREVIOUS_MONTH', (THIS_MONTH == 1?11:(THIS_MONTH == 2?12:THIS_MONTH-2))); // pre-previous month
define('PREVIOUS_YEAR', (THIS_YEAR-1) ); // previous year

include ROOT_DIR . '/app/model/model.php'; //main model class
include ROOT_DIR . '/helpers/sqlite_pdo.php'; // class for working with db

// model and conroller сщттусешщт
if(!empty($_REQUEST['action'])){	
	$action 	= 	htmlspecialchars(strip_tags($_REQUEST['action'])); 
	$model 		= 	ROOT_DIR . '/app/model/model-' . $action . '.php'; 
	if(file_exists($model)){
		include $model;
		$classname = 'model_' . $action;
		$b24u = new $classname;
	}else{
		$b24u = new model;
	}	
	$b24u->db_connect();
	$b24u->connect_controller($action);
}else{
    header("HTTP/1.0 404 Not Found");
	exit('Requested page not found');
}

$b24u->db_disconnect();

//functions for debugging
function out($var, $var_name = '') {
	echo '<pre style="outline: 1px dashed red;padding:5px;margin:10px;color:white;background:black;">';
	if( ! empty($var_name)) {
		echo '<h3>' . $var_name . '</h3>';
	}
	if(is_string($var)){
		$var = htmlspecialchars($var);
	}
	print_r($var);
	echo '</pre>';
}

function out2($var, $var_name = '') {
	echo '<pre style="outline: 1px dashed red;padding:5px;margin:10px;color:white;background:black;">';
	if( ! empty($var_name)) {
		echo '<h3>' . $var_name . '</h3>';
	}
	var_dump($var);
	echo '</pre>';
}

?>