<?php

namespace models;

use system\base\Model;

/**
 * Файл класса SectionModel
 *
 * @package models
 */
class SectionModel extends Model
{

	/**
	 * @var SeoModel
	 */
	public $seo = null;

	/**
	 * Получает название связной таблицы
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "sections";
	}

	public function relations()
	{
		return array(
			"seo" => array("models\SeoModel", "seo_id")
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
			"seo_id"       => array(),
			"language_id"  => array(),
			"structure_id" => array(),
			"is_main"      => array(),
		);
	}

	/**
	 * Получает объект модели
	 *
	 * @param string $className
	 *
	 * @return SectionModel
	 */
	public static function model($className = __CLASS__)
	{
		return new $className;
	}

	public function byUrl($url = "")
	{
		if ($url) {
			$this->db->with[] = "seo";
			$this->db->addCondition("seo.url = :url");
			$this->db->params["url"] = $url;
		} else {
			$this->db->addCondition($this->tableName() . ".is_main = 1");
		}

		return $this;
	}
}