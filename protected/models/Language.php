<?php

namespace itnew\models;

use itnew\models\Section;
use CActiveRecord;
use Yii;

/**
 * Файл класса Language.
 *
 * Модель для таблицы "language"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int       $id           идентификатор
 * @property string    $abbreviation абривиатура
 * @property string    $name         название
 * @property int       $main         главный
 *
 * @property Section[] $sections     модели разделов
 */
class Language extends CActiveRecord
{

	/**
	 * Список абривиатур
	 *
	 * @var string[]
	 */
	private static $_abbreviationList = array(
		"ru" => 1,
		"en" => 2,
	);

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'language';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('abbreviation, name, main', 'required'),
			array('main', 'numerical', 'integerOnly' => true),
			array('abbreviation, name', 'length', 'max' => 255),
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
			'sections' => array(
				self::HAS_MANY,
				'itnew\models\Section',
				'language_id'
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
	 * @return Language
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает идентификатор активного языка
	 *
	 * @return int
	 */
	public static function getActiveId()
	{
		if (empty(self::$_abbreviationList[Yii::app()->language])) {
			return self::$_abbreviationList[LANG];
		}

		return self::$_abbreviationList[Yii::app()->language];
	}
}
