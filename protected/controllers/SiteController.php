<?php

namespace itnew\controllers;

use CController;
use Yii;
use Exception;
use itnew\components\Version;
use itnew\models\Structure;

/**
 * Файл класса SiteController.
 *
 * Главный контроллер. Выводит страницу на экран.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class SiteController extends CController
{

	/**
	 * Выводит страницу на экран
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		$version = new Version;
		$version->update();

		if (!Yii::app()->db->schema->getTable("site")) {
			throw new Exception("Table \"site\" was not found.");
		}

		$model = Structure::getModel(
			Yii::app()->request->getQuery("controller"),
			Yii::app()->request->getQuery("content"),
			Yii::app()->request->getQuery("section")
		);

		$this->layout = "page";

		if ($model) {
			if (!Yii::app()->user->isGuest) {
				Yii::app()->session["structureId"] = $model->id;
			}

			$this->render("index", compact("model"));
		} else {
			$this->render("empty");
		}
	}
}