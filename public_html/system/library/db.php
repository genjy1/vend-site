<?php
class DB {
	private $db;
	private $p = null;

	public function __construct($driver, $hostname, $username, $password, $database) {
		$class = 'DB\\' . $driver;
		$placeholder = 'DB\\Placeholder';
		$this->p = new $placeholder($this);

		if (class_exists($class)) {
			$this->db = new $class($hostname, $username, $password, $database);
		} else {
			exit('Error: Could not load database driver ' . $driver . '!');
		}
	}

	public function query() {
		$params = func_get_args();
		$sql = array_shift($params);
		$sql = $this->p->placehold($sql, $params);

		return $this->db->query($sql);
	}

	public function getField() {
		$params = func_get_args();
		$sql = array_shift($params);
		$sql .= " LIMIT 1";
		$sql = $this->p->placehold($sql, $params);

		$result = $this->db->query($sql);
		return array_shift($result->row);
	}

	public function getRow() {
		$params = func_get_args();
		$sql = array_shift($params);
		$sql = $this->p->placehold($sql, $params);

		$result = $this->db->query($sql);
		return $result->row;
	}

	public function getRows() {
		$params = func_get_args();
		$sql = array_shift($params);
		$sql = $this->p->placehold($sql, $params);

		$result = $this->db->query($sql);
		return $result->rows;
	}
	// public function query() {
	// 	$params = func_get_args();
	// 	$sql = array_shift($params);
	// 	$sql = $this->p->placehold($sql, $params);

	// 	if(!empty($params)){
	// 		$params = array_shift($params);
	// 		return $this->db->query($sql, $params);
	// 	} else {
	// 		return $this->db->query($sql);
	// 	}

	// }

	public function escape($value) {
		return $this->db->escape($value);
	}

	public function countAffected() {
		return $this->db->countAffected();
	}

	public function getLastId() {
		return $this->db->getLastId();
	}
}
