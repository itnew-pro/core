<?php

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
	 * В случае ошибки - получает CSS-класс ошибки
	 *
	 * @return string
	 */
	public function authenticate()
	{
		if (!$this->username) {
			$this->errorClass = "user-empty";
		} else {
			if (!$this->password) {
				$this->errorClass = "password-empty";
			} else {
				$model = Admin::model()->find("login = :login", array(":login" => $this->username));
				if (!$model) {
					$this->errorClass = "user-not-exist";
				} else {
					if ($model->password !== $this->password) {
						$this->errorClass = "password-wrong";
					}
				}
			}
		}

		return !$this->errorClass;
	}
}