<?php

require_once __DIR__ . '/db.php';

class Paginator extends DB {


	// next, previous, offset
	/**
	 *
	 * Method to set the pagination fields next, previous, and offset
	 * @param $table string the table name to paginate
	 * @param $page int the requested page
	 * @param $condition string 
	 * @param $bindings array
	 * @return array with next, previous, offset
	 *
	 */
	public function paginate(string $table, int $page = 1, string $condition = '', array $bindings = []){
		$data = [
			'table' 	=> $table,
			'fields'	=> ['COUNT(1) AS count'],
			'where'		=> $condition,
			'bindings'	=> $bindings,
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


	/**
	 *
	 * Method to get the previous page number
	 * @param $page int the page number
	 * @return $page int the previous page number
	 *
	 */
	
	private function getPrevious($page){
		if( ($page - 1) < 1 ){
			return 1;
		}

		return --$page;
	}


	/**
	 *
	 * Method to get the next page number
	 * @param $page int the page number
	 * @param $numOfRows in the number of all rows
	 * @return $page int the next page number
	 *
	 */
	
	private function getNext($page, $numOfRows){
		$numOfPages = ceil($numOfRows / LIMIT);
		if( ($page + 1) >  $numOfPages){
			return max($numOfPages, 1);
		}
		return ++$page;
	}
}