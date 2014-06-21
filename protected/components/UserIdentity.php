<?php

namespace itnew\components;

use CUserIdentity;
use CDbCriteria;
use itnew\models\Admin;

/**
 * Файл класса UserIdentity.
 *
 * Авторизация администратора
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package components
 */
class UserIdentity extends CUserIdentity
{

	/**
	 * CSS-класс ошибки
	 *
	 * @var string
	 */
	public $errorClass = null;

	/**
	 * Проверяет наличие пользователя с заданными логином и паролем
	 *
	 * @return bool
	 */
	public function authenticate()
	{
		if (!$this->username) {
			$this->errorClass = "user-empty";
			return false;
		}

		if (!$this->password) {
			$this->errorClass = "password-empty";
			return false;
		}

		$criteria = new CDbCriteria;
		$criteria->condition = "t.login = :login";
		$criteria->params["login"] = $this->username;
		$model = Admin::model()->find($criteria);
		if (!$model) {
			$this->errorClass = "user-not-exist";
			return false;
		}

		if ($model->password !== $this->password) {
			$this->errorClass = "password-wrong";
			return false;
		}

		return true;
	}
}