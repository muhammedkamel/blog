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

	public function paginateIPs($offset = 0){
		$data = [
			'table' 	=> 'banned_ips',
			'fields'	=> '*',
			'offset'	=> $offset,
			'limit'		=> LIMIT
		];

		return $this->ip->select($data);
	}

	public function paginate($page = 1){
		return $this->paginator->paginate('banned_ips', $page);
	}

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

	public function allowIP($id){
		if($this->ip->deleteByID('banned_ips', $id)){
			echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}


	public function getIP($id){
		$data = [
			'table'		=> 'banned_ips',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id',
			'bindings'	=> [':id' => $id],
			'limit'		=> 1
		];

		$ipInfo = $this->ip->select($data)[0];

		if($ipInfo){
			header('Content-Type: application/json');
			echo json_encode($ipInfo);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
	}

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