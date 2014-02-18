<?php

/**
 * StaffController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class StaffController extends ContentController
{

	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("staff", "Staff"),
			Yii::t("staff", "Description")
		);
	}

	public function actionWindowGroup()
	{
		$this->windowTitle = Yii::app()->request->getQuery("name");

		if (Yii::app()->request->getQuery("id")) {
			$model = StaffGroup::model()->findByPk(Yii::app()->request->getQuery("id"));
			$this->windowTitle .= " - " . Yii::t("staff", "Edit group");
		} else {
			$model = new StaffGroup;
			$this->windowTitle .= " - " . Yii::t("staff", "Add group");
		}

		if ($model) {
			$this->layout = "window";
			$this->windowType = "staff-group";
			$this->windowLevel = 2;
			
			$this->render("window_group", compact("model"));
		}
	}

	public function actionSaveGroup()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->loadModel(Yii::app()->request->getQuery("id"))) {
				$model->saveContent();
			}
		}
	}
}