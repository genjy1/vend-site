<?php
	/**
	* Short Query DB
	* ? - value, % - object or array, @ - functions
	* ?i -int, ?f - float, ?s - string
	*/
namespace DB;
class Placeholder
{
	private $errors = array();
	private $db;

	public function __construct($db){
		$this->db = $db;
	}

	public function placehold($sql, $params=array()){
		// replace all __ to prefix
		$sql = preg_replace('/([^"\'0-9a-z_])__([a-z_]+[^"\'])/Usi', "\$1".DB_PREFIX."\$2", $sql);
		if(false == (strpos($sql, "?", 0))){
			return $sql;
		}
		$sql = $this->build($sql, $params);
		// echo $sql."\n";
		return $sql;
	}

	private function build($sql, $params=array()){
		$args = $this->getPlaceholders($sql);
		if(!empty($args)){
			return $this->replacePlaceholders($sql, $args, $params);
		} else {
			return $sql;
		}
	}

	private function replacePlaceholders($sql, $args, $params){
		if(empty($params)){
			return $sql;
		}
		$i = 0;
		$paramsql = array();

		foreach ($args as $p => $symbol) {
			if($symbol == ' ' || $symbol == ','|| $symbol == ''){ //for single values
				$paramsql[] = "'" . $this->db->escape($params[$i]) . "'";
			} elseif($symbol == 'i'){ // for int
				$paramsql[] = "" . (int)$params[$i] . "";
			} elseif($symbol == 'f'){ // for float
				$paramsql[] = "" . (float)$params[$i] . "";
			} elseif($symbol == 's'){ // for string
				$paramsql[] = "'" . $this->db->escape((string)$params[$i]) . "'";
			} elseif($symbol == '%'){ // for arrays and objects
				$paramsql[] = $this->getAssocToSql($sql, $p, $params[$i]);
			}
			$i++;
		}


		// $sql = preg_replace('/(\%)/Usi', '', $sql);
		$sql = preg_replace('/(\?i)|(\?s)|(\?f)|(\?%)/Usi', '{%placeholder%}', $sql);

		// $sql = preg_replace('/(\?)/Usi', '{%placeholder%}', $sql); // for save symbol ? in data

		foreach ($paramsql as $s) {
			$sql = preg_replace('/({%placeholder%})/Usi', $s, $sql, 1);
		}

		return $sql;
	}

	private function getAssocToSql($sql, $pos, $param){
		$result_sql = '';
		if(is_object($param)){
			$param = (array)$param;
		}

		$p = $pos - 2;
		$ll = 0;
		$fl = 0;

		while ($fl == 0) {
			$c = substr($sql, $p, 1);
			if($c != ' ' && $ll == 0){
				$ll = $p;
			} elseif($c == ' ' && $ll != 0){
				$fl = ($p + 1);
				$ll = $ll - $fl + 1;
			}
			$p--;
		}

		$operator = substr($sql, $fl, $ll);

		switch (strtoupper($operator)) {
			case 'AND':
				foreach ($param as $key => $value) {
					$result_sql .= "AND `" . $key . "` = '" . $this->db->escape($value) . "' ";
				}
				$result_sql = substr($result_sql, 3);
				break;
			case 'SET':
				foreach ($param as $key => $value) {
					$result_sql .= " `" . $key . " ` = '" . $this->db->escape($value) . "', ";
				}
				$result_sql = substr($result_sql, 0, (strlen($result_sql) - 2));
				break;
			case 'IN(':
				foreach ($param as $key => $value) {
					$result_sql .= "'" . $this->db->escape($value) . "', ";
				}
				$result_sql = substr($result_sql, 0, (strlen($result_sql) - 2));
				break;
			default:
				# code...
				break;
		}

		if(preg_match("/(" . DB_PREFIX . ")/Usi", $operator)){
			$cols = '';
			$values = '';
			foreach ($param as $key => $value) {
				$cols .= $key . ", ";
				$values .= "'" . $this->db->escape($value) . "', ";
			}

			$cols = substr($cols, 0, (strlen($cols) - 2));
			$values = substr($values, 0, (strlen($values) - 2));

			$result_sql = "(" . $cols . ") VALUES (" . $values . ")";
		}

		return $result_sql;
	}

	private function getPlaceholders($sql){
		$args = array(); 
		$p = 0;

		while(false !== ($p = strpos($sql, "?", $p))){
			$c = substr($sql, ++$p, 1);
			// if placeholder function, save name of function, else symbol placeholder
			if($c != '@'){
				$arg = $c;
			} else {
				$s = strpos($sql, " ", ++$p);
				$l = $s - $p;
				$arg = substr($sql, ++$p, $l);
			}

			$args[$p] = $arg; 
			$p++;
			if($p >= strlen($sql)){
				break;
			}
		}

		return $args;
	}


}
