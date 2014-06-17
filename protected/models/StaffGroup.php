<?php

namespace itnew\models;

use itnew\models\StaffContent;
use itnew\models\Seo;
use itnew\models\Staff;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * Файл класса StaffGroup.
 *
 * Модель для таблицы "staff_group"
 *
 * @property int            $id           идентификатор
 * @property int            $staff_id     идентификатор персонала
 * @property string         $name         название
 * @property int            $sort         сортировка
 *
 * @property StaffContent[] $staffContent модели персонала
 * @property Seo            $seo          модель СЕО
 * @property Staff          $staff        модель персонала
 */
class StaffGroup extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'staff_group';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('staff_id, sort', 'required'),
			array('staff_id, sort', 'numerical', 'integerOnly' => true),
			array("name", "length", "max" => 512),
		);
	}

	/**
	 * Возвращает связи между объектами
	 *
	 * @return string[]
	 */
	public function relations()
	{
		return array(
			'staffContent' => array(
				self::HAS_MANY,
				'itnew\models\StaffContent',
				'group_id'
			),
			'seo'          => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'name'
			),
			'staff'        => array(
				self::BELONGS_TO,
				'itnew\models\Staff',
				'staff_id'
			),
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return string[]
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t("staff", "Name"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return StaffGroup
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Сохраняет группу
	 * Возвращает идентификатор сотрудников
	 *
	 * @param string[] $attributes атрибуты
	 *
	 * @return int
	 */
	public function saveGroup($attributes = array())
	{
		$model = null;
		if ($attributes["id"]) {
			$model = $this->findByPk($attributes["id"]);
			if ($model) {
				$model->name = $attributes["name"];
				$model->save();
			}
		} else {
			$model = new self;
			$model->name = $attributes["name"];
			$model->staff_id = $attributes["staff_id"];
			$model->sort = $this->_getNewSort();
			$model->save();
		}

		if ($model) {
			return $model->staff_id;
		}

		return 0;
	}

	/**
	 * Получает новую сортировку
	 *
	 * @return int
	 */
	private function _getNewSort()
	{
		$criteria = new CDbCriteria;
		$criteria->select = "MAX(sort) AS sort";
		$row = $this->find($criteria);

		return $row["sort"] + 10;
	}
}
