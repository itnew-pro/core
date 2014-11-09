<?php

namespace controllers;

use system\base\Controller;
use models\SectionModel;

class SectionController extends Controller
{

	public $layout = "page";

	protected function getViewsDir()
	{
		return "section";
	}

	public function actionIndex()
	{
		$model = SectionModel::model()->byUrl($this->section)->find();

		$this->render("index", array("model" => $model));
	}
}