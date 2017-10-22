<?php
namespace Blog\Repositories;

require_once __DIR__ . '/IPostRepository.php';
require_once __DIR__ . '/../Libs/DB.php';

use Blog\Libs\DB as DB;
use Blog\Repositories\IPostRepository;

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
	 * check that all fields has values
	 *
	 */
	private function isValid($data){
		return ($data['title'] && $data['body'] && $data['summery'] && $data['status_id'] && $data['publish_at']);
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

		return $this->db->select($data);
	}

	/**
	 *
	 * Method to get POST by id and status
	 * @param $id int
	 * @param $status_id int
	 * @return object or bool
	 *
	 */
	public function getPostWhenStatus(int $id, int $status_id){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id AND status_id= :status_id',
			'bindings'	=> [':id' => $id, ':status_id' => $status_id],
			'limit'		=> 1
		];

		return $this->db->select($data);
	}


	/**
	 *
	 * Method to get all posts with there statuses with limit defined in the config file
	 * @param $offset int 
	 * @return array
	 *
	 */	
	public function getPosts(int $offset) {
		$data = [
			'table' => 'posts AS P, statuses AS S',
			'fields' => ['P.id', 'P.title', 'P.summery', 'P.body', 'P.publish_at', 'S.status'],
			'where' => 'WHERE P.status_id = S.id',
			'bindings' => [],
			'offset' => $offset,
			'limit' => LIMIT,
			'sort' => 'ORDER BY P.publish_at DESC',
		];

		return $this->db->select($data);
	}



	/**
	 *
	 * Method to get all posts that has a specific status_id
	 * @param $offset int 
	 * @return array
	 *
	 */	
	public function getPostsWhenStatus(int $offset, int $status_id) {
		
		$data = [
			'table' => 'posts',
			'fields' => '*',
			'where' => 'WHERE status_id= :status_id',
			'bindings' => [':status_id' => $status_id],
			'offset' => $offset,
			'limit' => LIMIT,
		];

		return $this->db->select($data);
	}

	/**
	 *
	 * Method to search all posts with status 
	 * @param $key string
	 * @return array
	 *
	 */	
	public function search(string $key) {
		$data = [
			'table' => 'posts',
			'fields' => '*',
			'where' => "WHERE ( title LIKE CONCAT('%', :title, '%') OR body LIKE CONCAT('%', :body, '%') ) AND status_id= :status_id",
			'bindings' => [':title' => $key, ':body' => $key, ':status_id' => ACTIVE],
		];
		return $this->db->select($data);
	}
}