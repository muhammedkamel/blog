<?php

require_once __DIR__ . '/IPostRepository.php';
require_once __DIR__ . '/../Libs/DB.php';

use DOTW\Libs\DB as DB;
use DOTW\Repositories\IPostRepository;

class PostRepository implements IPostRepository {

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
	 * Method to add post
	 * @param $data array
	 * @return bool
	 *
	 */
	public function addPost(array $data): bool{
		if(!isset($data['admin_id']) || empty($data['admin_id'])){
			$data['admin_id'] = 1;
		}
		if($this->isValid($data)){
			return $this->db->insertRecord('posts', $data);
		}
		return false;
	}

	/**
	 *
	 * Method to update post
	 * @param $id int
	 * @param $data array
	 * @return bool
	 *
	 */
	public function editPost(int $id, array $data): bool{
		$bindings = $this->db->getBindings($data);
		$bindings[':id'] = $id;
		if($this->isValid($data)){
			return $this->db->update('posts', ['title', 'body', 'summery', 'status_id', 'publish_at'], 'WHERE id= :id LIMIT 1', $bindings);
		}
		return false;
	}

	/**
	 *
	 * Method to delete post
	 * @param $id int
	 * @return bool
	 *
	 */
	public function deletePost(int $id): bool{
		if($id){
			return $this->db->deleteByID('posts', $id);
		}
		return false;
	}

	/**
	 *
	 * Method to get POST by id
	 * @param $id int
	 * @return object or bool
	 *
	 */
	public function getPost(int $id){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id',
			'bindings'	=> [':id' => $id],
			'limit'		=> 1
		];

		$post = $this->db->select($data);

		if(count($post) > 0){
			$post = $post[0];
			$post->publish_at = $this->formateDateTime($post->publish_at, 'DateTimePicker');
		}
		
		return $post;
	}

	/**
	 *
	 * Method to get POST by id and status
	 * @param $id int
	 * @param $status_id int
	 * @return object or bool
	 *
	 */
	public function getPostWithStatus(int $id, int $status_id){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id AND status_id= :status_id',
			'bindings'	=> [':id' => $id, ':status_id' => $status_id],
			'limit'		=> 1
		];

		$post = $this->db->select($data);

		if(count($post) > 0){
			$post = $post[0];
			$post->publish_at = $this->formateDateTime($post->publish_at, 'DateTimePicker');
		}
		
		return $post;
	}

	public function getPosts($offset);


	/**
	 *
	 * check that all fields has values
	 *
	 */
	private function isValid($data){
		return ($data['title'] && $data['body'] && $data['summery'] && $data['status'] && $data['publish_at']);
	}
}