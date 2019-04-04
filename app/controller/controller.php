<?php 
/*main controller class*/
class controller {
	public $html;
	public $model;

	function __construct(){
		$model_name =  'model_' . $_REQUEST['action']; //model according to action
		$this->model = new $model_name;
		$this->html = $this->model->load_view('header');
	}

	function load_footer ($footer = 'footer'){
		$this->html .= $this->model->load_view($footer);

	}

	public function output($html = ''){
		if(empty($html))$html = $this->html;
		$this->load_footer();
		echo $this->html;
	}

}
?>