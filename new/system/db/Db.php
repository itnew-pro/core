<?php

namespace system\db;

use system\App;
use system\base\Model;
use Exception;

class Db
{

	private static $_isConnect = false;

	public $tableName = "";
	public $condition = "";
	public $params = array();
	public $limit = "";
	public $with = array();
	public $fields = array();

	/**
	 * @var array
	 */
	public $relations = array();
	public $select = array();

	public static function setConnect($host, $user, $password, $base, $charset, $isNewConnection = false)
	{
		if (self::$_isConnect && !$isNewConnection) {
			return true;
		}

		if (!mysql_connect($host, $user, $password)) {
			throw new Exception("Не удалось установить соединение с сервером MySQL");
		}

		if (!mysql_select_db($base)) {
			throw new Exception("Не удалось выбрать базу");
		}

		if (!mysql_query("SET NAMES '{$charset}'") || !mysql_set_charset($charset)) {
			throw new Exception("Не удалось задать кодировку для БД");
		}

		self::$_isConnect = true;
	}

	private function _getQuery()
	{
		$join = array();
		$select = array();

		$select[] = "t.id AS t__id";
		foreach ($this->fields as $field) {
			$select[] = "t.{$field} AS t__{$field}";
		}

		foreach ($this->with as $with) {
			$relation = $this->relations[$with];
			/**
			 * @var Model $class
			 */
			$class = new $relation[0];
			$select[] = $class->tableName() . ".id AS {$with}__id";
			foreach (array_keys($class->rules()) as $field) {
				$select[] = $class->tableName() . ".{$field} AS {$with}__{$field}";
			}

			$this->condition = str_replace("{$with}.", $class->tableName() . ".", $this->condition);
			$join[] =
				" LEFT JOIN " .
				$class->tableName() .
				" ON t." .
				$relation[1] .
				" = " .
				$class->tableName().
				".id";
		}

		$query = "SELECT " . implode(", ", $select);
		$query .= " FROM " . $this->tableName . " AS t";

		if ($this->condition && $this->params) {
			foreach ($this->params as $key => $val) {
				$val = mysql_escape_string(htmlspecialchars(strip_tags(trim($val))));
				$this->condition = str_replace(
					":{$key}",
					"'{$val}'",
					$this->condition
				);
			}
		}

		foreach ($join as $item) {
			$query .= $item;
		}

		if ($this->condition) {
			$query .= " WHERE {$this->condition}";
		}

		if ($this->limit) {
			$query .= " LIMIT {$this->limit}";
		}

		if (App::$isDebug) {
			echo "<script>console.log(\"{$query}\");</script>";
		}

		return $query;
	}

	public function getResult()
	{
		$rows = array();

		$result = mysql_query($this->_getQuery());
		if($result) {
			while ($row = mysql_fetch_assoc($result)) {
				$rows[] = $row;
			}
		}

		return $rows;
	}

	/**
	 * @param string $condition
	 * @param string $operator
	 *
	 * @return bool
	 */
	public function addCondition($condition = "", $operator = "AND")
	{
		if (!$condition) {
			return false;
		}

		if ($this->condition) {
			$this->condition .= " {$operator} {$condition}";
		} else {
			$this->condition = $condition;
		}

		return true;
	}

	public function setSelect()
	{

	}

	public static function selectQuery($query)
	{
		$query = mysql_query($query);
		return mysql_fetch_assoc($query);
	}
}