<?php

namespace Blog\Controllers;

require_once __DIR__ . '/../Models/Status.php';
require_once __DIR__ . '/../Helpers/XSSFilter.php';

use Blog\Models\Status as Status;
use Blog\Repositories\StatusRepository as StatusRepo;
use Blog\Helpers\XSSFilter as XSSFilter;

class StatusesController {
	
	private $repo;

	public function __construct(){
		$this->repo = new StatusRepo;
	}	

	public function addNewStatus(string $status){
		$status = XSSFilter::globalXssClean($status);
		$status = new Status($status);
		return $status->save();
	}

	public function getAllStatuses(){
		return $this->repo->getStatuses();
	}
}
