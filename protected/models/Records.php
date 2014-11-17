<?php

namespace itnew\models;

use itnew\models\Structure;
use itnew\models\Images;
use itnew\models\RecordsContent;
use CActiveRecord;
use Yii;
use CDbCriteria;

/**
 * Файл класса Records.
 *
 * Модель для таблицы "records"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int              $id             идентификатор
 * @property int              $date           дата
 * @property int              $is_detail      подробно
 * @property int              $cover          обложка
 * @property int              $images         изображения
 * @property int              $structure_id   идентификатор структуры
 *
 * @property Structure        $structure      модель структуры
 * @property Images           $coverRelation  модель изображения
 * @property Images           $imagesRelation модель изображения
 * @property RecordsContent[] $recordsContent модели изображений
 * @property Block            $block          модель блока
 */
class Records extends CActiveRecord
{

	/**
	 * Идентификаторы записей
	 *
	 * @var string
	 */
	public $contentIds = "";

	/**
	 * Ширина обложки
	 *
	 * @var int
	 */
	const COVER_WIDTH = 120;

	/**
	 * Высота обложки
	 *
	 * @var int
	 */
	const COVER_HEIGHT = 120;

	/**
	 * Ширина изображений
	 *
	 * @var int
	 */
	const IMAGES_WIDTH = 1000;

	/**
	 * Высота изображений
	 *
	 * @var int
	 */
	const IMAGES_HEIGHT = 1000;

	/**
	 * Ширина миниатюрок
	 *
	 * @var int
	 */
	const IMAGES_THUMB_WIDTH = 120;

	/**
	 * Высота миниатюрок
	 *
	 * @var int
	 */
	const IMAGES_THUMB_HEIGHT = 120;

	/**
	 * Тип. Отсутствие даты
	 *
	 * @var int
	 */
	const DATE_UNDATE = 0;

	/**
	 * Тип. dd.mm.yyyy
	 *
	 * @var int
	 */
	const DATE_DDMMYYYY = 1;

	/**
	 * Наличие обложки
	 *
	 * @var bool
	 */
	public $isCover = true;

	/**
	 * Наличие изображений
	 *
	 * @var bool
	 */
	public $isImages = true;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'records';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('date, is_detail, cover, images, structure_id', 'numerical', 'integerOnly' => true),
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
			'structure'      => array(
				self::BELONGS_TO,
				'itnew\models\Structure',
				'structure_id'
			),
			'coverRelation'  => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'cover'
			),
			'imagesRelation' => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'images'
			),
			'recordsContent' => array(
				self::HAS_MANY,
				'itnew\models\RecordsContent',
				'records_id',
				"order" => "sort DESC"
			),
			"block"          => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_RECORDS,
				),
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
				"blockType" => Block::TYPE_RECORDS,
			)
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
			'date'      => Yii::t("records", "Date"),
			'is_detail' => Yii::t("records", "Detailed description"),
			'isCover'   => Yii::t("records", "Cover"),
			'isImages'  => Yii::t("records", "Images"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Records
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
		return Yii::t("records", "Records");
	}

	/**
	 * Список типов дат
	 *
	 * @return string[]
	 */
	public function getDateTypes()
	{
		return array(
			self::DATE_UNDATE   => Yii::t("records", "undated"),
			self::DATE_DDMMYYYY => Yii::t("records", "dd.mm.yyyy"),
		);
	}

	/**
	 * Получает модель обложки
	 *
	 * @return Images
	 */
	public function getCover()
	{
		if ($this->coverRelation) {
			return $this->coverRelation;
		}

		$model = new Images;
		$model->width = self::COVER_WIDTH;
		$model->height = self::COVER_HEIGHT;

		return $model;
	}

	/**
	 * Получает клона модели обложки
	 *
	 * @return Images
	 */
	public function getCoverClone()
	{
		$model = clone $this->getCover();
		$model->id = null;
		$model->isNewRecord = true;

		return $model;
	}

	/**
	 * Получает модель изображений
	 *
	 * @return Images
	 */
	public function getImages()
	{
		if ($this->imagesRelation) {
			return $this->imagesRelation;
		}

		$model = new Images;
		$model->view = Images::TYPE_LITEBOX;
		$model->width = self::IMAGES_WIDTH;
		$model->height = self::IMAGES_HEIGHT;
		$model->thumb_width = self::IMAGES_THUMB_WIDTH;
		$model->thumb_height = self::IMAGES_THUMB_HEIGHT;

		return $model;
	}

	/**
	 * Получает клона модели изображений
	 *
	 * @return Images
	 */
	public function getImagesClone()
	{
		$model = clone $this->getImages();
		$model->id = null;
		$model->isNewRecord = true;

		return $model;
	}

	/**
	 * Инициализация модели
	 *
	 * @return void
	 */
	public function init()
	{
		if ($this->isNewRecord) {
			$this->date = self::DATE_DDMMYYYY;
			$this->is_detail = true;
		}
	}

	/**
	 * Проверяет наличие обложки
	 *
	 * @return bool
	 */
	public function hasCover()
	{
		return $this->isNewRecord || $this->cover;
	}

	/**
	 * Проверяет наличие изображений
	 *
	 * @return bool
	 */
	public function hasImages()
	{
		return $this->isNewRecord || $this->images;
	}

	/**
	 * Сохраняет контент
	 *
	 * @param string $post поля модели переданные через POST
	 *
	 * @return bool
	 */
	public function saveContent($post = array())
	{
		if (empty($post["contentIds"])) {
			return false;
		}

		$contentIds = explode(",", $post["contentIds"]);
		$sort = count($contentIds) * RecordsContent::SORT_STEP;

		foreach ($contentIds as $pk) {
			if ($pk) {
				$model = RecordsContent::model()->findByPk($pk);
				if ($model) {
					$model->sort = $sort;
					$model->save();
					$sort -= RecordsContent::SORT_STEP;
				}
			}
		}

		return true;
	}

	/**
	 * Получает ширину обложки
	 *
	 * @return int
	 */
	public function getCoverWidth()
	{
		if ($this->coverRelation && $this->coverRelation->width) {
			return $this->coverRelation->width + RecordsContent::COVER_MARGIN;
		}

		return self::COVER_WIDTH + RecordsContent::COVER_MARGIN;
	}

	/**
	 * Получает URL на записи
	 *
	 * @return string
	 */
	public function getUrl()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.block_id = :block_id";
		$criteria->params = array(":block_id" => $this->block->id);
		$grid = Grid::model()->find($criteria);

		if (!$grid) {
			return null;
		}

		$criteria = new CDbCriteria;
		$criteria->condition = "t.structure_id = :structure_id";
		$criteria->params = array(":structure_id" => $grid->structure_id);
		$section = Section::model()->find($criteria);

		if (!$section) {
			return null;
		}

		return $section->getUrl();
	}

	/**
	 * Выполняется после сохранения модели
	 *
	 * @return void
	 */
	protected function afterSave()
	{
		parent::afterSave();

		if (!$this->isCover && $this->coverRelation) {
			$this->coverRelation->delete();
		}
		if (!$this->isImages && $this->imagesRelation) {
			$this->imagesRelation->delete();
		}
	}
}