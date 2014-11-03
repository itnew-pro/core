<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogSize.
 *
 * Модель для таблицы "catalog_size"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer     $id              идентификатор
 * @property integer     $catalog_item_id идентификатор элемента каталога
 * @property double      $value           значение
 *
 * @property CatalogItem $catalogItem     элемент каталога
 */
class CatalogSize extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_size';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_item_id, value', 'required'),
			array('catalog_item_id', 'numerical', 'integerOnly' => true),
			array('value', 'numerical'),
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
	 * @return CatalogSize
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
