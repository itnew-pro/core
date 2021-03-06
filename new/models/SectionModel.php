<?php

namespace models;

use system\App;
use system\base\Model;

/**
 * Файл класса SectionModel
 *
 * @package models
 */
class SectionModel extends Model
{

	public $seo_id = 0;
	public $language = 0;
	public $width = 0;
	public $is_main = 0;

	/**
	 * @var SeoModel
	 */
	public $seoModel = null;

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
			"seoModel" => array("models\SeoModel", "seo_id")
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
			"seo_id"   => array(),
			"language" => array(),
			"width"    => array(),
			"is_main"  => array(),
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
		$this->db->with[] = "seoModel";
		$this->db->addCondition("t.language = :language");
		$this->db->params["language"] = App::$languageId;

		if ($url) {
			$this->db->addCondition("seoModel.url = :url");
			$this->db->params["url"] = $url;
		} else {
			$this->db->addCondition("t.is_main = 1");
		}

		return $this;
	}
}