<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogItemField.
 *
 * Модель для таблицы "catalog_item_field"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer      $id               идентификатор
 * @property integer      $catalog_item_id  идентификатор элемента каталога
 * @property integer      $catalog_field_id идентификатор поля описания
 * @property string       $value            значение
 *
 * @property CatalogField $catalogField     поле описания
 * @property CatalogItem  $catalogItem      элемент каталога
 */
class CatalogItemField extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_item_field';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_item_id, catalog_field_id, value', 'required'),
			array('catalog_item_id, catalog_field_id', 'numerical', 'integerOnly' => true),
			array('value', 'length', 'max' => 255),
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
			'catalogField' => array(
				self::BELONGS_TO,
				'itnew\models\CatalogField',
				'catalog_field_id'
			),
			'catalogItem'  => array(
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
	 * @return CatalogItemField
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
