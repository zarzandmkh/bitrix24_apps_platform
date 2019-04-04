<?php 
global $b24u;
ob_start();

if(empty($_REQUEST['auth']['application_token']))exit('You get here in wrong way');
$base_url = 'https://' . $_REQUEST['auth']['domain'];
$query_base_url = $b24u->query_base_url;

echo 'Webhook detected ' . $_REQUEST['hook'] . ': ' . date('Y-m-d h:i:s') . "\r\n";

switch ($_REQUEST['hook']) {
	case 'hookname':
			//your webhook handling code here
		break;
	default:
		exit();
		break;
}
$output = ob_get_clean();
file_put_contents(ROOT_DIR . '/logs/webhooks/webhooks-log-' . date('Y-m-d_h:i') . '.log', $output);
?>