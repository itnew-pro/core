<?php

namespace itnew\models;

use itnew\models\StaffGroup;
use itnew\models\Text;
use itnew\models\Images;
use itnew\models\Seo;
use CActiveRecord;
use Yii;

/**
 * Файл класса StaffContent.
 *
 * Модель для таблицы "staff_content"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int        $id           идентификатор
 * @property int        $seo_id       идентификатор СЕО
 * @property int        $photo        фото
 * @property int        $description  описание
 * @property int        $text         текст
 * @property int        $group_id     идентификатор группы
 * @property int        $sort         сортировка
 *
 * @property StaffGroup $group        модель группы
 * @property Text       $descriptionT модель описания
 * @property Images     $images       модель изображения
 * @property Seo        $seo          модель СЕО
 * @property Text       $textT        модель текста
 */
class StaffContent extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'staff_content';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('seo_id, photo, description, text, group_id, sort', 'required'),
			array('seo_id, photo, description, text, group_id, sort', 'numerical', 'integerOnly' => true),
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
			'group'        => array(
				self::BELONGS_TO,
				'itnew\models\StaffGroup',
				'group_id'
			),
			'descriptionT' => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'description'
			),
			'images'       => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'photo'
			),
			'seo'          => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
			),
			'textT'        => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'text'
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
	 * @return StaffContent
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает описание
	 *
	 * @return string
	 */
	public function getDescription()
	{
		if ($this->descriptionT) {
			return $this->descriptionT->text;
		}

		return null;
	}
}
