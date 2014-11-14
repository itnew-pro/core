<?php

namespace controllers;

use models\GridModel;
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
		if (!$model) {
			throw new \Exception("Модель раздела не найдена");
		}

		$grids = GridModel::model()->bySectionId($model->id)->withContent()->findAll();

		$this->render("index", array("model" => $model));
	}
}