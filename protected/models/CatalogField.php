<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogField.
 *
 * Модель для таблицы "catalog_field"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer            $id                идентификатор
 * @property integer            $catalog_id        идентификатор каталога
 * @property string             $value             значение
 *
 * @property Catalog            $catalog           Каталог
 * @property CatalogItemField[] $catalogItemFields Значения полей элемента каталога
 */
class CatalogField extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_field';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_id, value', 'required'),
			array('catalog_id', 'numerical', 'integerOnly' => true),
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
			'catalog'           => array(
				self::BELONGS_TO,
				'itnew\models\Catalog',
				'catalog_id'
			),
			'catalogItemFields' => array(
				self::HAS_MANY,
				'itnew\models\CatalogItemField',
				'catalog_field_id'
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
	 * @return CatalogField
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
