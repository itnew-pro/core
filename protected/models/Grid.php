<?php

namespace itnew\models;

use itnew\models\Block;
use itnew\models\Structure;
use CActiveRecord;
use CDbCriteria;

/**
 * Файл класса Grid.
 *
 * Модель для таблицы "grid"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int       $id           идентификатор
 * @property int       $structure_id идентификатор структуры
 * @property int       $line         линия
 * @property int       $left         отступ слева
 * @property int       $top          отступ сверху
 * @property int       $width        ширина
 * @property int       $block_id     идентификатор блока
 *
 * @property Block     $block        модель блока с контентом
 * @property Structure $structure    модель структуры
 */
class Grid extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'grid';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('structure_id, line, left, top, width, block_id', 'required'),
			array('structure_id, line, left, top, width, block_id', 'numerical', 'integerOnly' => true),
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
			'block'     => array(self::BELONGS_TO, 'itnew\models\Block', 'block_id'),
			'structure' => array(self::BELONGS_TO, 'itnew\models\Structure', 'structure_id'),
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
	 * @return Admin
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает ячейки контейнера
	 *
	 * @param int[] $ids идентификаторы ячеек
	 *
	 * @return Grid[]
	 */
	public function getContainerGrids($ids)
	{
		$criteria = new CDbCriteria();
		$criteria->addInCondition("t.id", $ids);
		$criteria->order = "t.top, t.left";

		return $this->findAll($criteria);
	}
}
