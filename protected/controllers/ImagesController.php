<?php

/**
 * ImageController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class ImagesController extends ContentController
{

	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("images", "Images"),
			Yii::t("images", "Description")
		);
	}

	public function actionUpload()
	{
		if (Yii::app()->request->getQuery("id")) {
			$model = $this->loadModel(Yii::app()->request->getQuery("id"));
			if ($imagesContent = ImagesContent::model()->upload($model)) {
				$this->render("_window_item", array("model" => $imagesContent));
			}
		}
	}

	public function actionDeleteImage()
	{
		if (Yii::app()->request->getQuery("id")) {
			$model = ImagesContent::model()->findByPk(Yii::app()->request->getQuery("id"));
			if ($model) {
				$model->delete();
			}
		}
	}
}