<?php

namespace Blog\Libs;

require_once __DIR__ . '/../config.php';

/**
 * Class deals with the db
 */
class DB {

	// connection object
	private $conn;

	/**
	 * constructor to initiate connection
	 */
	public function __construct() {
		try {
			// PDO connect
			$this->conn = new \PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (\PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}
	}

	/**
	 *
	 * @method select
	 * @param $data array has the table, selected fields, offest, limit, where condition, bindings, sort (order by)
	 * @return $results fetched rows
	 */
	public function select($data) {

		if (!isset($data['fields'])) {
			$data['fields'] = '*';
		}

		$fields = $this->formatSelectFields($data);

		$data = $this->setSelectConditionAndBindings($data);

		$query = "SELECT {$fields} FROM {$data['table']} {$data['where']} ";

		if (isset($data['sort'])) {
			$query = $query . ' ' . $data['sort'] . ' ';
		}

		$query = $this->setSelectLimitAndOffset($data, $query);

		$stmt = $this->conn->prepare($query);
		$stmt->execute($data['bindings']);

		return $this->getResults($stmt);
	}

	/**
	 *
	 * Method to formate the selected fields
	 * @param $data array of data that contains the fields
	 * @return $fields string the formated fields
	 *
	 */
	private function formatSelectFields($data) {
		return ($data['fields'] == '*') ? '*' : implode(',', $data['fields']);
	}

	private function setSelectConditionAndBindings($data) {
		if (!isset($data['where']) && empty($data['where'])) {
			$data['where'] = '';
			$data['bindings'] = [];
		}
		return $data;
	}

	/**
	 * Method to set limit and offset
	 * @param $data array that has the limit and the offset
	 * @param $query string the select query
	 * @return $query string the query string
	 *
	 */
	private function setSelectLimitAndOffset($data, $query) {
		if (isset($data['offset'], $data['limit']) && ($data['offset'] >= 0) && ($data['limit'] > 0)) {
			$query .= "LIMIT {$data['limit']} OFFSET {$data['offset']}";
		} elseif (isset($data['limit']) && $data['limit'] > 0) {
			$query .= "LIMIT {$data['limit']}";
		}
		return $query;
	}
	
	
	/**
	 *
	 * Method to fetch data as array of objects
	 * @param $stmt PDO Object fetch the result of this statment
	 * @return $result array of objects (rows)
	 */

	private function getResults($stmt) {
		$result = [];
		while ($row = $stmt->fetchObject()) {
			$result[] = $row;
		}
		return $result;
	}

	/**
	 *
	 * Method to insert in the db
	 * @param $table string the name of the table
	 * @param $data associative array of the fields and values
	 * @return boolean the statment status
	 *
	 */

	public function insertRecord($table, $data) {

		$query = "INSERT INTO {$table} " . $this->formatFieldsAndValues($data);

		$bindings = $this->getBindings($data);

		$stmt = $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}

	/**
	 *
	 * Method to formate the fields and bind it in the insert and update methods
	 * @param $data array of fields and their values
	 * @return $query string the formated and binded fields
	 *
	 */

	private function formatFieldsAndValues($data) {
		$query = 'SET ';

		foreach ($data as $field => $value) {
			$query .= $field . ' = :' . $field . ',';
		}

		return rtrim($query, ',');
	}

	/**
	 *
	 * Method to get the bindings for the insert and update methods
	 * @param $data array of the fields and their values
	 * @return $bindings an associative array for the binded fields and their values [':id' => 1]
	 *
	 */

	public function getBindings($data) {
		$bindings = [];
		foreach ($data as $field => $value) {
			$bindings[':' . $field] = $value;
		}
		return $bindings;
	}

	/**
	 *
	 * Method to update
	 * @param $table string the name of the table
	 * @param $fields indexed array has the fields that will be updated
	 * @param $conditions string the where clause the params binded in it  (WHERE id = :id)
	 * @param $bindings associative array has the binded values and their values [':id'=> 1, ':ip' => '127.0.0.1']
	 * @return boolean the status of the query success or fail (TRUE, FALSE)
	 */

	public function update($table, $fields, $conditions, $bindings) {
		$query = "UPDATE {$table} " . $this->formatFields($fields) . ' ' . $conditions;

		// $bindings = $this->getBindings($data);
		$stmt = $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}

	/**
	 *
	 * Method to delete row with it's id
	 * @param $table string the name of the table
	 * @param $id int the row id
	 * @return boolean the status of the query true or false
	 */

	public function deleteByID($table, $id) {
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
	public function delete($table, $where = '', $bindings = [], $limit = '') {
		if ($limit) {
			$limit = 'LIMIT ' . $limit;
		}

		$query = "DELETE FROM {$table} {$where} {$limit}";
		$stmt = $this->conn->prepare($query);

		return $stmt->execute($bindings);
	}

	/**
	 *
	 * Method to formate the insert and update fields
	 * @param $fields array has the fields that want to be binded with their values
	 * @return $query string with the fields binded
	 *
	 */

	private function formatFields($fields) {
		$query = 'SET ';

		foreach ($fields as $field) {
			$query .= $field . ' = :' . $field . ',';
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
