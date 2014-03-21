<?php

/**
 * PanelController class file.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class SectionController extends CController
{

	/**
	 * Panel type.
	 *
	 * @return string
	 */
	public $panelType = "section";

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public $panelTitle = "Sections";

	/**
	 * Panel description.
	 *
	 * @return string
	 */
	public $panelDescription = "";

	/**
	 * Subpanel title.
	 *
	 * @return string
	 */
	public $subpanelTitle = "Edit section";

	/**
	 * Returns the filter configurations.
	 *
	 * @return string[]
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
				"users" => array("?"),
			),
		);
	}

	/**
	 * Section's panel
	 *
	 * @param bool $return parameter for render
	 *
	 * @return void
	 */
	public function actionPanel($return = false)
	{
		$criteria = new CDbCriteria();
		$criteria->order = "seo.name";
		$criteria->condition = "t.language_id = :language_id";
		$criteria->params = array(":language_id" => Language::getActiveId());
		$sections = Section::model()->with("seo")->findAll($criteria);

		$this->layout = "panel";

		$this->panelTitle = Yii::t("section", "Sections");
		$this->panelDescription = Yii::t("section", "You can add and edit sections");

		return $this->render("panel", compact("sections"), $return);
	}

	/**
	 * Section's subpanel
	 *
	 * @return void
	 */
	public function actionSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			$model = Section::model()->findByPk(Yii::app()->request->getQuery("id"));
		} else {
			$model = new Section;
		}
		if ($model) {
			$this->layout = "subpanel";
			if ($model->id) {
				$this->subpanelTitle = Yii::t("section", "Edit section");
			} else {
				$this->subpanelTitle = Yii::t("section", "Add section");
			}
			$this->render("settings", compact("model"));
		}
	}

	/**
	 * Save subpanel form
	 * Gets error class, panel content and new sections seo
	 *
	 * @return string
	 */
	public function actionSaveSettings()
	{
		$errorClass = Seo::getEmptyClass();
		$seo = Yii::app()->request->getPost("Seo");

		if (!$errorClass) {
			$errorClass = Section::model()->saveForm($seo);
		}

		$json = array(
			"error" => $errorClass,
			"panel" => $this->actionPanel(true),
			"seo" => $seo,
		);

		echo json_encode($json);
	}

	public function actionDelete()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = Section::model()->findByPk(Yii::app()->request->getQuery("id"))) {
				var_dump($model->id);
				$model->delete();
				echo $this->actionPanel(true);
			}
			
		}
	}

	public function actionDuplicate()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = Section::model()->findByPk(Yii::app()->request->getQuery("id"))) {
				$model->duplicate();
			}
			echo $this->actionPanel(true);
		}
	}
}