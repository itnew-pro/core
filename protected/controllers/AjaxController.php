<?php

/**
 * AjaxController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class AjaxController extends CController
{

	/**
	 * Main action
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		//sleep(2);
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