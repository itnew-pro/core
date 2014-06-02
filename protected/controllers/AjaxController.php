<?php

namespace itnew\controllers;

use CController;
use Yii;

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
	 * Обрабатывает ajax-запрос
	 * Проверяет необходимые входные данные и передает действие соответствующему контроллеру
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		if (Yii::app()->request->isAjaxRequest) {

			if (
				Yii::app()->request->getQuery("controller")
				&& Yii::app()->request->getQuery("action")
				&& Yii::app()->request->getQuery("language")
			) {
				$this->forward(
					Yii::app()->request->getQuery("controller") .
					DIRECTORY_SEPARATOR .
					Yii::app()->request->getQuery("action")
				);
			}
		}
	}
}