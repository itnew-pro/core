<?php

namespace models;

use system\base\Model;

/**
 * Файл класса GridModel
 *
 * @package models
 */
class GridModel extends Model
{

	/**
	 * @var BlockModel
	 */
	public $blockModel = null;

	/**
	 * Получает название связной таблицы
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "grids";
	}

	public function relations()
	{
		return array(
			"blockModel" => array("models\BlockModel", "block_id")
		);
	}

	/**
	 * Правила валидации
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			"section_id" => array(),
			"block_id"   => array(),
			"line"       => array(),
			"left"       => array(),
			"top"        => array(),
			"width"      => array(),
		);
	}

	/**
	 * Получает объект модели
	 *
	 * @param string $className
	 *
	 * @return GridModel
	 */
	public static function model($className = __CLASS__)
	{
		return new $className;
	}

	public function bySectionId($sectionId = null)
	{
		if ($sectionId) {
			$this->db->addCondition("t.section_id = :section_id");
			$this->db->params["section_id"] = $sectionId;
		}

		return $this;
	}

	public function withContent()
	{
		$this->db->with[] = "blockModel";

		return $this;
	}
}