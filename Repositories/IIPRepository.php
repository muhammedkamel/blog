<?php

namespace Blog\Repositories;

interface IIPRepository{

	public function addIP(string $ip, int $admin_id): bool;

	public function deleteIP(int $id) : bool;

	public function editIP(int $id, string $ip) : bool;

	public function getIP(int $id);

	public function isExists(string $ip) : bool;

	public function getIPs(int $offset);

}