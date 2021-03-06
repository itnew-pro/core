<?php

namespace itnew\controllers;

use itnew\models\Seo;
use itnew\models\Section;
use itnew\models\Language;
use CController;
use Yii;
use CHtml;
use CDbCriteria;

/**
 * Файл класса SectionController.
 *
 * Контроллер для работы с разделами
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class SectionController extends CController
{

	/**
	 * Тип панели.
	 *
	 * @var string
	 */
	public $panelType = "section";

	/**
	 * Заголовок панели.
	 *
	 * @var string
	 */
	public $panelTitle = "Sections";

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
	public $subpanelTitle = "Edit section";

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
				"users" => array("?"),
			),
		);
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
	 * Субпанель настроек
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
	 * Сохраняет настройки
	 * Получает css-класс ошибки
	 *
	 * @return bool|void
	 */
	public function actionSaveSettings()
	{
		$post = Yii::app()->request->getPost(CHtml::modelName(new Section));
		$seo = Yii::app()->request->getPost(CHtml::modelName(new Seo));

		if (!$post || !$seo) {
			return false;
		}

		$errorClass = Seo::getEmptyClass($seo);
		if (!$errorClass) {
			$errorClass = Section::model()->saveForm($post, $seo);
		}

		$json = array(
			"error" => $errorClass,
			"panel" => $this->actionPanel(true),
			"seo"   => $seo,
		);

		echo json_encode($json);
	}

	/**
	 * Удаляет раздел
	 *
	 * @return void
	 */
	public function actionDelete()
	{
		$id = Yii::app()->request->getQuery("id");
		if (!$id) {
			return null;
		}

		$model = Section::model()->findByPk($id);
		if (!$model) {
			return null;
		}

		$model->delete();
		echo $this->actionPanel(true);
	}

	/**
	 * Дублирует раздел
	 *
	 * @return void
	 */
	public function actionDuplicate()
	{
		$id = Yii::app()->request->getQuery("id");
		if (!$id) {
			return null;
		}

		$model = Section::model()->findByPk($id);
		if (!$model) {
			return null;
		}

		$model->duplicate();
		echo $this->actionPanel(true);
	}
}