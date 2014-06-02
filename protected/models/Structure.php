<?php

namespace itnew\models;

use itnew\models\Grid;
use itnew\models\Section;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;
use CHttpCookie;

/**
 * Файл класса Structure.
 *
 * Модель для работы с таблицей "structure"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer   $id       идентификатор структуры
 * @property integer   $width    ширина контейнера
 *
 * @property Grid[]    $grid     модели ячеек структуры
 * @property Section[] $sections модель раздела структуры
 */
class Structure extends CActiveRecord
{

	/**
	 * Размер сетки
	 *
	 * @var int
	 */
	const GRID_SIZE = 12;

	/**
	 * Ширина контейнера
	 *
	 * @var int
	 */
	const WIDTH = 987;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'structure';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('width', 'required'),
			array('width', 'numerical', 'integerOnly' => true),
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
			'grid'     => array(
				self::HAS_MANY,
				'itnew\models\Grid',
				'structure_id',
				"order" => "grid.line, grid.top, grid.left"
			),
			'sections' => array(self::HAS_MANY, 'itnew\models\Section', 'structure_id'),
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
			'width' => 'Width',
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Structure
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает структуру текущего раздела
	 *
	 * @param string $controller контроллер
	 * @param string $content    контент
	 * @param string $section    раздел
	 *
	 * @return Structure|null
	 */
	public static function getModel($controller, $content, $section)
	{
		if ($controller && $content) {
			return null;
		}

		$section = Section::model()->getActive($section);
		if (!$section) {
			return null;
		}

		return $section->structure;
	}

	public static function isContentShowPage()
	{
		if (Yii::app()->request->getQuery("contentShow")) {
			Yii::app()->request->cookies['contentShow'] = new CHttpCookie(
				"contentShow",
				Yii::app()->request->getQuery("contentShow")
			);
		}

		if (Yii::app()->request->cookies["contentShow"] == "page") {
			return true;
		}

		return false;
	}

	public function beforeDelete()
	{
		foreach ($this->grid as $grid) {
			$grid->delete();
		}

		return parent::beforeDelete();
	}

	public static function isCss()
	{
		$file =
			__DIR__ .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			"static/" .
			Yii::app()->params["siteId"] .
			"/css.css";
		if (file_exists($file)) {
			return true;
		}

		return false;
	}

	public static function isJs()
	{
		$file =
			__DIR__ .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			"static/" .
			Yii::app()->params["siteId"] .
			"/js.js";
		if (file_exists($file)) {
			return true;
		}

		return false;
	}

	public function getLines()
	{
		$list = array();

		foreach ($this->grid as $grid) {
			$list[$grid->line][] = $grid;
		}

		return $list;
	}

	public function getWidth()
	{
		$width = "100%";

		if ($this->width) {
			$width = "{$this->width}px";
		}

		return $width;
	}

	public function getMargin()
	{
		$margin = "0";

		if ($this->width) {
			$margin = "0 auto";
		}

		return $margin;
	}

	/**
	 * Получает дерево для линии
	 *
	 * @param Grid[] $grids массив из ячеек
	 *
	 * @return string[]
	 */
	public function getLineTree($grids)
	{
		$tree = array();

		$doubleGrid = array();
		for ($i = 0; $i < self::GRID_SIZE * 2; $i++) {
			$doubleGrid[$i] = 0;
		}
		foreach ($grids as $grid) {
			for ($i = $grid->left * 2; $i < ($grid->left + $grid->width) * 2 - 1; $i++) {
				$doubleGrid[$i] = 1;
			}
		}

		$borders = array();
		$flag = 0;
		foreach ($doubleGrid as $left => $val) {
			if ($val != $flag) {
				$borders[] = $left;
				$flag = $val;
			}
		}

		if ($borders) {
			for ($i = 0; $i < count($borders); $i = $i + 2) {
				if (!$i) {
					$offset = $borders[$i] / 2;
				} else {
					$offset = ($borders[$i] - $borders[$i - 1] - 1) / 2;
				}

				$gridsList = array();
				$right = 0;
				foreach ($grids as $grid) {
					if (
						$grid->left >= $borders[$i] / 2
						&& $grid->left < $borders[$i + 1] / 2
						&& $grid->width <= ($borders[$i + 1] - $borders[$i] + 1) / 2
					) {
						$gridsList[] = array(
							"block"  => $grid->block,
							"col"    => $grid->width,
							"top"    => $grid->top,
							"offset" => $grid->left - $borders[$i] / 2 - $right,
						);
						$right = $grid->left - $borders[$i] / 2 + $grid->width;
					}
				}

				$tree[] = array(
					"col"    => ($borders[$i + 1] - $borders[$i] + 1) / 2,
					"offset" => $offset,
					"grids"  => $gridsList,
				);
			}
		}

		return $tree;
	}
}