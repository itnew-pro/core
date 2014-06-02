<?php

namespace itnew\controllers;

use CController;
use Yii;

/**
 * Файл класса RecordsController.
 *
 * Контроллер для работы с записями
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class RecordsController extends ContentController
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
			Yii::t("records", "Records"),
			Yii::t("records", "Description")
		);
	}

	/**
	 * Редактирование записи
	 *
	 * @param bool $return получить ли html-код (в противном случае выводит на экран)
	 *
	 * @return string|void
	 */
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

	/**
	 * Добавление записи (окно)
	 *
	 * @return void
	 */
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

	/**
	 * Добавление записи (обработка данных)
	 *
	 * @return void
	 */
	public function actionSaveAdd()
	{
		$array = RecordsContent::model()->saveAdd();

		echo json_encode(array(
			"errorClass"  => $array["errorClass"],
			"records"     => $this->actionWindow($array["recordsId"]),
			"recordsForm" => $this->actionWindowForm($array["id"]),
		));
	}

	/**
	 * Удаление записи
	 *
	 * @return void
	 */
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

	/**
	 * Сохранение порядка записей
	 *
	 * @return void
	 */
	public function actionSaveForm()
	{
		$id = RecordsContent::model()->saveForm();
		if ($id) {
			echo $this->actionWindow($id);
		}
	}
}