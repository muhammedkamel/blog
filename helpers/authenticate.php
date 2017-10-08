<?php 
require_once __DIR__.'/../models/db.php';
require_once('vendor/autoload.php');

class Authenticate
{
	
    private $db;
    private $session;
    
    public function __construct()
    {
        $this->db = new DB;
        // Create a new gears session.
		$session = new Gears\Session();
    }

    public function login($username, $password){
    	// @TODO validate, and santize username, and password
    	if($id = intval($this->isUser($username, $password))){
    		// Session::set('username', $username);
    		$this->session->__set('username', $username);
    		header('Location: admin/html/posts.php');
    	}else{
    		require_once ROOT_DIR.'/translations.php';
    		return $translate['en']['invalid_login'];
    	}
    }


    public function is_loggedin(){
    	if(!$this->session->__get('username', $username)){
    		exit;
    		header('Location: '.ROOT_URL.'views/login.php');
    	}
    }

    private function isUser($username, $password){
    	$password = $this->encrypt($password);
    	$data = [
			'table'		=> 'admins',
			'fields'	=> ['id'],
			'where'		=> "WHERE username=:username AND password= :password",
			'bindings'	=> [':username' => $username, ':password' => $password],
			'limit'		=> 1
		];
		
		if($user = $this->db->select($data)){
			return $user[0]->id;
		}
		return 0;
    }

    
    private function encrypt($value){
    	return sha1(sha1($value).'@'.md5($value));
    }
}