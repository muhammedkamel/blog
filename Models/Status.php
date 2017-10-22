<?php

namespace Blog\Models;

require_once __DIR__ . '/../Repositories/StatusRepository.php';

use Blog\Repositories\StatusRepository as StatusRepo;

class Status {

	private $repo;
	private $status;

	/**
	 *
	 * Method to get initialize new post
	 * @param $status string
	 *
	 */
	public function __construct(string $status) {
		$this->repo = new StatusRepo;
		$this->status = $status;
	}

	/**
	 *
	 * Method to insert a new post
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->status) {
			return $this->repo->addStatus($this->status);
		}
		return false;
	}

}
