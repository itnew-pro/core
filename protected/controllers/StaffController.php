<?php

/**
 * Файл класса StaffController.
 *
 * Контроллер для работы с сотрудниками
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class StaffController extends ContentController
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
			Yii::t("staff", "Staff"),
			Yii::t("staff", "Description")
		);
	}

	/**
	 * Группа сотрудников.
	 * Окно. Админка
	 *
	 * @return void
	 */
	public function actionWindowGroup()
	{
		$this->windowTitle = Yii::app()->request->getQuery("name");

		$model = null;
		if (Yii::app()->request->getQuery("id")) {
			$model = StaffGroup::model()->findByPk(Yii::app()->request->getQuery("id"));
			$this->windowTitle .= " - " . Yii::t("staff", "Edit group");
		} else {
			$model = new StaffGroup;
			$model->staff_id = Yii::app()->request->getQuery("staff_id");
			$this->windowTitle .= " - " . Yii::t("staff", "Add group");
		}

		if ($model) {
			$this->layout = "window";
			$this->windowType = "staff-group";
			$this->windowLevel = 2;

			$this->render("window_group", compact("model"));
		}
	}

	/**
	 * Сохраняет группу
	 *
	 * @return void
	 */
	public function actionSaveGroup()
	{
		$pk = StaffGroup::model()->saveGroup();
		if ($pk) {
			echo $this->actionWindow($pk);
		}
	}

	/**
	 * Удаляет группу
	 *
	 * @return void
	 */
	public function actionDeleteGroup()
	{
		$pk = Yii::app()->request->getQuery("id");
		if ($pk) {
			$model = StaffGroup::model()->findByPk($pk);
			if ($model) {
				$model->delete();
			}
		}
	}
}