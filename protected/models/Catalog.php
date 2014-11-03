<?php

namespace itnew\models;

use CActiveRecord;
use Yii;

/**
 * Файл класса Catalog.
 *
 * Модель для таблицы "catalog"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer        $id                    идентификатор
 * @property integer        $price_type            тип цены
 * @property integer        $size_type             тип рамеров
 * @property integer        $new_type              тип новинки
 * @property integer        $discount_type         тип скидки
 * @property integer        $date_type             тип даты
 * @property integer        $is_rating             тип рейтинга
 * @property integer        $is_article            есть ли артикул
 * @property integer        $is_color              есть ли цвет
 * @property integer        $is_brand              есть ли бренд
 * @property integer        $is_cover              есть ли обложка
 * @property integer        $is_images             есть ли изображения
 * @property integer        $is_description        есть ли описание
 * @property integer        $is_text               есть ли текст
 * @property integer        $price_in_short_card   выводить ли цену в краткой анкете
 * @property integer        $size_in_short_card    выводить ли размеры в краткой анкете
 * @property integer        $date_in_short_card    выводить ли дату в краткой анкете
 * @property integer        $article_in_short_card выводить ли артикль в краткой анкете
 * @property integer        $color_in_short_card   выводить ли цвета в краткой анкете
 * @property integer        $brand_in_short_card   выводить ли бренд в краткой анкете
 * @property integer        $rating_in_short_card  выводить ли рейтинг в краткой анкете
 *
 * @property CatalogBrand[] $catalogBrands         бренды
 * @property CatalogField[] $catalogFields         дополнительные поля
 * @property CatalogMenu[]  $catalogMenus          меню
 */
class Catalog extends CActiveRecord
{

	/**
	 * Тип цены. Рубль.
	 *
	 * @var integer
	 */
	const PRICE_TYPE_RUB = 1;

	/**
	 * Тип цены. Доллар.
	 *
	 * @var integer
	 */
	const PRICE_TYPE_USD = 2;

	/**
	 * Тип цены. Евро.
	 *
	 * @var integer
	 */
	const PRICE_TYPE_EURO = 3;

	/**
	 * Тип размеров. Одежда.
	 *
	 * @var integer
	 */
	const SIZE_TYPE_CLOTHES = 1;

	/**
	 * Тип размеров. Обувь.
	 *
	 * @var integer
	 */
	const SIZE_TYPE_SHOES = 2;

	/**
	 * Красный
	 *
	 * @var integer
	 */
	const COLOR_RED = 1;

	/**
	 * Зеленый
	 *
	 * @var integer
	 */
	const COLOR_GREEN = 2;

	/**
	 * Синий
	 *
	 * @var integer
	 */
	const COLOR_BLUE = 3;

	/**
	 * Оранжевый
	 *
	 * @var integer
	 */
	const COLOR_ORANGE = 4;

	/**
	 * Желтый
	 *
	 * @var integer
	 */
	const COLOR_YELLOW = 5;

	/**
	 * Розовый
	 *
	 * @var integer
	 */
	const COLOR_PINK = 6;

	/**
	 * Дата. dd.mm.yyyy
	 *
	 * @var int
	 */
	const DATE_DDMMYYYY = 1;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'catalog';
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
				'price_type, size_type, new_type, discount_type, date_type, is_rating, is_article, is_color, is_brand,
					is_cover, is_images, is_description, is_text, price_in_short_card, size_in_short_card,
					date_in_short_card, article_in_short_card, color_in_short_card, brand_in_short_card,
					rating_in_short_card',
				'numerical',
				'integerOnly' => true
			),
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
			"block"         => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_CATALOG,
				),
			),
			'catalogBrands' => array(
				self::HAS_MANY,
				'itnew\models\CatalogBrand',
				'catalog_id'
			),
			'catalogFields' => array(
				self::HAS_MANY,
				'itnew\models\CatalogField',
				'catalog_id'
			),
			'catalogMenus'  => array(
				self::HAS_MANY,
				'itnew\models\CatalogMenu',
				'catalog_id'
			),
		);
	}

	/**
	 * Возвращает список поведений модели
	 *
	 * @return string[]
	 */
	public function behaviors()
	{
		return array(
			"ContentBehavior" => array(
				"class"     => "itnew\behaviors\ContentBehavior",
				"blockType" => Block::TYPE_CATALOG,
			)
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			"price_type" => Yii::t("catalog", "Price"),
			"size_type" => Yii::t("catalog", "Sizes"),
			"new_type" => Yii::t("catalog", "New labels"),
			"discount_type" => Yii::t("catalog", "Discount labels"),
			"date_type" => Yii::t("catalog", "Date"),
			"is_rating" => Yii::t("catalog", "Rating"),
			"is_article" => Yii::t("catalog", "Article"),
			"is_color" => Yii::t("catalog", "Colors"),
			"is_brand" => Yii::t("catalog", "Brands"),
			"is_cover" => Yii::t("catalog", "Cover"),
			"is_images" => Yii::t("catalog", "Images"),
			"is_description" => Yii::t("catalog", "Brief description"),
			"is_text" => Yii::t("catalog", "Text"),
			"price_in_short_card" => Yii::t("catalog", "Output in short card"),
			"size_in_short_card" => Yii::t("catalog", "Output in short card"),
			"date_in_short_card" => Yii::t("catalog", "Output in short card"),
			"article_in_short_card" => Yii::t("catalog", "Output in short card"),
			"color_in_short_card" => Yii::t("catalog", "Output in short card"),
			"brand_in_short_card" => Yii::t("catalog", "Output in short card"),
			"rating_in_short_card" => Yii::t("catalog", "Output in short card"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Catalog
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает название
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Yii::t("catalog", "Catalog");
	}

	/**
	 * Типы прайсов
	 *
	 * @return array
	 */
	public function getPriceTypeList()
	{
		return array(
			self::PRICE_TYPE_RUB  => Yii::t("catalog", "ruble"),
			self::PRICE_TYPE_USD  => Yii::t("catalog", "dollar"),
			self::PRICE_TYPE_EURO => Yii::t("catalog", "евро")
		);
	}

	/**
	 * Получает типы размеров
	 *
	 * @return array
	 */
	public function getSizeTypeList()
	{
		return array(
			self::SIZE_TYPE_CLOTHES  => Yii::t("catalog", "for clothing"),
			self::SIZE_TYPE_SHOES  => Yii::t("catalog", "for shoes"),
		);
	}

	/**
	 * Получает список цветов
	 *
	 * @return array
	 */
	public function getColorList()
	{
		return array(
			self::COLOR_RED => Yii::t("catalog", "red"),
			self::COLOR_GREEN => Yii::t("catalog", "green"),
			self::COLOR_BLUE => Yii::t("catalog", "blue"),
			self::COLOR_ORANGE => Yii::t("catalog", "orange"),
			self::COLOR_YELLOW => Yii::t("catalog", "yellow"),
			self::COLOR_PINK => Yii::t("catalog", "pink"),
		);
	}

	/**
	 * Получает типы дат
	 *
	 * @return array
	 */
	public function getDateTypeList()
	{
		return array(
			self::DATE_DDMMYYYY => Yii::t("catalog", "dd.mm.yyyy"),
		);
	}

	/**
	 * Вызывается после удаления модели
	 *
	 * @return void
	 */
	protected function afterDelete()
	{
		parent::afterDelete();

		if ($this->block) {
			$this->block->delete();
		}
	}
}
