<?php

/**
 * LoginController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class LoginController extends CController
{

	/**
	 * Window class type
	 *
	 * @var string
	 */
	public $windowType = "login";

	/**
	 * Window title
	 *
	 * @var string
	 */
	public $windowTitle = "Login";

	public $windowLevel = 1;

	/**
	 * Login window
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
	 * Login
	 *
	 * @return void
	 */
	public function actionLogin()
	{
		echo Admin::login();
	}

	/**
	 * Logout
	 *
	 * @return void
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
	}

}