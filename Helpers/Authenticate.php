<?php 

namespace Blog\Helpers;

require_once __DIR__.'/../Libs/DB.php';
require_once __DIR__.'/../Helpers/session.php';
require_once __DIR__ . '/../Helpers/XSSFilter.php';

use Blog\Libs\DB as DB;
use Blog\Helpers\XSSFilter as XSSFilter;


class Authenticate
{
	
    private $db;
    
    public function __construct()
    {
        $this->db = new DB;
    }

    
    /**
     *
     * Method to login the admin
     * @param $username string 
     * @param $password string 
     *
     */
    
    public function login(string $username, string $password){
    	// @TODO validate, and santize username, and password
        $username = XSSFilter::globalXssClean($username);
        $password = XSSFilter::globalXssClean($password);
        
    	if($id = intval($this->isUser($username, $password))){
    		\Session::put('username', $username);
    		header('Location: admin/html/posts.php');
    	}else{
    		require_once ROOT_DIR.'/translations.php';
    		return $translate['en']['invalid_login'];
    	}
    }

    /**
     *
     * Method to check if the user logged in using session
     *
     */
    public function is_loggedin(){
    	if(!\Session::has('username')){
    		header('Location: '.ROOT_URL.'views/login.php');
            exit;
    	}
    }

    /**
     *
     * Method to check if the credentials is already exists
     * @param $username string 
     * @param $password string 
     * @return $id or 0 in order to the user existance
     *
     */
    
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

    /**
     *
     * Method to logout destroy session
     *
     */
    public function logout(){
        \Session::flush();
        header('Location: '.ROOT_URL.'views/login.php');
    }

    /**
     *
     * Method to encrypt string 
     * @param $value string 
     * @return $value encrypted string
     *
     */
    private function encrypt($value){
    	return sha1(sha1($value).'@'.md5($value));
    }

}