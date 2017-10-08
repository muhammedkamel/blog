<?php

require_once __DIR__ . '/../models/ip.php';
require_once __DIR__.'/../models/paginator.php';

class IPsController {

	private $ip;
	private $paginator;

	public function __construct(){
		$this->ip 			= new IP;
		$this->paginator 	= new Paginator;
	}

	/**
	 *
	 * Method to get ips in the requested page
	 * @param $offset int the starting row
	 * @return $ips array of objects 
	 *
	 */
	
	public function paginateIPs($offset = 0){
		$data = [
			'table' 	=> 'banned_ips',
			'fields'	=> '*',
			'offset'	=> $offset,
			'limit'		=> LIMIT
		];

		return $this->ip->select($data);
	}


	/**
	 *
	 * Method to paginate the ips to pages 
	 * @param $page int the page number the default is 1
	 * @return array has the next, previous, and the offset
	 *
	 */
	
	public function paginate($page = 1){
		return $this->paginator->paginate('banned_ips', $page);
	}

	/**
	 *
	 * Method to add a new ip to the banned-ips  this method responds only to the ajax requests
	 * @param $ip string validated ip
	 * 
	 *
	 */
	
	public function banIP($ip){
		// @TODO check if it's ip and sanitize it
		$data['ip'] 		= $ip;
		$data['admin_id'] 	= 1;
		if($this->ip->insertRecord('banned_ips', $data)){
			echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}


	/**
	 *
	 * Method to delete ip from the banned ips this method responds only to the ajax requests
	 * @param $id int the id of the IP
	 * 
	 */
	
	public function allowIP($id){
		if($this->ip->deleteByID('banned_ips', $id)){
			echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}


	/**
	 *
	 * Method to get an IP with it's id this method responds only to the ajax requests
	 * @param $id int the IP id
	 * 
	 *
	 */
	
	public function getIP($id){
		$data = [
			'table'		=> 'banned_ips',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id',
			'bindings'	=> [':id' => $id],
			'limit'		=> 1
		];

		$ipInfo = $this->ip->select($data);
		if($ipInfo) $ipInfo = $ipInfo[0];

		if($ipInfo){
			header('Content-Type: application/json');
			echo json_encode($ipInfo);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}

	/**
	 *
	 * Method to update an ip this method responds only to the ajax requests
	 * @param $id int the IP id
	 * @param $ip string the new IP
	 *
	 */
	
	public function editIP($id, $ip){
		// @TODO validate $data
		if($this->ip->update('banned_ips', ['ip'],'WHERE id= :id LIMIT 1', [':id' => $id, ':ip' => $ip])){
			echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}



	// it is better to write in an .htaccess file and ban it
	/**
	 *
	 * Method to check if the accessed user is banned
	 *
	 */
	
	public function isBanned(){
		$ip = $this->getRealIpAddr();
		$data = [
			'table'		=> 'banned_ips',
			'fields'	=> ['COUNT(1) AS count'],
			'where'		=> 'WHERE ip=:ip',
			'bindings'	=> [':ip' => $ip],
			'limit'		=> 1
		];

		$ipInfo = $this->ip->select($data);

		if(isset($ipInfo[0]) && $ipInfo[0]->count > 0){
			header('Location: '.ROOT_URL.'401.php');
			exit;
		}
	}

	/**
	 *
	 * Method to get the real IP of the client
	 * @return $ip string the IP
	 */
	
	private function getRealIpAddr()
	{
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	    {
	      $ip=$_SERVER['HTTP_CLIENT_IP'];
	    }
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	    {
	      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else
	    {
	      $ip=$_SERVER['REMOTE_ADDR'];
	    }
	    return $ip;
	}
}