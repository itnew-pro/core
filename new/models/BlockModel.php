<?php

namespace models;

use system\base\Model;

/**
 * Файл класса BlockModel
 *
 * @package models
 */
class BlockModel extends Model
{

	/**
	 * Получает название связной таблицы
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "blocks";
	}

	public function relations()
	{
		return array();
	}

	/**
	 * Правила валидации
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			"language" => array(),
			"name"     => array(),
			"type"     => array(),
			"content"  => array(),
		);
	}

	/**
	 * Получает объект модели
	 *
	 * @param string $className
	 *
	 * @return BlockModel
	 */
	public static function model($className = __CLASS__)
	{
		return new $className;
	}
}