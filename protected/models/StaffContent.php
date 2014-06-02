<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "staff_content".
 *
 * The followings are the available columns in table 'staff_content':
 * @property integer $id
 * @property integer $seo_id
 * @property integer $photo
 * @property integer $description
 * @property integer $text
 * @property integer $group_id
 * @property integer $sort
 *
 * The followings are the available model relations:
 * @property StaffGroup $group
 * @property Text $description
 * @property Images $photo
 * @property Seo $seo
 * @property Text $text
 */
class StaffContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'staff_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seo_id, photo, description, text, group_id, sort', 'required'),
			array('seo_id, photo, description, text, group_id, sort', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seo_id, photo, description, text, group_id, sort', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'StaffGroup', 'group_id'),
			'descriptionT' => array(self::BELONGS_TO, 'Text', 'description'),
			'images' => array(self::BELONGS_TO, 'Images', 'photo'),
			'seo' => array(self::BELONGS_TO, 'Seo', 'seo_id'),
			'textT' => array(self::BELONGS_TO, 'Text', 'text'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'seo_id' => 'Seo',
			'photo' => 'Photo',
			'description' => 'Description',
			'text' => 'Text',
			'group_id' => 'Group',
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
		$criteria->compare('seo_id',$this->seo_id);
		$criteria->compare('photo',$this->photo);
		$criteria->compare('description',$this->description);
		$criteria->compare('text',$this->text);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StaffContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getDescription()
	{
		if ($this->descriptionT) {
			return $this->descriptionT->text;
		}

		return null;
	}
}
