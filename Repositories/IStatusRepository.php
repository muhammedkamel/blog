<?php

namespace Blog\Repositories;

interface IStatusRepository{

	public function addStatus(string $status): bool;

	public function getStatuses();

}