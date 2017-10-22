<?php
namespace Blog\Repositories;

require_once __DIR__ . '/IIPRepository.php';
require_once __DIR__ . '/../Libs/DB.php';

use Blog\Libs\DB as DB;
use Blog\Repositories\IIPRepository;

class IPRepository implements IIPRepository {

	private $db;

	/**
	 *
	 * Method to initialize connection
	 *
	 */
	public function __construct() {
		$this->db = new DB;
	}

	
	public function addIP(string $ip, int $admin_id): bool{
		$data['ip'] = $ip;
		$data['admin_id'] = $admin_id;
		return $this->db->insertRecord('banned_ips', $data);
	}

	public function deleteIP(int $id) : bool{
		return $this->db->deleteByID('banned_ips', $id);
	}

	public function editIP(int $id, string $ip) : bool{
		return $this->db->update('banned_ips', ['ip'],'WHERE id= :id LIMIT 1', [':id' => $id, ':ip' => $ip]);
	}

	public function getIP(int $id){
		$data = [
			'table'		=> 'banned_ips',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id',
			'bindings'	=> [':id' => $id],
			'limit'		=> 1
		];

		$ipInfo = $this->db->select($data);
		if($ipInfo) {
			$ipInfo = $ipInfo[0];
		}

		return $ipInfo;
	}
	
	public function getIPs(int $offset){
		$data = [
			'table' 	=> 'banned_ips',
			'fields'	=> '*',
			'offset'	=> $offset,
			'limit'		=> LIMIT
		];

		return $this->db->select($data);
	}

	public function isExists(string $ip) : bool{
		$data = [
			'table'		=> 'banned_ips',
			'fields'	=> ['COUNT(1) AS count'],
			'where'		=> 'WHERE ip=:ip',
			'bindings'	=> [':ip' => $ip],
			'limit'		=> 1
		];

		if(isset($ipInfo[0]) && $ipInfo[0]->count > 0){
			return true;
		}else{
			return false;
		}
	}
}