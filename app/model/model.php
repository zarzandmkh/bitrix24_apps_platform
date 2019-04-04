<?php 
/*main model*/
class model {
    public $dbh;                                // db object
    public $chief_ids      = array(1);          // heads id
    public $current_user;                       // current logged data
    public $pdf;
    public $is_chief;                           //is current user head
	 
    function __construct(){
    	$this->db_connect();
    	if(!empty($_REQUEST['action'])){
        	$this->current_user = $this->get_current_user('http://'.$_REQUEST['DOMAIN'].'/rest/user.current.json', array_merge($_REQUEST, array('auth' => $_REQUEST['AUTH_ID'])));
    	}
    	$this->is_chief = in_array($this->current_user['ID'], $this->chief_ids)? true:false;
    }
    function __destruct(){
    	$this->db_disconnect();
    }


	/**
	* sends request to b24 api and returns answer
	* @param string $url - request url
	* @param array request parameters
	* @return array with result or false if there is an error in response
	*/
	public function get_response ($url, $params = array()) {
		if(empty($url))return false;

		$curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_POSTFIELDS => http_build_query($params),
        ));
        $result = json_decode(curl_exec($curl), true);
        curl_close($curl);
        if(!empty($result['error']))exit(__METHOD__ . ': ' . $result['error_description']);
        return $result;
	}

    /**
    * connect db 
    */
    public function db_connect(){
        require_once('helpers/sqlite_pdo.php');
        $this->dbh = new DB("sqlite:db.sqlite");
        return $this->dbh;
    }

    /**
    * closing db connection
    */
    public function db_disconnect(){
        return $this->dbh = null;
    }

    /**
    * adding data to db
    * @param array $data data - key is the name of column and value is value to be vritten to the column 
    * @param string $table - db table name
    * @return id of added column or false if there is an error
    */
    public function add_data($data=array(), $table){
        if(!is_array($data)){return false;}

        $data['date_add'] = time();
        $data['date_edit'] = 0;

        $into = '';
        $values = '';

        $i = 0;//счетчик
        foreach($data as $key => $value){
            if(count($data) == 1 || $i == (count($data) - 1)){
                $into .= '`' . $key . '` ';
                $values .= '\'' . $value . '\' ';
            }else{
                $into .= '`' . $key . '`, ';
                $values .= '\'' . $value . '\', ';
            }
            $i++;
        }

        $sql = "INSERT INTO `" . $table . "`(" . $into . ") VALUES (" . $values . ")";
        $d = $this->dbh->exec($sql);
        if($d){
            $last_id = $this->dbh->row("SELECT last_insert_rowid() as 'last_id'");
            return $last_id['last_id'];
        }else{
            return false;
        }
    }

    /**
    * editing db data
    * @param mixed $where_value a value by which we can identify column
    * @param array $data data (same structure as in add_data method)
    * @param string $table db table name
    * @param string $where where parameter
    * @return integer 1 if success 0 if not
    */
    public function edit_data($where_value = 0, $data, $table = '', $where = 'id'){
        if(empty($where_value) || empty($table))return false;
        $set = '';
        $i = 0;
        foreach($data as $key => $value){
            if(count($data) == 1 || $i == (count($data) - 1)){
                $set .= '`' . $key . '` = \'' . $value . '\' ';
            }else{
                $set .= '`' . $key . '` = \'' . $value . '\', ';
            }
            $i++;
        }
        $sql = "UPDATE `$table` SET $set WHERE `" . $where . "` = $where_value";
        return $this->dbh->exec($sql);
        
    }

    /**
    * returns current user data
    * @return array with user data
    */
    public function get_current_user($url = '', $params = array()){
        if(empty($url))$url = 'http://' . $_REQUEST['DOMAIN'] . '/rest/user.current.json';
        if(empty($params['auth']))$params['auth'] = $_REQUEST['AUTH_ID'];
        $user = $this->get_response($url, $params)['result'];
        $user['FULL_NAME'] = $user['NAME'] . ' ' . $user['LAST_NAME']; 
        return $user;
    }

    /**
    * returns user data by id
    * @return array with user data
    */
    public function get_user($id){
        $user = $this->get_response('http://'.$_REQUEST['DOMAIN'].'/rest/user.get.json', array('auth'=>$_REQUEST['AUTH_ID'], 'ID'=>$id));
        if(!empty($user['result'][0])){
            $user = $user['result'][0];
        }else{
           return array();
        }
        $user['FULL_NAME'] = $user['NAME'] . ' ' . $user['LAST_NAME']; 
        return $user;
    }

    /**
    * returns all users data
    * @return array with users data
    */
    public function get_users(){
        $users = $this->get_response('http://'.$_REQUEST['DOMAIN'].'/rest/user.get.json', array_merge($_REQUEST, array('auth'=>$_REQUEST['AUTH_ID'])));
        if(!empty($users['result'])){
            foreach ($users['result']  as $key => &$user) {
        		if(!empty($user['NAME'])){
        			$user['FULL_NAME'] = $user['NAME'] . ' ' . $user['LAST_NAME']; 
        		}else{
        			$user['FULL_NAME'] = $user['EMAIL']; 	
        		}
            }
        }else{
           return array();
        }
        return $users['result'];
    }

    /**
    * sends notification to user in bitrix24 system битрикс24
    * @param string $message - notification text
    * @param integer $user_id - user id
    */
    public function notify($user_id, $message){
        $url = $this->query_base_url . '/im.notify.json';
        $params = array(
            'to'        => $user_id,
            'message'   => $message
        );
        return $this->get_response($url, $params);
    }


    /**
    * returns project data by id (b24 project)
    * @param integer $id - project id 
    * @return array with project data
    */
    public function get_project_by_id($id = 0){
        if(!$id)return array();
        $project = $this->get_response('http://'.$_REQUEST['DOMAIN'].'/rest/sonet_group.get.json', array( 'auth'=>$_REQUEST['AUTH_ID'], 'FILTER'=>array('ID' => $id) ));
        return !empty($project['result'][0])?$project['result'][0]:array();
    }

    /**
    * apllies html template and data returns ready html string with template and data on it
    * @param string $name tpl template name (without extension)
    * @param array $data array with data
    * @return string html markup text
    */
    function load_view($name = 'index', $data=array()) {
        $full_name = ROOT_DIR . '/app/views/' . $name . '.tpl';      
        if (file_exists($full_name)) {
            $view = $full_name;
        } else {
            exit('Error: Missing template file: ' . $full_name);
        }       
        extract($data);        
        ob_start();
        include $view;
        return ob_get_clean();
    }

    /**
    * connecting controller
    * @param string $action  action parameter (controller name)
    */
    public function connect_controller($action){
        $controller = ROOT_DIR . '/app/controller/controller-' . $action . '.php';
        if(file_exists($controller)){
            include $controller;
        }else{
            return $this->action_404();
        }
    }

    /**
    * loads model we need by action
    * @param string $action  action parameter
    * @return object model object
    */
    public function load_model($action){
        $model = ROOT_DIR . '/app/model/model-' . $action . '.php';
        if(file_exists($model)){
            include $model;
            $classname = 'model_' . $action;
            if(class_exists($classname)){
              $object_model = new $classname;  
            }else{
            $object_model  = null;
          }
          return $object_model;
        }else{
            return null;
        }
    }

    /**
    * applies number of month and returns month name in russian
    * @param number $month number of month without 0
    * @return string name of month in rusiian
    */
    protected function month_to_ru($month){
        $months_ru = array('', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
        if(!empty($months_ru[$month])){
            return $months_ru[$month];
        }else{
            return ''; 
        }
    }

    /**
    * float formatting (deletes 0s after point or leaves two numbers if it is no 0 - example - 1.000 wil return 1 and 1.2544584 will return 1.25)
	* @param float formatted number
    */
    public function custom_number_format($number){
    	return rtrim(rtrim(number_format($number, 2, '.', ' '), '0'), '.');
    }

    /**
	* redirecting
	* @param string $url - url
	* @param array $params - parameters
    */
	public function custom_redirect($url, $params = array()){
		$url = $url . ( count($params) > 0 ? '?'.http_build_query($params):'' );
		header("Location:$url");
		exit();
	}


    public function action_404(){
        header("HTTP/1.0 404 Not Found");
        exit('Page not found');
    }


    /**
    * creates zip archive
    * @param string $dir directory uri
    * @param string $zipname name of archive
    * @param string $filesdir - files directory if other than $dir
    * @return array name and path array(name, file_full_path)
    */
    function create_zip_archive($dir, $zipname = '', $files = array(), $filesdir = ''){
        if(empty($zipname))$zipname - rand() . '_' . time();
        if(!empty($filesdir) && !is_dir($filesdir))exit(__METHOD__ . ': files directory not found');

        $zip_name = $zipname . '.zip';
        $zip_file = $dir . '/' . $zip_name;

        $zip = new ZipArchive();
        if(!$zip->open($zip_file, (ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) )){
            exit(__METHOD__ . ': не удалось создать zip архив');
        }

        foreach ($files as $file) {
           $zip->addFile( (!empty($filesdir)?$filesdir:$dir) . $file, $file);
        }

        $zip->close();

        return array('name'=>$zip_name, 'file_full_path'=>$zip_file);
    }

    /**
    * generates pdf file with given html
    * @param string $html html markup . there is restrictions (see TCPDF docs)
    * @param string $filename file dir. all directories muxt be created
    */
    function html_to_pdf($html, $filename) {
    	if(!class_exists('TCPDF')){
    		include ROOT_DIR . '/helpers/tcpdf/tcpdf.php';
			include ROOT_DIR . '/helpers/tcpdf_extended_classes.php';
    	}
    	$pdf = new TCPDF_EXTENDED;
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetHeaderFont(array('Arial', '', 10));
        $pdf->SetFooterFont(array('Arial', '', 12));
        $pdf->SetMargins(PDF_PADDING_LEFT, PDF_PADDING_TOP, PDF_PADDING_RIGHT, true);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output(getcwd().'/'.$filename, 'F');
    }

    function rus_to_lat($text){
        $characters = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'kh',  'ц' => 'ts',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
            
            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'KH',  'Ц' => 'TS',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($text, $characters);
    }

    /**
    * loads helper / creates model::helper_helper_name property 
    * @param string $helper helper class and file name
    */
    function load_helper($helper) {
        $helper_name = 'helper_' . $helper;
        if(is_file(ROOT_DIR . '/helpers/' . $helper . 'php'))include ROOT_DIR . '/helpers/' . $helper . 'php';
        if(class_exists($helper)){
          $this->$helper_name = new $helper;  
        }
    }


}
 
?>
