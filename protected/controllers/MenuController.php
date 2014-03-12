<?php

/**
 * MenuController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class MenuController extends ContentController
{

	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("menu", "Menu"),
			Yii::t("menu", "Description")
		);
	}
}