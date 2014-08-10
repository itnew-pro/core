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
	 * Возвращает список поведений модели
	 *
	 * @return string[]
	 */
	public function behaviors()
	{
		return array(
			"ContentBehavior" => array(
				"class"     => 'itnew\behaviors\ContentBehavior',
				"blockType" => Block::TYPE_MENU,
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
	 * Получает тип
	 *
	 * @return string
	 */
	public function getType()
	{
		if (array_key_exists($this->type, $this->_typeList)) {
			return $this->_typeList[$this->type];
		}

		return $this->_typeList[0];
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
		parent::afterDelete();

		if ($this->block) {
			$this->block->delete();
		}
	}

	/**
	 * Получает неиспользуемые разделы
	 *
	 * @return string[]
	 */
	public function getUnusedSections()
	{
		$list = array();

		$sections = Section::model()->findAll();
		if (!$sections) {
			return $list;
		}

		foreach ($sections as $model) {
			$list[$model->id] = $model->seo->name;
		}

		return $list;
	}

	/**
	 * Получает html код меню
	 *
	 * @return string
	 */
	public function getHtml()
	{
		$list = array();

		if (!$this->menuContent) {
			return null;
		}

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

		if (!$list) {
			return null;
		}

		return $this->_createMenuTree($list);
	}

	/**
	 * Получает html код меню
	 *
	 * @param string[] $list     список меню
	 * @param int      $parentId идентификатор родителя
	 *
	 * @return string
	 */
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
