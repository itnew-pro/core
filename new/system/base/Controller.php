<?php

namespace system\base;

use system\App;
use Exception;

/**
 * Файл класса Controller.
 *
 * Базовый абстрактный класс для работы с контроллерами
 *
 * @package system.base
 */
abstract class Controller
{

	/**
	 * Абривиатура языка
	 *
	 * @var string
	 */
	public $language = "";

	/**
	 * Абривиатура раздела
	 *
	 * @var string
	 */
	public $section = "";

	/**
	 * Первый параметр
	 *
	 * @var string
	 */
	public $param1 = "";

	/**
	 * Второй параметр
	 *
	 * @var string
	 */
	public $param2 = "";

	/**
	 * Макет
	 *
	 * @var string
	 */
	public $layout = "";

	/**
	 * Название директории для представлений
	 *
	 * @return string
	 */
	protected abstract function getViewsDir();

	/**
	 * Конструктор
	 *
	 * @param string $language абривиатура языка
	 * @param string $section  абривиатура раздела
	 * @param string $param1   первый параметр
	 * @param string $param2   второй параметр
	 *
	 * @return void
	 */
	public function __construct($language = "", $section = "", $param1 = "", $param2 = "")
	{
		$this->language = $language;
		$this->section = $section;
		$this->param1 = $param1;
		$this->param2 = $param2;

		if ($language) {
			if (array_key_exists($language, App::$languageList)) {
				App::$languageId = App::$languageList[$language];
			} else {
				throw new Exception("Такого языка не существует");
			}
		}
	}

	/**
	 * Показывает или возвращает представление в макете
	 *
	 * @param string $viewFile представление
	 * @param array  $data     данные
	 * @param bool   $isReturn возвращать ли результат
	 *
	 * @return string|void
	 */
	public function render($viewFile, $data = array(), $isReturn = false)
	{
		$path = $this->getViewsRootDir() . "layouts/{$this->layout}.php";

		$content = $this->renderPartial($viewFile, $data, true);

		if ($isReturn) {
			ob_start();
			ob_implicit_flush(false);
			require($path);
			return ob_get_clean();
		} else {
			require($path);
		}
	}

	/**
	 * Показывает или возвращает представление
	 *
	 * @param string $viewFile представление
	 * @param array  $data     данные
	 * @param bool   $isReturn возвращать ли результат
	 *
	 * @return string|void
	 */
	public function renderPartial($viewFile, $data = array(), $isReturn = false)
	{
		$path = $this->getViewsRootDir();
		if ($viewFile[0] !== "/") {
			$path .= $this->getViewsDir() . "/";
		} else {
			$viewFile = substr($viewFile, 1);
		}
		$path .= "{$viewFile}.php";

		extract($data, EXTR_OVERWRITE);

		if ($isReturn) {
			ob_start();
			ob_implicit_flush(false);
			require($path);
			return ob_get_clean();
		} else {
			require($path);
		}
	}

	/**
	 * Получает путь до директории представлений
	 *
	 * @return string
	 */
	protected function getViewsRootDir()
	{
		return App::$rootDir . "views/";
	}
}