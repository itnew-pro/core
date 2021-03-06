<?php

namespace itnew\controllers;

use itnew\models\ImagesContent;
use CController;
use Yii;

/**
 * Файл класса ImageController.
 *
 * Контроллер для работы с изображениями
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class ImagesController extends ContentController
{

	/**
	 * Панель управления
	 * Выводит на экран или получает html-код
	 *
	 * @param bool $return получить ли html-код (в противном случае выводит на экран)
	 *
	 * @return string|void
	 */
	public function actionPanel($return = false)
	{
		return $this->actionContentPanel(
			$return,
			Yii::t("images", "Images"),
			Yii::t("images", "Description")
		);
	}

	/**
	 * Загружает изображение и выводит на экран миниатюрку.
	 *
	 * @return void
	 */
	public function actionUpload()
	{
		$id = Yii::app()->request->getQuery("id");
		if ($id) {
			$model = $this->loadModel($id);
			$imagesContent = ImagesContent::model()->upload($model);
			if ($imagesContent) {
				$this->render("_window_item", array("model" => $imagesContent));
			}
		}
	}

	/**
	 * Удаляет изображение
	 *
	 * @return void
	 */
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