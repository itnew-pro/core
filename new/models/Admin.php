<?php

namespace models;

use system\base\Model;

/**
 * Файл класса Admin
 *
 * @package models
 *
 * @method Admin find()
 */
class Admin extends Model
{

	/**
	 * Логин
	 *
	 * @var string
	 */
	public $login = "";

	/**
	 * Пароль
	 *
	 * @var string
	 */
	public $password = "";

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return "admins";
	}

	/**
	 * Получает объект модели
	 *
	 * @param string $className
	 *
	 * @return Admin
	 */
	public static function model($className = __CLASS__)
	{
		return new $className;
	}

	public $fields = array(
		"login",
		"password",
	);

	/**
	 * Правила валидации
	 *
	 * @return array
	 */
	public function rules()
	{
		return array(
			"login"    => array(
				"max"        => 255,
				"isRequired" => true,
				"isUnique"   => true,
			),
			"password" => array(
				"max"        => 255,
				"isRequired" => true,
			)
		);
	}

	/**
	 * Выбор по логину
	 *
	 * @param string $login логин
	 *
	 * @return Admin
	 */
	public function login($login = "")
	{
		if ($login) {
			$this->db->addCondition("{$this->tableName}.login = :login");
			$this->db->params["login"] = $login;
		}

		return $this;
	}

	/**
	 * Выбор по паролю
	 *
	 * @param string $password пароль
	 *
	 * @return Admin
	 */
	public function password($password = "")
	{
		if ($password) {
			$this->db->addCondition("{$this->tableName}.password = :password");
			$this->db->params["password"] = $password;
		}

		return $this;
	}
}