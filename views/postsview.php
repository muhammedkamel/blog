<?php

require_once __DIR__.'/../controllers/postscontroller.php'; 
require_once __DIR__.'/../controllers/statusescontroller.php'; 
require_once __DIR__.'/../models/paginator.php';
class PostsView
{

	private $postsController;
	private $statusesController;
	private $paginator;
    /**
     * summary
     */
    public function __construct()
    {
        $this->postsController 		= new PostsController;
        $this->statusesController 	= new StatusesController;
        $this->paginator 	= new Paginator;
    }

    public function getPosts($offset = 0){
    	// offset starts with 0 then increases
		return $this->postsController->paginatePostsWithStatus($offset);
    }


    public function getStatuses(){
    	return $this->statusesController->getAllStatuses();
    }

    public function addPost($data){
    	// add post    
	    if($this->postsController->addNewPost($data)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    public function getPost($id){
    	if($post = $this->postsController->getPostByID($id)){
	        // get the post status
	        // $post->status = $statuses[$post->status_id - 1]->status;
	        $status_id = $post->status_id;
	        header('Content-Type: application/json');
	        echo json_encode($post);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    public function editPost($id, $data){
    	if($this->postsController->editPost($id, $data)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    public function deletePost($id){
    	if($this->postsController->deletePost($id)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    public function paginate($page = 1) {
    	return $this->paginator->paginate('posts', $page);
    }

}