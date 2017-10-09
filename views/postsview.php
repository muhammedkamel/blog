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

    /**
     *
     * Method to show the active posts in the home page
     * @param $offset number the starting row default 0
     * @return $posts array of objects 
     *
     */
    
    public function showPosts($offset = 0){
        return $this->postsController->paginatePosts($offset);
    }

    /**
     *
     * Method to get all posts to be shown in the dashboard
     * @param $offset number the starting row default 0
     * @return $posts array of objects 
     */
    
    public function getPosts($offset = 0){
    	// offset starts with 0 then increases
		return $this->postsController->paginatePostsWithStatus($offset);
    }

    /**
     *
     * Method to get all statuses
     * @return array of objects with all statuses
     *
     */
    
    public function getStatuses(){
    	return $this->statusesController->getAllStatuses();
    }

    /**
     *
     * Method to add post responds to ajax request
     * @param $data array the post data (title, summery, body, status, date)
     * 
     */
    
    public function addPost($data){
    	// add post    
	    if($this->postsController->addNewPost($data)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }

    /**
     *
     * Method to show "Active" post in the home page 
     * @param $id int the id of the post
     * 
     *
     */
    
    public function showPost($id){
        return $this->postsController->getPostByID($id, ACTIVE);
    }

    /**
     *
     * Method to get post to the dashboard this method responds to ajax request
     * @param $id int the post id
     *
     */
    
    public function getPost($id){
    	if(($post = $this->postsController->getPostByID($id)) && ($statuses = $this->statusesController->getAllStatuses())){
    		
    		$data['post'] 	  = $post;
    		$data['statuses'] = $statuses;
	        
	        header('Content-Type: application/json');
	        echo json_encode($data);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    /**
     *
     * Method to edit post responds to the ajax only
     * @param $id int the post id
     * @param $data array has the post data
     *
     */
    
    public function editPost($id, $data){
    	if($this->postsController->editPost($id, $data)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    /**
     *
     * Method to delete post responds to the ajax only
     * @param $id int 
     * 
     *
     */
    
    public function deletePost($id){
    	if($this->postsController->deletePost($id)){
	        echo json_encode(['success' => true]);
	        exit;
	    }else{
	        header('HTTP/1.1 503 Service Temporarily Unavailable');
	    }
    }


    /**
     *
     * Method to set the next and previous links correctly
     * @param $page int the page number the default is 1
     * @return array has the next, previous, and the offset
     */
    
    public function paginate($page = 1, $condition = '', $bindings = []) {
    	return $this->paginator->paginate('posts', $page, $condition, $bindings);
    }


    /**
     *
     * Method to search for post in the title and body
     * @param $key string
     * @return array of objects
     */
    
    public function search($key){
        // @TODO filter key
        return $this->postsController->search($key);
    }
}