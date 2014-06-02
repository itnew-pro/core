<?php

namespace itnew\controllers;

use itnew\models\Structure;
use CController;
use Yii;

/**
 * Файл класса ContentController.
 *
 * Является базовым классом для панели управления контентом.
 * Является как классом-родителем, так и самостоятельным классом.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class ContentController extends CController
{

	/**
	 * Тип панели.
	 *
	 * @var string
	 */
	public $panelType = "content";

	/**
	 * Заголовок панели.
	 *
	 * @var string
	 */
	public $panelTitle = "";

	/**
	 * Описание панели.
	 *
	 * @var string
	 */
	public $panelDescription = "";

	/**
	 * Заголовок субпанели.
	 *
	 * @var string
	 */
	public $subpanelTitle = "";

	/**
	 * Заголовок окна.
	 *
	 * @var string
	 */
	public $windowTitle = "";

	/**
	 * Уровень окна.
	 *
	 * @var int
	 */
	public $windowLevel = 1;

	/**
	 * Тип окна.
	 *
	 * @var string
	 */
	public $windowType = "";

	/**
	 * Получает фильтр настроек.
	 *
	 * @var string[]
	 */
	public function filters()
	{
		return array(
			"accessControl",
		);
	}

	/**
	 * Получает права доступа для данного контроллера.
	 *
	 * @return string[]
	 */
	public function accessRules()
	{
		return array(
			array(
				"deny",
				"actions" => array("panel"),
				"users"   => array("?"),
			),
		);
	}

	/**
	 * Получает модель данного контроллера
	 *
	 * @param int $pk идентификатор модели
	 *
	 * @return object
	 */
	protected function loadModel($pk = 0)
	{
		$modelName = ucfirst($this->id);
		$model = new $modelName;
		if ($pk) {
			$model = $model->findByPk($pk);
		}

		return $model;
	}

	/**
	 * Получает html-код контента
	 *
	 * @param int $pk идентификатор модели
	 *
	 * @return string|null
	 */
	public function getContent($pk)
	{
		if ($model = $this->loadModel($pk)) {
			return $this->renderPartial(
				"content",
				array("model" => $model),
				true
			);
		}

		return null;
	}

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
		$this->layout = "panel";

		$this->panelTitle = Yii::t("content", "Content");
		$this->panelDescription = Yii::t("content", "Create and fill in the information blocks for your website");

		if (Structure::isContentShowPage()) {
			$blocks = Block::model()->getThisPageBlocks();
		} else {
			$blocks = Block::model()->getAllContentBlocks();
		}

		return $this->render("panel", compact("blocks"), $return);
	}

	/**
	 * Панель управления блоков одной тематики
	 * Выводит на экран или получает html-код
	 *
	 * @param bool   $return      получить ли html-код (в противном случае выводит на экран)
	 * @param string $title       заголовок
	 * @param string $description описание
	 *
	 * @return string|void
	 */
	public function actionContentPanel($return = false, $title = "", $description = "")
	{
		$this->layout = "panel";

		$this->panelTitle = $title;
		$this->panelDescription = $description;

		if (Structure::isContentShowPage()) {
			$blocks = $this->loadModel()->getThisPageBlocks();
		} else {
			$blocks = $this->loadModel()->getAllContentBlocks();
		}

		return $this->render("panel", compact("blocks"), $return);
	}

	/**
	 * Окно
	 * Выводит на экран или получает html-код
	 *
	 * @param int $pk идентификатор модели
	 *
	 * @return string|void|null
	 */
	public function actionWindow($pk = 0)
	{
		$return = true;
		if (!$pk) {
			$pk = Yii::app()->request->getQuery("id");
			$return = false;
		}
		if ($pk) {
			$model = $this->loadModel($pk);
			if ($model) {
				$this->layout = "window";
				$this->windowType = Yii::app()->controller->id;
				$this->windowTitle = $model->block->name;
				return $this->render("window", compact("model"), $return);
			}
		}

		return null;
	}

	/**
	 * Сохраняет модель в окне
	 *
	 * @return void
	 */
	public function actionSaveWindow()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->loadModel(Yii::app()->request->getQuery("id"))) {
				$model->saveContent();
			}
		}
	}

	/**
	 * Субпанель настроек
	 *
	 * @return void
	 */
	public function actionSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			$this->subpanelTitle = Yii::t("block", "Update block");
			$model = $this->loadModel(Yii::app()->request->getQuery("id"));
		} else {
			$this->subpanelTitle = Yii::t("block", "Add block");
			$model = $this->loadModel();
		}

		if ($model) {
			$this->layout = "subpanel";
			$this->render("settings", compact("model"));
		}
	}

	/**
	 * Сохраняет настройки
	 *
	 * @return void
	 */
	public function actionSaveSettings()
	{
		if ($model = $this->loadModel()->saveSettings()) {
			$json = array(
				"panel"   => $this->actionPanel(true),
				"content" => $this->getContent($model->id),
			);

			echo json_encode($json);
		}
	}

	/**
	 * Удаляет модель
	 *
	 * @return void
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->loadModel(Yii::app()->request->getQuery("id"))) {
				$model->delete();
			}
			echo $this->actionPanel(true);
		}
	}

	/**
	 * Дублирует модель
	 *
	 * @return void
	 */
	public function actionDuplicate()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->loadModel(Yii::app()->request->getQuery("id"))) {
				$model->duplicate();
			}
			echo $this->actionPanel(true);
		}
	}
}