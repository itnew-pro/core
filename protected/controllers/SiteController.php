<?php

/**
 * SiteController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class SiteController extends CController
{

	/**
	 * Displays the page to the screen
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		//phpinfo();
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