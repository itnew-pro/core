<?php

namespace itnew\controllers;

use CController;
use Yii;
use itnew\models\Admin;

/**
 * Файл класса LoginController.
 *
 * Авторизация администратора
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class LoginController extends CController
{

	/**
	 * CSS-класс для диалогового окна
	 *
	 * @var string
	 */
	public $windowType = "login";

	/**
	 * Заголовок окна
	 *
	 * @var string
	 */
	public $windowTitle = "Login";

	/**
	 * Уровень окна
	 *
	 * @var int
	 */
	public $windowLevel = 1;

	/**
	 * Выводит на экран диалоговое окно авторизации
	 *
	 * @return void
	 */
	public function actionForm()
	{
		$model = new Admin;
		$this->layout = "window";
		$this->windowTitle = $model->getAttributeLabel("windowTitle");
		$this->render("form", compact("model"));
	}

	/**
	 * Производится авторизация
	 * Результат выводится на экран
	 *
	 * @return void
	 */
	public function actionLogin()
	{
		echo Admin::login(Yii::app()->request->getPost("itnew_models_Admin"));
	}

	/**
	 * Выход
	 *
	 * @return void
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
	}
}