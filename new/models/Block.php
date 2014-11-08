<?php

namespace models;

use components\Model;

/**
 * Файл класса Block
 *
 * @package models
 */
class Block extends Model
{

	/**
	 * Название таблицы
	 *
	 * @var string
	 */
	protected $tableName = "blocks";

	/**
	 * Получает объект модели
	 *
	 * @param string $className
	 *
	 * @return Admin
	 */
	public static function model($className = __CLASS__)
	{
		return new $className;
	}
}