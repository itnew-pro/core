<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogBrand.
 *
 * Модель для таблицы "catalog_brand"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer       $id           идентификатор
 * @property integer       $catalog_id   идентификатор каталога
 * @property integer       $seo_id       идентификатор СЕО
 *
 * @property Seo           $seo          СЕО
 * @property Catalog       $catalog      Каталог
 * @property CatalogItem[] $catalogItems Элементы каталога
 */
class CatalogBrand extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_brand';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_id, seo_id', 'required'),
			array('catalog_id, seo_id', 'numerical', 'integerOnly' => true),
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
			'seo'          => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
			),
			'catalog'      => array(
				self::BELONGS_TO,
				'itnew\models\Catalog',
				'catalog_id'
			),
			'catalogItems' => array(
				self::HAS_MANY,
				'itnew\models\CatalogItem',
				'brand_id'
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
	 * @return CatalogBrand
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
