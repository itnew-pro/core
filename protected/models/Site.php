<?php

namespace itnew\models;

use CActiveRecord;
use Yii;

/**
 * Файл класса Site.
 *
 * Модель для таблицы "site"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int    $id           идентификатор
 * @property string $name         название
 * @property string $email        электронная почта
 * @property string $title        заголовок
 * @property string $keywords     ключевые слова
 * @property string $description  описание
 * @property string $migrate_time время миграции
 */
class Site extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'site';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('name, email, title, keywords', 'length', 'max' => 255),
		);
	}

	/**
	 * Возвращает связи между объектами
	 *
	 * @return string[]
	 */
	public function relations()
	{
		return array();
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
	 * @return Site
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
