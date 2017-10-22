<?php
namespace Blog\Repositories;

require_once __DIR__ . '/IStatusRepository.php';
require_once __DIR__ . '/../Libs/DB.php';

use Blog\Libs\DB as DB;
use Blog\Repositories\IStatusRepository;

class StatusRepository implements IStatusRepository {

	private $db;

	/**
	 *
	 * Method to initialize connection
	 *
	 */
	public function __construct() {
		$this->db = new DB;
	}

	/**
	 *
	 * Method to add new status
	 * @param $status string
	 * @return bool
	 *
	 */
	
	public function addStatus(string $status) : bool{
		if($status){
			return $this->db->insertRecord('statuses', $status);
		}
	}


	/**
	 *
	 * Method to get all statuses
	 * @return array
	 *
	 */
	
	public function getStatuses(){
		$data = [
			'table' => 'statuses',
			'fields' => '*',
		];
		return $this->db->select($data);
	}

}