<?php

namespace itnew\models;

use itnew\models\Block;
use itnew\models\Menu;
use itnew\models\Section;
use CActiveRecord;
use Yii;

/**
 * Файл класса MenuContent.
 *
 * Модель для таблицы "menu_content"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int     $id         идентификатор
 * @property int     $menu_id    идентификатор меню
 * @property int     $section_id идентификатор раздела
 * @property int     $block_id   идентификатор блока
 * @property int     $sort       сортировка
 * @property int     $parent_id  идентификатор родителя
 *
 * @property Block   $block      модель блока
 * @property Menu    $menu       модель меню
 * @property Section $section    модель раздела
 */
class MenuContent extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'menu_content';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('menu_id', 'required'),
			array('menu_id, section_id, block_id', 'numerical', 'integerOnly' => true),
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
			'block'   => array(
				self::BELONGS_TO,
				'itnew\models\Block',
				'block_id'
			),
			'menu'    => array(
				self::BELONGS_TO,
				'itnew\models\Menu',
				'menu_id'
			),
			'section' => array(
				self::BELONGS_TO,
				'itnew\models\Section',
				'section_id'
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
	 * @return MenuContent
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
