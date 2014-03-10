<?php

/**
 * ContentController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class ContentController extends CController
{

	/**
	 * Panel type.
	 *
	 * @var string
	 */
	public $panelType = "content";

	/**
	 * Panel title.
	 *
	 * @var string
	 */
	public $panelTitle = "";

	/**
	 * Panel description.
	 *
	 * @var string
	 */
	public $panelDescription = "";

	/**
	 * Subpanel title.
	 *
	 * @var string
	 */
	public $subpanelTitle = "";

	/**
	 * Window title.
	 *
	 * @var string
	 */
	public $windowTitle = "";

	/**
	 * Window level.
	 *
	 * @var int
	 */
	public $windowLevel = 1;

	/**
	 * Window type.
	 *
	 * @var string
	 */
	public $windowType = "";

	protected function loadModel($pk = false)
	{
		$modelName = ucfirst($this->id);
		$model = new $modelName;
		if ($pk) {
			$model = $model->findByPk($pk);
		}
		return $model;
	}

	/**
	 * Returns the filter configurations.
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
	 * Returns the access rules for this controller.
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
	 * Gets content
	 *
	 * @param int $pk
	 *
	 * @return string
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
	 * Panel
	 *
	 * @param bool $return parameter for render
	 *
	 * @return void
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
	 * Content panel
	 *
	 * @param bool $return parameter for render
	 *
	 * @return void
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
	 * Window
	 *
	 * @return void
	 */
	public function actionWindow($pk = false)
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
	}

	/**
	 * Update content
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
	 * Settings
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
	 * Saves settings
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
	 * Delete
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
	 * Duplicate
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