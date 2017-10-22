<?php

namespace Blog\Controllers;

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Helpers/XSSFilter.php';

use Blog\Helpers\XSSFilter as XSSFilter;
use Blog\Models\Post as Post;
use Blog\Repositories\PostRepository as PostRepo;

class PostsController {

	private $repo;

	public function __construct() {
		$this->repo = new PostRepo;
	}

	/**
	 *
	 * Method to paginate the posts
	 * @param $offset int the starting row to select
	 * @return $posts array of objects
	 *
	 */
	public function paginatePosts(int $offset) {
		$posts = $this->repo->getPostsWhenStatus($offset, ACTIVE);
		$posts = XSSFilter::escape($posts);
		return $this->formateArrayDates($posts);
	}

	/**
	 *
	 * Method to paginate posts with the statuses JOIN
	 * @param $offset the starting row
	 * @return $posts array of objects
	 *
	 */
	public function paginatePostsWithStatus(int $offset) {
		$posts = $this->repo->getPosts($offset);
		$posts = XSSFilter::escape($posts);
		return $this->formateArrayDates($posts);
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
		$data['admin_id'] = 1; // supposed to have method like auth()->id in larave
		$post = new Post($data);
		return $post->save();
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
			$post = $this->repo->getPostWhenStatus($id, $status_id);
		} else {
			// getPost
			$post = $this->repo->getPost($id);
		}

		if (count($post) > 0) {
			$post = $post[0];
			$post = XSSFilter::escape($post);
			$post->publish_at = $this->formateDateTime($post->publish_at, 'DateTimePicker');
		}
		$post = XSSFilter::escape($post);
		return $post;
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
		return $this->repo->editPost($id, $data);
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
		return $this->repo->deletePost($id);
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
			$dateFormater = \DateTime::createFromFormat('m/d/Y g:i A', $date);
			$newDate = $dateFormater->format('Y-m-d H:i:s');
		} else {
			$dateFormater = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
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
		$posts = $this->repo->search($key);
		$posts = XSSFilter::escape($posts);
		return $this->formateArrayDates($posts);
	}
}