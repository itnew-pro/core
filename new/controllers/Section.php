<?php

namespace controllers;

use system\base\Controller;

class Section extends Controller
{

	public $layout = "page";

	public function actionIndex()
	{
		$this->render("index", array("aaa" => 333));
	}

	protected function getViewsDir()
	{
		return "section";
	}

}