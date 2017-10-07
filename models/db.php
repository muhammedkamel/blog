<?php

require_once __DIR__ . '/../config.php';

/**
 * Class deals with the db
 */
class DB {

	private $conn;

	/**
	 * constructor to initiate connection
	 */
	public function __construct() {
		try {
			$this->conn = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}


	// $table, $fields, $offset, $limit, $where, bindings
	public function select($data) {

		if(!isset($data['fields'])){
			$data['fields'] = '*';
		}

		$fields = ($data['fields'] == '*') ? '*' : implode(',', $data['fields']);
				
		if(!isset($data['where']) && empty($data['where'])){
			$data['where'] = '';
			$data['bindings'] = [];
		}
		
		$query 	= "SELECT {$fields} FROM {$data['table']} {$data['where']} ";
		
		if(isset($data['sort'])){
			$query = $query.' '.$data['sort'] .' ';
		}

		if(isset($data['offset'], $data['limit']) && ($data['offset'] >= 0) && ($data['limit'] > 0)){
			$query .= "LIMIT {$data['limit']} OFFSET {$data['offset']}";
		}elseif(isset($data['limit']) && $data['limit'] > 0){
			$query .= "LIMIT {$data['limit']}";
		}
		
		$stmt 	= $this->conn->prepare($query);
		$stmt->execute($data['bindings']);
		
		return $this->getResults($stmt);
	}

	// public function bind_where($where) {
	// 	$where_stmt = "";
	// 	$bindings 	= [];
	// 	foreach ($where as $field => $value) {
	// 		$where_stmt .= $field . ' = :' . $field;
	// 		$bindings[':'.$field] = $value;
	// 	}
	// 	return ['where_stmt' => $where_stmt, 'bindings' => $bindings];
	// }
	private function getResults($stmt){
		$result = [];
		while ($row = $stmt->fetchObject()) {
		    $result[] = $row;
		}
		return $result;
	}



	public function insertRecord($table, $data){

		$query = "INSERT INTO {$table} " . $this->formatFieldsAndValues($data);
		
		$bindings = $this->getBindings($data);

		$stmt = $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}


	private function formatFieldsAndValues($data){
		$query = 'SET ';
		
		foreach ($data as $field => $value) {
			$query .= $field .' = :' . $field .',';
		}

		return rtrim($query, ',');
	}



	public function getBindings($data){
		$bindings = [];
		foreach ($data as $field => $value) {
			$bindings[':'.$field] = $value;
		}
		return $bindings;
	}


	public function update($table, $fields, $conditions, $bindings){
		$query = "UPDATE {$table} " . $this->formatFields($fields) .' '. $conditions;
		
		// $bindings = $this->getBindings($data);
		$stmt = $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}

	/**
	*/
	public function deleteByID($table, $id){
		return $this->delete($table, "WHERE id = :id", [':id' => $id], 1);
	}

	/**
	* Method to delete from db
	* @param $table string the table name to delete from it
	* @param $where string the where statment starts by "WHERE"
	* @param $bindings associative array of the bingings like [':id' => 1]
	* @param $limit int number of rows that you want to delete them using the where condition
	* @return boolean the query status
	*/
	public function delete($table, $where = '', $bindings = [], $limit = ''){
		if($limit) $limit = 'LIMIT '.$limit;
		$query 	= "DELETE FROM {$table} {$where} {$limit}";
		$stmt 	= $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}



	private function formatFields($fields){
		$query = 'SET ';

		foreach($fields as $field){
			$query .= $field.' = :'.$field.',';
		}
		return rtrim($query, ',');
	}

	/**
	 *
	 * destructor to destroy connection
	 *
	 */
	public function __destruct() {
		$this->conn = NULL;
	}
}
