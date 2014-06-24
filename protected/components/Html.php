<?php

namespace itnew\components;

use CHtml;
use Yii;

/**
 * Файл класса Html.
 *
 * Класс с набором статических методов.
 * Получает html-код различных сущностей и прочего.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package components
 */
class Html extends CHtml
{

	/**
	 * Получает текст кнопки ("Добавить" или "Редактировать")
	 *
	 * @param object $model модель, для которой создается кнопка
	 *
	 * @return string
	 */
	public static function getButtonText($model = null)
	{
		if ($model) {
			if (!$model->isNewRecord) {
				return Yii::t("common", "Update");
			}
		}
		return Yii::t("common", "Add");
	}
}