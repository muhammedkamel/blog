<?php

require_once __DIR__ . '/../models/post.php';

class PostsController {
	public $post;
	
	public function __construct() {
		$this->post = new Post;
	}

	public function paginatePosts($offset, $control = false){
		$condition = '';
		$bindings  = [];
		if($control === FALSE){
			$condition = 'WHERE status_id= :status_id';
			$bindings  = [':status_id' => ACTIVE];
		}	
		
		$data = [
			'table' 	=> 'posts',
			'fields'	=> '*',
			'where'		=> $condition,
			'bindings'	=> $bindings,
			'offset'	=> $offset,
			'limit'		=> LIMIT
		];

		$posts = $this->formateArrayDates($this->post->select($data));
		return $posts;
	}


	public function paginatePostsWithStatus($offset){
		$data = [
			'table' 	=> 'posts AS P, statuses AS S',
			'fields'	=> ['P.id', 'P.title', 'P.summery', 'P.body', 'P.publish_at', 'S.status'],
			'where'		=> 'WHERE P.status_id = S.id',
			'bindings'	=> [],
			'offset'	=> $offset,
			'limit'		=> LIMIT,
			'sort'		=> 'ORDER BY P.publish_at DESC'
		];

		$posts = $this->formateArrayDates($this->post->select($data));
		return $posts;	
	}


	public function addNewPost($data){
		// @TODO validate data
		// formate date to time stamp
		$data['publish_at'] = $this->formateDateTime($data['publish_at']);
		// this value will be retrieved from the session
		$data['admin_id']	= 1;
		return $this->post->insertRecord('posts', $data);
	}


	public function getPostByID($id){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> "WHERE id=:id AND status_id= :status_id",
			'bindings'	=> [':id' => $id, ':status_id' => ACTIVE],
			'limit'		=> 1
		];
		$post = $this->post->select($data)[0];
		$post->publish_at = $this->formateDateTime($post->publish_at, 'DateTimePicker');
		return $post;
	}


	public function editPost($id, $data){
		$data['publish_at'] = $this->formateDateTime($data['publish_at']);
		$bindings = $this->post->getBindings($data);
		$bindings[':id'] = $id;
		return $this->post->update('posts', ['title', 'body', 'summery', 'status_id', 'publish_at'], 'WHERE id= :id LIMIT 1', $bindings);
	}


	public function deletePost($id){
		return $this->post->deleteByID('posts', $id);
	}

	private function formateDateTime($date, $to='timestamp'){
		$newDate = '';
		if($to == 'timestamp'){
			$dateFormater = DateTime::createFromFormat('m/d/Y g:i A', $date);
			$newDate = $dateFormater->format('Y-m-d H:i:s');
		}else{
			$dateFormater = DateTime::createFromFormat('Y-m-d H:i:s', $date);
			$newDate = $dateFormater->format('m/d/Y g:i A');
		}
		return $newDate;
	}

	private function formateArrayDates($dates){
		for($i=0; $i<count($dates); $i++){
			$dates[$i]->publish_at = $this->formateDateTime($dates[$i]->publish_at, 'DateTimePicker');
		}
		return $dates;
	}

	public function search($key){
		$data = [
			'table'		=> 'posts',
			'fields'	=> '*',
			'where'		=> "WHERE ( title LIKE CONCAT('%', :title, '%') OR body LIKE CONCAT('%', :body, '%') ) AND status_id= :status_id",
			'bindings'	=> [':title' => $key, ':body' => $key, ':status_id' => ACTIVE]
		];
		$posts = $this->post->select($data);
		$this->formateArrayDates($posts);
		return $posts;
	}
}