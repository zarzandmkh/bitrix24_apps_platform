<?php 
include ROOT_DIR . '/app/model/model.php'; // main model class
include ROOT_DIR . '/app/controller/controller.php'; //main controller class
include ROOT_DIR . '/helpers/debug.php'; //debugging methods


if(empty($_REQUEST['action'])){
	header("HTTP/1.0 404 Not Found");
	exit('Requested page not found');
}

//autoload
spl_autoload_register(
	function ($class_name) {
		if(stristr($class_name, 'model')){
			$type = 'model';
		}else if (stristr($class_name, 'controller')){
			$type = 'controller';
		}else{
			$type = 'lib';
		}

		$class_name = str_replace(array('controller_', 'model_'), '', $class_name);
		$app_dir = ROOT_DIR . '/app/';
		$file = $app_dir . $type . '/' . $type . '-' . $class_name . '.php';
	    if(is_file($file))include $file;
	}
);

$controller_name = 'controller_' . $_REQUEST['action'];
$controller = new $controller_name;
$controller->index();

?>