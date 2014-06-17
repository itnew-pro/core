<?php

namespace itnew\models;

use itnew\models\MenuContent;
use itnew\models\Block;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * Файл класса Menu.
 *
 * Модель для таблицы "menu"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int           $id          идентификатор
 * @property int           $type        тип
 *
 * @property MenuContent[] $menuContent модели элементов меню
 */
class Menu extends CActiveRecord
{

	/**
	 * Тип. вертикальное меню
	 *
	 * @var int
	 */
	const TYPE_VERTICAL = 0;

	/**
	 * Тип. горизонтальное меню
	 *
	 * @var int
	 */
	const TYPE_HORIZONTAL = 1;

	/**
	 * Список типов
	 *
	 * @var string
	 */
	private $_typeList = array(
		self::TYPE_VERTICAL   => "vertical",
		self::TYPE_HORIZONTAL => "horizontal",
	);

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('type', 'required'),
			array('type', 'numerical', 'integerOnly' => true),
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
			'menuContent' => array(
				self::HAS_MANY,
				'itnew\models\MenuContent',
				'menu_id',
				"order" => "sort"
			),
			"block"       => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_MENU,
				),
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
		return array();
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Menu
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
		return Yii::t("menu", "Menu");
	}

	/**
	 * Получает все блоки
	 *
	 * @return Block[]
	 */
	public function getAllContentBlocks()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.language_id = :language_id AND type = :type";
		$criteria->params["language_id"] = Language::getActiveId();
		$criteria->params["type"] = Block::TYPE_MENU;

		return Block::model()->findAll();
	}

	/**
	 * Получает блок
	 *
	 * @return Block
	 */
	public function getBlock()
	{
		if ($this->block) {
			return $this->block;
		}
		return new Block;
	}

	/**
	 * Получает тип
	 *
	 * @return string
	 */
	public function getType()
	{
		if (!empty($this->_typeList[$this->type])) {
			return null;
		}

		return $this->_typeList[$this->type];
	}

	/**
	 * Названия типов
	 *
	 * @return string[]
	 */
	public function getTypeListLabels()
	{
		$list = array();

		$list[self::TYPE_VERTICAL] = Yii::t("menu", "Vertical");
		$list[self::TYPE_HORIZONTAL] = Yii::t("menu", "Horizontal");

		return $list;
	}

	/**
	 * Обновляет настройки модели
	 *
	 * @param int $id идентификатор
	 *
	 * @return Menu|null
	 */
	public function updateSettings($id, $blockPost, $modelPost)
	{
		$model = $this->findByPk($id);
		if (!$model) {
			return null;
		}

		$block = $model->getBlock();
		if (!$block) {
			return null;
		}

		$model->attributes = $modelPost;
		$block->attributes = $blockPost;

		$transaction = Yii::app()->db->beginTransaction();
		if ($block->save()) {
			if ($model->save()) {
				$transaction->commit();
				return $model;
			}
		}
		$transaction->rollback();

		return null;
	}

	/**
	 * Добавляет настройки
	 *
	 * @param string[] $blockPost данные POST для блока
	 * @param string[] $modelPost данные POST для модели
	 *
	 * @return Menu|null
	 */
	public function addSettings($blockPost, $modelPost)
	{
		$model = new self;
		$block = new Block;
		$model->attributes = $modelPost;
		$block->attributes = $blockPost;

		$transaction = Yii::app()->db->beginTransaction();

		if ($model->save()) {
			$block->content_id = $model->id;
			$block->type = Block::TYPE_MENU;
			$block->language_id = Language::getActiveId();

			if ($block->save()) {
				$transaction->commit();
				return $model;
			}
		}
		$transaction->rollback();

		return null;
	}

	/**
	 * Вызывается перед удалением модели
	 *
	 * @return bool
	 */
	protected function beforeDelete()
	{
		if ($this->menuContent) {
			foreach ($this->menuContent as $model) {
				$model->delete();
			}
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
		if ($this->block) {
			$this->block->delete();
		}

		return parent::afterDelete();
	}

	public function getUnusedSections()
	{
		$list = array();
		$sections = Section::model()->findAll();
		if ($sections) {
			foreach ($sections as $model) {
				$list[$model->id] = $model->seo->name;
			}
		}

		return $list;
	}

	public function getHtml()
	{
		$list = array();

		if ($this->menuContent) {
			foreach ($this->menuContent as $menu) {
				$link = null;

				if ($menu->section_id) {
					$section = Section::model()->findByPk($menu->section_id);
					if ($section) {
						$link = $section->getLink();
					}
				}

				if ($link) {
					$list[$menu->parent_id][] = array(
						"id"   => $menu->id,
						"link" => $link,
					);
				}
			}
		}

		if ($list) {
			return $this->_createMenuTree($list);
		}

		return;
	}

	private function _createMenuTree($list, $parentId = 0)
	{
		$html = "";

		if (!empty($list[$parentId])) {
			$html .= "<ul>";

			foreach ($list[$parentId] as $item) {
				$html .= "<li>";
				$html .= $item["link"];
				$html .= $this->_createMenuTree($list, $item["id"]);
				$html .= "</li>";
			}

			$html .= "</ul>";
		}

		return $html;
	}
}
