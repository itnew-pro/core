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
 * @property integer             $id                 идентификатор
 * @property integer             $catalog_menu_id    идентификатор меню
 * @property integer             $cover_id           идентификатор обложки
 * @property integer             $images_id          идентификатор изображений
 * @property integer             $seo_id             идентификатор СЕО
 * @property integer             $text_id            идентификатор текста
 * @property integer             $description_id     идентификатор описания
 * @property integer             $brand_id           идентификатор бренда
 * @property integer             $sort               сортировка
 * @property integer             $is_action          есть ли акция
 * @property integer             $old_price          старая цена
 * @property integer             $price              цена
 * @property integer             $color_id           идентификатор цвета
 * @property string              $article            артикул
 * @property string              $date               дата
 *
 * @property CatalogBrand        $brand              бренд
 * @property CatalogMenu         $catalogMenu        меню
 * @property Images              $cover              обложка
 * @property Text                $description        описание
 * @property Images              $images             изображения
 * @property Seo                 $seo                СЕО
 * @property Text                $text               текст
 * @property CatalogItemClone[]  $catalogItemClones  клоны
 * @property CatalogItemField[]  $catalogItemFields  поля описания
 * @property CatalogItemRating[] $catalogItemRatings рейтинги
 * @property CatalogSize[]       $catalogSizes       размены
 */
class CatalogItem extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog_item';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			array(
				'catalog_menu_id, seo_id, sort, is_action, old_price, price, color_id, article, date',
				'required'
			),
			array(
				'catalog_menu_id, cover_id, images_id, seo_id, text_id, description_id, brand_id, sort, is_action,
					old_price, price, color_id',
				'numerical',
				'integerOnly' => true
			),
			array('article', 'length', 'max' => 255),
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
			'brand'              => array(
				self::BELONGS_TO,
				'itnew\models\CatalogBrand',
				'brand_id'
			),
			'catalogMenu'        => array(
				self::BELONGS_TO,
				'itnew\models\CatalogMenu',
				'catalog_menu_id'
			),
			'cover'              => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'cover_id'
			),
			'description'        => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'description_id'
			),
			'images'             => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'images_id'
			),
			'seo'                => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
			),
			'text'               => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'text_id'
			),
			'catalogItemClones'  => array(
				self::HAS_MANY,
				'itnew\models\CatalogItemClone',
				'catalog_item_id'
			),
			'catalogItemFields'  => array(
				self::HAS_MANY,
				'itnew\models\CatalogItemField',
				'catalog_item_id'
			),
			'catalogItemRatings' => array(
				self::HAS_MANY,
				'itnew\models\CatalogItemRating',
				'catalog_item_id'
			),
			'catalogSizes'       => array(
				self::HAS_MANY,
				'itnew\models\CatalogSize',
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
	 * @return CatalogItem
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
