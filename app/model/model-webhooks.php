<?php 
class model_webhooks extends model{
	private   $input_hook_token = '';	// input webhooks token
	protected $base_url;				// server url
	protected $query_base_url;			// server url with webhook calling	

	function __construct(){
		parent::__construct();
		$this->base_url = 'https://' . $_REQUEST['auth']['domain']; 
		$this->query_base_url = $this->base_url . '/rest/1/' . $this->input_hook_token;
	}
}


?>