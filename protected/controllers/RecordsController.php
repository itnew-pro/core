<?php

/**
 * RecordsController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class RecordsController extends ContentController
{

	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("records", "Records"),
			Yii::t("records", "Description")
		);
	}

	public function actionWindowForm($return = false)
	{
		if (!$return) {
			$id = Yii::app()->request->getQuery("id");
		} else {
			$id = $return;
		}

		if ($id) {
			$model = RecordsContent::model()->findByPk($id);
			if ($model) {
				$this->windowTitle = 
					$model->records->block->name . 
					" - " .
					$model->seo->name . 
					" - " .
					Yii::t("common", "Update");

				$this->layout = "window";
				$this->windowType = "records-form";
				$this->windowLevel = 2;
				
				return $this->render("window_form", compact("model"), $return);
			}
		} 
	}

	public function actionWindowAdd()
	{
		$id = Yii::app()->request->getQuery("id");
		if ($id) {
			$records = Records::model()->findByPk($id);
			if ($records) {
				$this->windowTitle = $records->block->name . " - " . Yii::t("common", "Add");

				$model = new RecordsContent;
				$model->records_id = $id;

				$this->layout = "window";
				$this->windowType = "records-add";
				$this->windowLevel = 2;
				
				$this->render("window_add", compact("model"));
			}
		}
	}

	public function actionSaveAdd()
	{
		$array = RecordsContent::model()->saveAdd();

		echo json_encode(array(
			"errorClass"  => $array["errorClass"],
			"records"     => $this->actionWindow($array["recordsId"]),
			"recordsForm" => $this->actionWindowForm($array["id"]),
		));
	}

	public function actionDeleteRecordsContent()
	{
		$id = Yii::app()->request->getQuery("id");
		if ($id) {
			$model = RecordsContent::model()->findByPk($id);
			if ($model) {
				$model->delete();
			}
		}
	}

	public function actionSaveForm()
	{
		$id = RecordsContent::model()->saveForm();
		if ($id) {
			echo $this->actionWindow($id);
		}
	}
}