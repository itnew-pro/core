<?php

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
		if (Yii::app()->db->schema->getTable("site")) {
			$model = Structure::getModel();

			if (!Yii::app()->user->isGuest && $model) {
				Yii::app()->session["structureId"] = $model->id;
			}

			$this->layout = "page";
			$this->render("index", compact("model"));
		}
	}
}