<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;
use itnew\components\UserIdentity;

/**
 * Файл класса Admin.
 *
 * Модель для таблицы "admin"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property integer $id       идентификатор
 * @property string  $login    логин
 * @property string  $password пароль
 */
class Admin extends CActiveRecord
{

	/**
	 * Флаг. Запомнить ли администратора
	 *
	 * @var bool
	 */
	public $remember = false;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "admin";
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array("login, password", "required"),
			array("login, password", "length", "max" => 255),
			array("id, login, password", "safe", "on" => "search"),
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
		return array(
			"windowTitle" => Yii::t("admin", "Login"),
			"login"       => Yii::t("admin", "User"),
			"password"    => Yii::t("admin", "Password"),
			"remember"    => Yii::t("admin", "Remember me"),
		);
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
	 * Производит авторизацию.
	 * Возвращает CSS-класс ошибки
	 *
	 * @param string[] $post данные переданные через $_POST при входе в панель управления
	 *
	 * @return string
	 */
	public static function login($post = array())
	{
		$identity = new UserIdentity($post["login"], $post["password"]);
		if ($identity->authenticate()) {
			$remember = false;
			if ($post["remember"]) {
				$remember = 60 * 60 * 24 * 30;
			}
			Yii::app()->user->login($identity, $remember);
		} else {
			return $identity->errorClass;
		}

		return null;
	}
}