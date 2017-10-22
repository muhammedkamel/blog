<?php
namespace Blog\Repositories;

interface IPostRepository {

	public function addPost(array $data): bool;

	public function editPost(int $id, array $data): bool;

	public function deletePost(int $id): bool;

	public function getPost(int $id);

	public function getPostWhenStatus(int $id, int $status_id);

	public function getPosts(int $offset);

	public function getPostsWhenStatus(int $offset, int $status_id);

	public function search(string $key);
}