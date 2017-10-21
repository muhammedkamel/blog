<?php

require_once __DIR__ . '/../models/post.php';
require_once __DIR__ . '/../helpers/xxs-filter.php';

class PostsController {
	public $post;

	public function __construct() {
		$this->post = new Post;
	}

	/**
	 *
	 * Method to paginate the posts
	 * @param $offset int the starting row to select
	 * @param $control boolean if false you must fetch the active posts only else fetch all to the dashboard
	 * @return $posts array of objects
	 *
	 */

	public function paginatePosts(int $offset, bool $control = false) {
		$condition = '';
		$bindings = [];
		if ($control === FALSE) {
			$condition = 'WHERE status_id= :status_id';
			$bindings = [':status_id' => ACTIVE];
		}

		$data = [
			'table' => 'posts',
			'fields' => '*',
			'where' => $condition,
			'bindings' => $bindings,
			'offset' => $offset,
			'limit' => LIMIT,
		];

		$posts = $this->formateArrayDates($this->post->select($data));
		return $posts;
	}

	/**
	 *
	 * Method to paginate posts with the statuses JOIN
	 * @param $offset the starting row
	 * @return $posts array of objects
	 *
	 */

	public function paginatePostsWithStatus(int $offset) {
		$data = [
			'table' => 'posts AS P, statuses AS S',
			'fields' => ['P.id', 'P.title', 'P.summery', 'P.body', 'P.publish_at', 'S.status'],
			'where' => 'WHERE P.status_id = S.id',
			'bindings' => [],
			'offset' => $offset,
			'limit' => LIMIT,
			'sort' => 'ORDER BY P.publish_at DESC',
		];

		$posts = $this->formateArrayDates($this->post->select($data));
		return $posts;
	}

	/**
	 *
	 * Method to add new post
	 * @param $data array of the post content
	 * @return boolean the query status
	 *
	 */

	public function addNewPost(array $data) {
		// @TODO validate data
		$data = XSSFilter::globalXssClean($data);
		// formate date to time stamp
		$data['publish_at'] = $this->formateDateTime($data['publish_at']);
		// this value will be retrieved from the session
		$data['admin_id'] = 1;
		return $this->post->insertRecord('posts', $data);
	}

	/**
	 *
	 * Method to get the post with id
	 * @param $id int the post ID
	 * @param $status_id default 0 if it has value it will get the posts with status_id = value
	 * @return $post object
	 */
	// TODO htmlentities to strings
	public function getPostByID(int $id, int $status_id = 0) {
		$id = XSSFilter::globalXssClean($id);
		$status_id = XSSFilter::globalXssClean($status_id);
		if ($status_id) {
			// getPostWithStatus
		} else {
			// getPost
		}
	}

	/**
	 *
	 * Method to edit post
	 * @param $id int the post ID
	 * @param $data array of the post content
	 * @return boolean the query result
	 *
	 */

	public function editPost(int $id, array $data) {
		$data = XSSFilter::globalXssClean($data);
		$id = XSSFilter::globalXssClean($id);
		$data['publish_at'] = $this->formateDateTime($data['publish_at']);
		$bindings = $this->post->getBindings($data);
		$bindings[':id'] = $id;
		return $this->post->update('posts', ['title', 'body', 'summery', 'status_id', 'publish_at'], 'WHERE id= :id LIMIT 1', $bindings);
	}

	/**
	 *
	 * Method to delete Post
	 * @param $id int the post ID
	 * @return Boolean the query result
	 *
	 */

	public function deletePost(int $id) {
		$id = XSSFilter::globalXssClean($id);
		return $this->post->deleteByID('posts', $id);
	}

	/**
	 *
	 * Method to formate the date and time from and to Timestamp, and DateTimePicker
	 * @param $date string has the date
	 * @param $to the formate you want to format the date to
	 * @return $newDate string with the formated date
	 *
	 */

	private function formateDateTime(string $date, string $to = 'timestamp') {
		$newDate = '';
		if ($to == 'timestamp') {
			$dateFormater = DateTime::createFromFormat('m/d/Y g:i A', $date);
			$newDate = $dateFormater->format('Y-m-d H:i:s');
		} else {
			$dateFormater = DateTime::createFromFormat('Y-m-d H:i:s', $date);
			$newDate = $dateFormater->format('m/d/Y g:i A');
		}
		return $newDate;
	}

	/**
	 *
	 * Method to Formate array of dates
	 * @param $dates array has dates
	 * @return $dates array with the dates formatted
	 *
	 */

	private function formateArrayDates(array $dates) {
		for ($i = 0; $i < count($dates); $i++) {
			$dates[$i]->publish_at = $this->formateDateTime($dates[$i]->publish_at, 'DateTimePicker');
		}
		return $dates;
	}

	/**
	 *
	 * Method to search for post
	 * @param $key string the search key
	 * @return $posts array of objects
	 *
	 */

	public function search(string $key) {
		$key = XSSFilter::globalXssClean($key);
		$data = [
			'table' => 'posts',
			'fields' => '*',
			'where' => "WHERE ( title LIKE CONCAT('%', :title, '%') OR body LIKE CONCAT('%', :body, '%') ) AND status_id= :status_id",
			'bindings' => [':title' => $key, ':body' => $key, ':status_id' => ACTIVE],
		];
		$posts = $this->post->select($data);
		$this->formateArrayDates($posts);
		return $posts;
	}
}