<?php

require_once __DIR__ . '/../models/post.php';

class PostsController {
	public $post;
	
	public function __construct(Post $post) {
		$this->post = $post;
	}

	public function getPosts($offset, $limit){
		$data = [
			'table' 	=> 'posts',
			'fields'	=> '*',
			'offset'	=> $offset,
			'limit'		=> $limit
		];

		return $this->post->select($data);
	}


}
