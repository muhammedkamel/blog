<?php

namespace Blog\Models;

require_once __DIR__ . '/../Repositories/PostRepository.php';

use Blog\Repositories\PostRepository as PostRepo;

class Post {

	private $repo;
	private $data;

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
	public function __construct(array $data) {
		$this->repo = new PostRepo;
		$this->data = $data;
	}

	/**
	 *
	 * Method to insert a new post
	 * @return bool
	 *
	 */
	public function save() {
		if ($this->data) {
			return $this->repo->addPost($this->data);
		}
		return false;
	}

}
