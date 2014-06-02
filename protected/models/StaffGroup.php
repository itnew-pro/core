<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "staff_group".
 *
 * The followings are the available columns in table 'staff_group':
 * @property integer $id
 * @property integer $staff_id
 * @property string $name
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property StaffContent[] $staffContent
 * @property Seo $seo
 * @property Staff $staff
 */
class StaffGroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'staff_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('staff_id, sort', 'required'),
			array('staff_id, sort', 'numerical', 'integerOnly'=>true),
			array("name", "length", "max" => 512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, staff_id, name, sort', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'staffContent' => array(self::HAS_MANY, 'StaffContent', 'group_id'),
			'seo' => array(self::BELONGS_TO, 'Seo', 'name'),
			'staff' => array(self::BELONGS_TO, 'Staff', 'staff_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'staff_id' => 'Staff',
			'name' => Yii::t("staff", "Name"),
			'sort' => 'Sort',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('staff_id',$this->staff_id);
		$criteria->compare('name',$this->name);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StaffGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function saveGroup()
	{
		$attributes = Yii::app()->request->getPost("StaffGroup");

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
	}

	private function _getNewSort()
	{
		$criteria = new CDbCriteria;
		$criteria->select = "MAX(sort) AS sort";
		$row = $this->find($criteria);
		return $row["sort"] + 10;
	}
}
