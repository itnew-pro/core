<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "staff".
 *
 * The followings are the available columns in table 'staff':
 * @property integer $id
 * @property integer $is_group
 * @property integer $is_detail
 *
 * The followings are the available model relations:
 * @property StaffGroup[] $staffGroup
 */
class Staff extends CActiveRecord
{
	public $groupIds;

	const SORT_STEP = 10;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'staff';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('is_group, is_detail', 'numerical', 'integerOnly' => true),
			array('id, is_group, is_detail', 'safe', 'on'=>'search'),
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
			'staffGroup' => array(self::HAS_MANY, 'StaffGroup', 'staff_id', "order" => "staffGroup.sort"),
			"block" => array(
				self::HAS_ONE,
				'Block',
				'content_id',
				"condition" => "block.type = :type",
				"params" => array(
					":type" => Block::TYPE_STAFF,
				),
			),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'is_group' => 'Is Group',
			'is_detail' => 'Is Detail',
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
		$criteria->compare('is_group',$this->is_group);
		$criteria->compare('is_detail',$this->is_group);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Staff the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTitle()
	{
		return Yii::t("staff", "Staff");
	}

	public function getAllContentBlocks($notIn = null)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "language_id = :language_id AND type = :type";
		$criteria->params = array(
			":language_id" => Language::getActiveId(),
			":type" => Block::TYPE_STAFF
		);

		if ($notIn) {
			$criteria->condition .= " AND id IN ({$notIn})";
		}

		return Block::model()->findAll($criteria);
	}

	public function getBlock()
	{
		if ($this->block) {
			return $this->block;
		}
		return new Block;
	}

	public function saveSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->findByPk(Yii::app()->request->getQuery("id"))) {
				if ($block = $model->getBlock()) {
					$model->attributes = Yii::app()->request->getPost("Staff");
					$block->attributes = Yii::app()->request->getPost("Block");

					$transaction = Yii::app()->db->beginTransaction();
					if ($block->save()) {
						if ($model->save()) {
							$transaction->commit();
							return $model;
						}
					}
					$transaction->rollback();
				}
			}
		} 

		else {
			$model = new self;
			$block = new Block;
			$model->attributes = Yii::app()->request->getPost("Staff");
			$block->attributes = Yii::app()->request->getPost("Block");

			$transaction = Yii::app()->db->beginTransaction();
			if ($model->save()) {
				$block->content_id = $model->id;
				$block->type = Block::TYPE_STAFF;
				$block->language_id = Language::getActiveId();
				if ($block->save()) {
					$transaction->commit();
					return $model;
				}
			}
			$transaction->rollback();
		}

		return null;
	}

	public function saveContent()
	{
		$attributes = Yii::app()->request->getPost("Staff");
		if ($attributes) {
			if (!empty($attributes["groupIds"])) {
				$sort = self::SORT_STEP;
				foreach (explode(",", $attributes["groupIds"]) as $pk) {
					if ($pk) {
						if ($model = StaffGroup::model()->findByPk($pk)) {
							$model->sort = $sort;
							$model->save();
							$sort += self::SORT_STEP;
						}
					}
				}
			}
		}
	}
}