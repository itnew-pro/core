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
	 * Возвращает кнопку с лоадером
	 *
	 * @param string $class css-класс
	 * @param string $label название кнопки
	 *
	 * @return string
	 */
	public static function getButtonWithLoader($class, $label)
	{
		return "
			<button class=\"{$class}\"><span>{$label}</span>
			<div class=\"button-loader\">
				<div class=\"bounce1\"></div>
				<div class=\"bounce2\"></div>
				<div class=\"bounce3\"></div>
			</div>
			</button>
		";
	}
}