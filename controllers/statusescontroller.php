<?php

require_once __DIR__ . '/../models/status.php';

class StatusesController {
	public $status;
	
	public function __construct() {
		$this->status = new Status;
	}

	public function getAllStatuses(){
		$data = [
			'table' 	=> 'statuses',
			'fields'	=> '*'
		];
		return $this->status->select($data);
	}
}
