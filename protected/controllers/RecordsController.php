<?php

namespace itnew\controllers;

use itnew\models\Records;
use itnew\models\RecordsContent;
use itnew\models\Seo;
use itnew\models\Images;
use itnew\models\Text;
use CController;
use Yii;
use CHtml;

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
	 * @return bool|void
	 */
	public function actionSaveAdd()
	{
		$post = Yii::app()->request->getPost(CHtml::modelName(new RecordsContent));
		$seoPost = Yii::app()->request->getPost(CHtml::modelName(new Seo));

		if (!$post || !$seoPost) {
			return false;
		}

		$list = RecordsContent::model()->saveAdd($post, $seoPost);
		if (!$list) {
			return false;
		}

		echo json_encode(array(
			"errorClass"  => $list["errorClass"],
			"records"     => $this->actionWindow($list["recordsId"]),
			"recordsForm" => $this->actionWindowForm($list["id"]),
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
		$post = Yii::app()->request->getPost(CHtml::modelName(new RecordsContent));
		$seo = Yii::app()->request->getPost(CHtml::modelName(new Seo));
		$description = Yii::app()->request->getPost("Description");
		$images = Yii::app()->request->getPost(CHtml::modelName(new Images));
		$text = Yii::app()->request->getPost(CHtml::modelName(new Text));

		if ($post && $seo && !empty($post["id"])) {
			$id = RecordsContent::model()->saveForm($post, $seo, $description, $images, $text);
			if ($id) {
				echo $this->actionWindow($id);
			}
		}
	}

	/**
	 * Получает параметры для настроек
	 *
	 * @return string[]
	 */
	protected function getSettingsParams()
	{
		$list = array();

		$list[] = array(
			"relation"  => "coverRelation",
			"attribute" => "cover",
			"class"     => 'itnew\models\Images',
			"post"      => "Cover",
		);
		$list[] = array(
			"relation"  => "imagesRelation",
			"attribute" => "images",
			"class"     => 'itnew\models\Images',
			"post"      => "itnew_models_Images",
		);

		return $list;
	}
}