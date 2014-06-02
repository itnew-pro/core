<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "records_clone".
 *
 * The followings are the available columns in table 'records_clone':
 * @property integer $id
 * @property integer $records_id
 * @property integer $is_date
 * @property integer $is_detail
 * @property integer $is_cover
 * @property integer $is_description
 * @property integer $count
 *
 * The followings are the available model relations:
 * @property Records $records
 */
class RecordsClone extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'records_clone';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('records_id, is_date, is_detail, is_cover, is_description, count', 'required'),
			array('records_id, is_date, is_detail, is_cover, is_description, count', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, records_id, is_date, is_detail, is_cover, is_description, count', 'safe', 'on'=>'search'),
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
			'records' => array(self::BELONGS_TO, 'Records', 'records_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'records_id' => 'Records',
			'is_date' => 'Is Date',
			'is_detail' => 'Is Detail',
			'is_cover' => 'Is Cover',
			'is_description' => 'Is Description',
			'count' => 'Count',
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
		$criteria->compare('records_id',$this->records_id);
		$criteria->compare('is_date',$this->is_date);
		$criteria->compare('is_detail',$this->is_detail);
		$criteria->compare('is_cover',$this->is_cover);
		$criteria->compare('is_description',$this->is_description);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RecordsClone the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
