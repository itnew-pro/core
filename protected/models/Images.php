<?php

namespace itnew\models;

use itnew\models\ImagesContent;
use itnew\models\Block;
use itnew\models\Language;
use CActiveRecord;
use Yii;
use CDbCriteria;

/**
 * Файл класса Images.
 *
 * Модель для таблицы "images"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int             $id            идентификатор
 * @property int             $many          флаг множества изображений в группе
 * @property int             $view          тип отображения
 * @property int             $width         ширина
 * @property int             $height        высота
 * @property int             $thumb_width   ширина миниатюрки
 * @property int             $thumb_height  высота миниатюрки
 *
 * @property ImagesContent[] $imagesContent модели изображений группы
 * @property Block           $block         модель блока
 */
class Images extends CActiveRecord
{

	/**
	 * Идентификаторы изображений
	 *
	 * @var string
	 */
	public $imageContentIds = "";

	/**
	 * Тип отображения. Простое изображение
	 *
	 * @var int
	 */
	const TYPE_SIMPLE = 0;

	/**
	 * Тип отображения. Увеличение
	 *
	 * @var int
	 */
	const TYPE_LITEBOX = 1;

	/**
	 * Тип отображения. Слайдер
	 *
	 * @var int
	 */
	const TYPE_SLIDER = 2;

	/**
	 * Типы отображений
	 *
	 * @var string[]
	 */
	private $_views = array(
		self::TYPE_SIMPLE  => "simple",
		self::TYPE_LITEBOX => "litebox",
		self::TYPE_SLIDER  => "slider",
	);

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'images';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('many, view, width, height, thumb_width, thumb_height', 'numerical', 'integerOnly' => true),
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
			'imagesContent' => array(
				self::HAS_MANY,
				'itnew\models\ImagesContent',
				'images_id',
				"order" => "sort"
			),
			"block"         => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_IMAGE,
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
				"blockType" => Block::TYPE_IMAGE,
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
			'view'         => Yii::t("images", "View"),
			'width'        => Yii::t("images", "Max width"),
			'height'       => Yii::t("images", "Max height"),
			'thumb_width'  => Yii::t("images", "Thumbnail width"),
			'thumb_height' => Yii::t("images", "Thumbnail height"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Images
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает заголовок
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Yii::t("images", "Images");
	}

	/**
	 * Список типов отображения
	 *
	 * @return string[]
	 */
	public function getViewList()
	{
		return array(
			self::TYPE_SIMPLE  => Yii::t("images", "Simple image"),
			self::TYPE_LITEBOX => Yii::t("images", "Increasing thumbnail"),
			self::TYPE_SLIDER  => Yii::t("images", "Slider"),
		);
	}

	/**
	 * Вызывается перед удалением модели
	 *
	 * @return bool
	 */
	public function beforeDelete()
	{
		foreach ($this->imagesContent as $model) {
			$model->delete();
		}

		return parent::beforeDelete();
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

	/**
	 * Получает название шаблона
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		if (empty($this->_views[$this->view])) {
			return "_" . $this->_views[0];
		}

		return "_" . $this->_views[$this->view];
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
		if (empty($post["imageContentIds"])) {
			return true;
		}

		$sort = 10;
		foreach (explode(",", $post["imageContentIds"]) as $pk) {
			if ($pk) {
				$model = ImagesContent::model()->findByPk($pk);
				if ($model) {
					$model->sort = $sort;
					$model->save();
					$sort = $sort + 10;
				}
			}
		}

		return true;
	}
}