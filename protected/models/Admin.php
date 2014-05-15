<?php

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
	 * Получает список моделей на основе условий поиска / фильтров.
	 *
	 * @return CActiveDataProvider
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare("id", $this->id);
		$criteria->compare("login", $this->login, true);
		$criteria->compare("password", $this->password, true);

		return new CActiveDataProvider($this, array(
			"criteria" => $criteria,
		));
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return self
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Производит авторизацию.
	 * Возвращает CSS-класс ошибки
	 *
	 * @return string
	 */
	public static function login()
	{
		$admin = Yii::app()->request->getPost("Admin");
		$identity = new UserIdentity($admin["login"], $admin["password"]);
		if ($identity->authenticate()) {
			$remember = false;
			if ($admin["remember"]) {
				$remember = 60 * 60 * 24 * 30;
			}
			Yii::app()->user->login($identity, $remember);
		} else {
			return $identity->errorClass;
		}

		return null;
	}
}