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
	 * Получает html-код лоадера
	 *
	 * @param string $class название класса-вспомогателя
	 *
	 * @return string
	 */
	public static function loader($class = null)
	{
		if ($class) {
			$class = " loader-{$class}";
		}
		return "
			<div class=\"loader{$class}\">
				<div class=\"f_circleG frotateG_01\"></div>
				<div class=\"f_circleG frotateG_02\"></div>
				<div class=\"f_circleG frotateG_03\"></div>
				<div class=\"f_circleG frotateG_04\"></div>
				<div class=\"f_circleG frotateG_05\"></div>
				<div class=\"f_circleG frotateG_06\"></div>
				<div class=\"f_circleG frotateG_07\"></div>
				<div class=\"f_circleG frotateG_08\"></div>
			</div>
		";
	}

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