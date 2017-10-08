<?php 
require_once __DIR__.'/../models/db.php';
require_once __DIR__.'/../helpers/session.php';


class Authenticate
{
	
    private $db;
    
    public function __construct()
    {
        $this->db = new DB;
    }

    

    public function login($username, $password){
    	// @TODO validate, and santize username, and password
    	if($id = intval($this->isUser($username, $password))){
    		Session::put('username', $username);
    		header('Location: admin/html/posts.php');
    	}else{
    		require_once ROOT_DIR.'/translations.php';
    		return $translate['en']['invalid_login'];
    	}
    }


    public function is_loggedin(){
    	if(!Session::has('username')){
    		header('Location: '.ROOT_URL.'views/login.php');
            exit;
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

    
    public function logout(){
        Session::flush();
        header('Location: '.ROOT_URL.'views/login.php');
    }

    private function encrypt($value){
    	return sha1(sha1($value).'@'.md5($value));
    }

}