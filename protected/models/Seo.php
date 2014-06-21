<?php

namespace itnew\models;

use itnew\models\Section;
use CActiveRecord;
use Yii;

/**
 * Файл класса Seo.
 *
 * Модель для таблицы "seo"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int       $id          идентификатор
 * @property string    $name        название
 * @property string    $url         URL адрес
 * @property string    $title       заголовок
 * @property string    $keywords    ключевые слова
 * @property string    $description описание
 *
 * @property Section[] $sections    модели разделов
 */
class Seo extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "seo";
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array("name, url", "required"),
			array("name, url, title, keywords, description", "length", "max" => 512),
		);
	}

	/**
	 * Возвращает связи между объектами
	 *
	 * @return string[]
	 */
	public function relations()
	{
		return array(
			"sections" => array(
				self::HAS_MANY,
				'itnew\models\Section',
				"seo_id"
			),
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return string[]
	 */
	public function attributeLabels()
	{
		return array(
			"name"        => Yii::t("seo", "Name"),
			"url"         => Yii::t("seo", "Page url"),
			"title"       => Yii::t("seo", "Title"),
			"keywords"    => Yii::t("seo", "Keywords"),
			"description" => Yii::t("seo", "Description"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Seo
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает CSS-класс ошибки на пустоту
	 *
	 * @param string[] $post параметры модели переданные через POST
	 *
	 * @return string
	 */
	public static function getEmptyClass($post)
	{
		if (!$post["name"]) {
			return "name-empty";
		}

		if (!$post["url"]) {
			return "url-empty";
		}

		return null;
	}
}
