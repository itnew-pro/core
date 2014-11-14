<?php

namespace system\db;

class Migration
{

	private $_columnTypes = array(
		'pk'      => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
		'string'  => 'varchar(255) NOT NULL',
		'integer' => 'int(11) NOT NULL',
		'boolean' => 'tinyint(1) NOT NULL',
		'text' => 'text NOT NULL',
	);

	public function createTable($table, $columns, $options = null)
	{
		$cols = array();
		foreach ($columns as $name => $type) {
			$cols[] = "`{$name}`" . ' ' . $this->getColumnType($type);
		}
		$query = "\nCREATE TABLE " . $table . " (\n" . implode(",\n", $cols) . "\n)" . $options;
		$this->execute($query);
	}

	public function addForeignKey($name, $table, $column, $refTable, $refColumn, $delete = null, $update = null)
	{
		$query = 'ALTER TABLE ' . $table
			. ' ADD CONSTRAINT ' . $name
			. ' FOREIGN KEY (' . $column . ')'
			. ' REFERENCES ' . $refTable
			. ' (' . $refColumn . ')';
		if ($delete !== null) {
			$query .= ' ON DELETE ' . $delete;
		}
		if ($update !== null) {
			$query .= ' ON UPDATE ' . $update;
		}
		$this->execute($query);
	}

	public function createIndex($name, $table, $column, $unique = false)
	{
		$query = ($unique ? 'CREATE UNIQUE INDEX ' : 'CREATE INDEX ')
			. $name . ' ON '
			. $table . ' (' . $column . ')';
		$this->execute($query);
	}

	public function getColumnType($type)
	{
		if (array_key_exists($type, $this->_columnTypes)) {
			return $this->_columnTypes[$type];
		}

		return $type;
	}

	public function execute($query)
	{
		mysql_query($query);
	}
}