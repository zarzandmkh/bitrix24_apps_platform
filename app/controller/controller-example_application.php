<?php 
class controller_example_application extends controller{
	// example of simple application
	// you need to register application on application section on your bitrix24 with url https://your.domain/?action=example_application and choose users right
	function index(){
		$data['page_name'] = 'An example of bitrix24 cloud application on REST api';
		$user_name = $this->model->get_current_user_name();
		if($user_name){
			$data['user_name'] = $user_name;
		}
		$this->html = $this->model->load_view('example', $data);
		$this->output();
	}
}

 ?>