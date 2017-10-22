<?php

namespace Blog\Models;

require_once __DIR__ . '/../Repositories/IPRepository.php';

use Blog\Repositories\IPRepository as IPRepo;

class IP {

	private $repo;
	private $ip;
	private $admin_id;

	/**
	 *
	 * Method to get initialize new post
	 * @param $title string
	 * @param $body string
	 * @param $summery string
	 * @param $status int
	 * @param $publish_at string
	 *
	 */

	public function __construct(string $ip) {
		$this->repo = new IPRepo;
		$this->ip = $ip;
		$this->admin_id = 1; // supposed to get this value from method like auth()->id in laravel
	}

	/**
	 *
	 * Method to insert a new ip
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->canInsert()) {
			return $this->repo->addIP($this->ip, $this->admin_id);
		}
		return false;
	}

	/**
	 *
	 * Method to check if the post fields all set
	 * @return bool
	 *
	 */
	private function canInsert() {
		return ($this->ip && $this->admin_id);
	}

}
