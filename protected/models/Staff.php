<?php

namespace itnew\models;

use itnew\models\StaffGroup;
use CActiveRecord;
use Yii;

/**
 * Файл класса Staff.
 *
 * Модель для таблицы "staff"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int          $id         идентификатор
 * @property int          $is_group   наличие групп
 * @property int          $is_detail  наличие подробного описания
 *
 * @property StaffGroup[] $staffGroup модели групп
 */
class Staff extends CActiveRecord
{

	/**
	 * Идентификаторы групп
	 *
	 * @var string
	 */
	public $groupIds = "";

	/**
	 * Шаг сортировки
	 *
	 * @var int
	 */
	const SORT_STEP = 10;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'staff';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array(
				'is_group, is_detail',
				'numerical',
				'integerOnly' => true
			),
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
			'staffGroup' => array(
				self::HAS_MANY,
				'itnew\models\StaffGroup',
				'staff_id',
				"order" => "staffGroup.sort"
			),
			"block"      => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_STAFF,
				),
			),
		);
	}

	/**
	 * Возвращает список поведений модели
	 *
	 * @return string[]
	 */
	public function behaviors()
	{
		return array(
			"ContentBehavior" => array(
				"class"     => 'itnew\behaviors\ContentBehavior',
				"blockType" => Block::TYPE_STAFF,
			)
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return string[]
	 */
	public function attributeLabels()
	{
		return array();
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Staff
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает название
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Yii::t("staff", "Staff");
	}

	/**
	 * Сохраняет контент
	 *
	 * @param string $post поля модели переданные через POST
	 *
	 * @return bool
	 */
	public function saveContent($post = array())
	{
		if (empty($post["groupIds"])) {
			return false;
		}

		$sort = self::SORT_STEP;
		foreach (explode(",", $post["groupIds"]) as $pk) {
			if ($pk) {
				$model = StaffGroup::model()->findByPk($pk);
				if ($model) {
					$model->sort = $sort;
					$model->save();
					$sort += self::SORT_STEP;
				}
			}
		}

		return true;
	}
}