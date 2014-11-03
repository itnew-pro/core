<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogItemClone.
 *
 * Модель для таблицы "catalog_item_clone"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer $id идентификатор
 * @property integer $catalog_item_id идентификатор элемента каталога
 * @property integer $cover_id идентификатор обложки
 * @property integer $images_id идентификатор изображений
 * @property integer $color_id идентификатор цвета
 * @property string $article артикул
 *
 * @property CatalogItem $catalogItem элемент каталога
 * @property Images $cover обложка
 * @property Images $images изображения
 */
class CatalogItemClone extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_item_clone';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_item_id, color_id, article', 'required'),
			array('catalog_item_id, cover_id, images_id, color_id', 'numerical', 'integerOnly'=>true),
			array('article', 'length', 'max'=>255),
		);
	}

	/**
	 * Возвращает связи между объектами
	 *
	 * @return array
	 */
	public function relations()
	{
		return array(
			'catalogItem' => array(
				self::BELONGS_TO,
				'itnew\models\CatalogItem',
				'catalog_item_id'
			),
			'cover' => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'cover_id'
			),
			'images' => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'images_id'
			),
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array();
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return CatalogItemClone
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
