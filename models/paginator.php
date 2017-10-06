<?php

require_once __DIR__ . '/db.php';

class Paginator extends DB {


	// next, previous, offset
	public function paginate($table, $page = 1){
		$data = [
			'table' 	=> $table,
			'fields'	=> ['COUNT(1) AS count'],
			'limit'		=> 1
		];

		$numOfRows 	= intval($this->select($data)[0]->count);
		$offset   	= ($page - 1) * LIMIT;
		$previous	= $this->getPrevious($page);
		$next		= $this->getNext($page, $numOfRows);

		return [
				'next' 		=> $next,
				'previous'	=> $previous,
				'offset'	=> $offset
			];
	}


	private function getPrevious($page){
		if( ($page - 1) < 1 ){
			return 1;
		}

		return --$page;
	}


	private function getNext($page, $numOfRows){
		$numOfPages = ceil($numOfRows / LIMIT);
		if( ($page + 1) >  $numOfPages){
			return max($numOfPages, 1);
		}
		return ++$page;
	}
}