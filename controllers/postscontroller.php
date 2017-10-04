<?php

require_once __DIR__ . '/../models/post.php';

class PostsController {
	public $post;
	
	public function __construct() {
		$this->post = new Post;
	}

	public function paginatePosts($offset, $limit){
		$data = [
			'table' 	=> 'posts',
			'fields'	=> '*',
			'offset'	=> $offset,
			'limit'		=> $limit
		];

		return $this->post->select($data);
	}


	public function paginatePostsWithStatus($offset, $limit){
		$data = [
			'table' 	=> 'posts AS P, statuses AS S',
			'fields'	=> ['P.id', 'P.title', 'P.summery', 'P.body', 'P.publish_at', 'S.status'],
			'where'		=> 'WHERE P.status_id = S.id',
			'bindings'	=> [],
			'offset'	=> $offset,
			'limit'		=> $limit
		];

		return $this->post->select($data);	
	}


	public function addNewPost($data){
		// @TODO validate data
		// formate date to time stamp
		$date = DateTime::createFromFormat('d/m/Y H:i A', '20/10/2014 05:39 PM');
		$data['publish_at'] = $date->format('Y-m-d H:i:s');
		// this value will be retrieved from the session
		$data['user_id']	= 1;
		return $this->post->insertRecord('posts', $data);
	}


	public function getPostByID($id){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> 'WHERE id=:id',
			'bindings'	=> [':id' => $id],
			'limit'		=> 1
		];
		return $this->post->select($data)[0];
	}


	public function editPost($id, $data){
		return $this->post->update('posts', $data);
	}
}
