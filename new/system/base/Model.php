<?php

namespace system\base;

use system\db\Db;

/**
 * Файл класса Model.
 *
 * Базовый абстрактный класс для работы с моделями
 *
 * @package system.base
 */
abstract class Model
{

	const BELONGS_TO = 1;
	const HAS_ONE = 2;
	const HAS_MANY = 3;

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

	/**
	 * Получает название связной таблицы
	 *
	 * @return string
	 */
	abstract public function tableName();

	abstract public function relations();

	abstract public function rules();

	/**
	 * Конструктор
	 */
	public function __construct()
	{
		$this->db = new Db;
		$this->db->tableName = $this->tableName();
		$this->db->relations = $this->relations();
		$this->db->fields = array_keys($this->rules());
	}

	public function byIds($ids)
	{
		if (!is_array($ids)) {
			$this->db->addCondition("t.id = :id");
			$this->db->params["id"] = $ids;
		}

		return $this;
	}

	/**
	 * Поиск модели
	 *
	 * @return null|Model
	 */
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

	/**
	 * Поиск моделей
	 *
	 * @return null|Model[]
	 */
	public function findAll()
	{
		$result = $this->db->getResult();
		if (!$result) {
			return null;
		}

		$list = array();

		foreach ($result as $values) {
			/**
			 * @var Model $model
			 */
			$model = new $this;
			$model->setAttributes($values);
			if ($model) {
				$list[] = $model;
			}
		}

		return $list;
	}

	/**
	 * Устанавливает атрибуты модели
	 *
	 * @param array $values значения атрибутов
	 *
	 * @return bool
	 */
	public function setAttributes($values = array())
	{
		if (!is_array($values)) {
			return false;
		}

		$attributes = array();

		foreach ($values as $key => $val) {
			$explode = explode("__", $key, 2);
			$attributes[$explode[0]][$explode[1]] = $val;
		}

		if (!$attributes) {
			return false;
		}

		$relations = $this->relations();
		foreach ($attributes as $key => $fields) {
			if ($key == "t") {
				foreach ($fields as $name => $value) {
					$this->$name = $value;
				}
			} else {
				$model = new $relations[$key][0];
				foreach ($fields as $name => $value) {
					$model->$name = $value;
				}
				$this->$key = $model;
			}
		}

		return true;
	}
}