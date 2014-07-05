<?php

namespace itnew\models;

use itnew\models\Structure;
use itnew\models\Grid;
use CActiveRecord;
use Yii;

/**
 * Файл класса Block.
 *
 * Модель для таблицы "block"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int    $id          идентификатор
 * @property int    $type        тип
 * @property string $name        название
 * @property int    $content_id  идентификатор контента
 * @property int    $language_id идентификатор языка
 *
 * @property Grid[] $grid        модели ячеек сетки
 */
class Block extends CActiveRecord
{

	/**
	 * Тип текст
	 *
	 * @var int
	 */
	const TYPE_TEXT = 1;

	/**
	 * Тип изображения
	 *
	 * @var int
	 */
	const TYPE_IMAGE = 2;

	/**
	 * Тип меню
	 *
	 * @var int
	 */
	const TYPE_MENU = 3;

	/**
	 * Тип сотрудники
	 *
	 * @var int
	 */
	const TYPE_STAFF = 4;

	/**
	 * Тип записи
	 *
	 * @var int
	 */
	const TYPE_RECORDS = 5;

	/**
	 * Типы
	 *
	 * @var string[]
	 */
	public $types = array(
		self::TYPE_TEXT    => "text",
		self::TYPE_IMAGE   => "images",
		self::TYPE_MENU    => "menu",
		self::TYPE_STAFF   => "staff",
		self::TYPE_RECORDS => "records",
	);

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'block';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('type, name, content_id, language_id', 'required'),
			array('type, content_id, language_id', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 255),
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
			'grid' => array(
				self::HAS_MANY,
				'itnew\models\Grid',
				'block_id'
			),
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
			'name' => Yii::t("block", "Name"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Block
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает модель контента
	 *
	 * @return CActiveRecord
	 */
	public function getContentModel()
	{
		$modelName = "itnew\\models\\" . ucfirst($this->getType());
		$model = new $modelName;

		return $model->findByPk($this->content_id);
	}

	/**
	 * Получает тип
	 *
	 * @return string
	 */
	public function getType()
	{
		if (empty($this->types[$this->type])) {
			return null;
		}

		return $this->types[$this->type];
	}

	/**
	 * Получает все блоки
	 *
	 * @param string[] $pageArray идентификаторы блоков на данной странице
	 *
	 * @return string[]
	 */
	public function getAllContentBlocks($pageArray = array())
	{
		$blocksArray = array();

		foreach ($this->types as $key => $type) {
			if (($pageArray && in_array($key, $pageArray)) || !$pageArray) {
				$model = "itnew\\models\\" . ucfirst($type);
				$model = $model::model();
				$blocksArray[$model->getTitle()] = $type;
			}
		}

		return $blocksArray;
	}

	/**
	 * Получает блоки на данной странице
	 *
	 * @return string[]
	 */
	public function getThisPageBlocks()
	{
		return $this->getAllContentBlocks($this->getAllThisPageBlocksTypes());
	}

	/**
	 * Получает все блоки на данной странице
	 *
	 * @return Block[]
	 */
	private function _getAllThisPageBlocks()
	{
		$blocks = array();

		if (!Yii::app()->session["structureId"]) {
			return $blocks;
		}

		$structure = Structure::model()->findByPk(Yii::app()->session["structureId"]);
		if (!$structure) {
			return $blocks;
		}

		$grids = $structure->grid;
		if (!$grids) {
			return $blocks;
		}

		foreach ($grids as $grid) {
			$blocks[] = $grid->block;
		}

		return $blocks;
	}

	/**
	 * Получает все типы блоков на данной странице
	 *
	 * @return string[]
	 */
	public function getAllThisPageBlocksTypes()
	{
		$blocksTypes = array(0);

		$blocks = $this->_getAllThisPageBlocks();
		if (!$blocks) {
			return $blocksTypes;
		}

		foreach ($blocks as $block) {
			$blocksTypes[] = $block->type;
		}

		return array_unique($blocksTypes);
	}

	/**
	 * Получает все идентификаторы блоков на данной странице
	 *
	 * @return int[]
	 */
	public function getAllThisPageBlocksIds()
	{
		$blocksIds = array(0);

		$blocks = $this->_getAllThisPageBlocks();
		if (!$blocks) {
			return $blocksIds;
		}

		foreach ($blocks as $block) {
			$blocksIds[] = $block->id;
		}

		$blocksIds = array_unique($blocksIds);
		sort($blocksIds);

		return $blocksIds;
	}
}