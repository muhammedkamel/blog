<?php
namespace Blog\Repositories;

interface IPostRepository {

	public function addPost(string $title, string $body, string $summery, int $status, string $publish_at, int $admin_id): bool;

	public function editPost(int $id, string $title, string $body, string $summery, int $status, string $publish_at, int $admin_id): bool;

	public function deletePost(int $id): bool;

	public function getPost(int $id);

	public function getPostWithStatus(int $id);

	public function getPosts($offset);

}