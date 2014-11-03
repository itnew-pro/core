<?php

namespace itnew\models;

use CActiveRecord;

/**
 * Файл класса CatalogMenu.
 *
 * Модель для таблицы "catalog_menu"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer       $id           идентификатор
 * @property integer       $catalog_id   идентификатор каталога
 * @property integer       $parent_id    идентификатор родителя
 * @property integer       $seo_id       идентификатор СЕО
 * @property integer       $sort         сортировка
 *
 * @property CatalogItem[] $catalogItems элементы каталога
 * @property Catalog       $catalog      каталог
 * @property Seo           $seo          СЕО
 */
class CatalogMenu extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_menu';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('catalog_id, parent_id, seo_id, sort', 'required'),
			array('catalog_id, parent_id, seo_id, sort', 'numerical', 'integerOnly' => true),
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
			'catalogItems' => array(
				self::HAS_MANY,
				'itnew\models\CatalogItem',
				'catalog_menu_id'
			),
			'catalog'      => array(
				self::BELONGS_TO,
				'itnew\models\Catalog',
				'catalog_id'
			),
			'seo'          => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
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
	 * @return CatalogMenu
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
