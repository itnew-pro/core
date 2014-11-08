<?php

namespace system\base;

use system\App;

abstract class Controller
{

	public $language = "";
	public $section = "";
	public $param1 = "";
	public $param2 = "";
	public $layout = "";

	public function __construct($language, $section, $param1, $param2)
	{
		$this->language = $language;
		$this->section = $section;
		$this->param1 = $param1;
		$this->param2 = $param2;
	}

	protected abstract function getViewsDir();

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

	public function renderPartial($viewFile, $data = array(), $isReturn = false)
	{
		$path = $this->getViewsRootDir() . $this->getViewsDir() . "/{$viewFile}.php";

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

	protected function getViewsRootDir()
	{
		return App::$rootDir . "views/";
	}
}