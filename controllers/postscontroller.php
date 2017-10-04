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
			'offset'	=> $offset,
			'limit'		=> $limit
		];

		return $this->post->select($data);	
	}


	public function addNewPost($data){
		// @TODO validate data
		
	}
}
