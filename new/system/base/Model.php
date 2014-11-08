<?php

namespace system\base;

use system\db\Db;

/**
 * Файл класса Model.
 *
 * Базовый абстрактный класс для работы с моделями
 *
 * @package system.base
 *
 * @property string $tableName
 */
abstract class Model
{

	const BELONGS_TO = 1;

	/**
	 * Идентификатор
	 *
	 * @var integer
	 */
	public $id = 0;

	/**
	 * Параметры для выборки из БД
	 *
	 * @var Db
	 */
	protected $db;

	abstract public function tableName() {}

	public function __construct()
	{
		$this->db = new Db;
		$this->db->model = $this;
	}

	public function find()
	{
		$this->db->limit = 1;

		$result = $this->db->getResult();
		if (!$result) {
			return null;
		}

		/**
		 * @var Model $model
		 */
		$model = new $this;
		if (!$model->setAttributes($result[0])) {
			return null;
		}

		return $model;
	}


	public function setAttributes($values)
	{
		if (!is_array($values)) {
			return false;
		}

		foreach ($values as $attribute => $value) {
			$this->$attribute = $value;
		}

		return true;
	}
}