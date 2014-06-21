<?php

namespace itnew\models;

use itnew\models\Records;
use CActiveRecord;
use Yii;

/**
 * Файл класса RecordsClone.
 *
 * Модель для таблицы "records_clone"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int     $id             идентификатор
 * @property int     $records_id     идентификатор записей
 * @property int     $is_date        наличие даты
 * @property int     $is_detail      наличие подробного описания
 * @property int     $is_cover       наличие обложки
 * @property int     $is_description наличие описания
 * @property int     $count          количество записей на странице
 *
 * @property Records $records        модели записей
 */
class RecordsClone extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'records_clone';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array(
				'records_id, is_date, is_detail, is_cover, is_description, count',
				'required'
			),
			array(
				'records_id, is_date, is_detail, is_cover, is_description, count',
				'numerical',
				'integerOnly' => true
			),
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
			'records' => array(
				self::BELONGS_TO,
				'itnew\models\Records',
				'records_id'
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
	 * @return RecordsClone
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
