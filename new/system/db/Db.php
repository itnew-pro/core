<?php

namespace system\db;

use Exception;

class Db
{

	private static $_isConnect = false;

	public $condition = "";
	public $params = array();
	public $limit = "";
	public $model = null;
	public $select = array();

	public static function setConnect($host, $user, $password, $base, $charset)
	{
		if (self::$_isConnect) {
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
		$query = "SELECT * FROM {$this->table}";

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

		if ($this->condition) {
			$query .= " WHERE {$this->condition}";
		}

		if ($this->limit) {
			$query .= " LIMIT {$this->limit}";
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
}