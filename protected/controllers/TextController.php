<?php

/**
 * TextController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class TextController extends ContentController
{

	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("text", "Text"),
			Yii::t("text", "Select the text you want to edit or add new")
		);
	}
}