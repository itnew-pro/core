<?php

namespace itnew\controllers;

use CController;
use Yii;

/**
 * Файл класса CatalogController.
 *
 * Контроллер для работы с каталогом
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package controllers
 */
class CatalogController extends ContentController
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
			Yii::t("catalog", "Catalog"),
			Yii::t("catalog", "")
		);
	}
}