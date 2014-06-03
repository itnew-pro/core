<?php

namespace itnew\controllers;

use CController;
use Yii;
use CHttpException;

/**
 * Файл класса AjaxController.
 *
 * Обрабатывает все ajax-запросы
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class AjaxController extends CController
{

	/**
	 * Ошибка
	 *
	 * @var string
	 */
	public static $error = "";

	/**
	 * Обрабатывает ajax-запрос
	 * Проверяет необходимые входные данные и передает действие соответствующему контроллеру
	 *
	 * @throws CHttpException
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		$controller = Yii::app()->request->getQuery("controller");
		$action = Yii::app()->request->getQuery("action");
		$language = Yii::app()->request->getQuery("language");

		if (
			!Yii::app()->request->isAjaxRequest
			|| !$controller
			|| !$action
			|| !$language
		) {
			throw new CHttpException(404, Yii::t("errors", "Invalid AJAX request"));
		}

		$this->forward("{$controller}/{$action}");
	}
}