<?php 
class model_example_application extends model{

	public function get_current_user_name(){

		$request_url = 'http://' . $_REQUEST['DOMAIN'] . '/rest/user.current.json'; //request url to bitrix24 rest api
		$request_params = array('auth' => $_REQUEST['AUTH_ID']);

		$user = $this->get_current_user($request_url, $request_params);

		if(!empty($user['FULL_NAME'])){
			return $user['FULL_NAME'];
		}else{
			return $user;
		}
	}
}
?>