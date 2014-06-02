<?php

namespace itnew\models;

use itnew\models\Block;
use itnew\models\Structure;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "grid".
 *
 * The followings are the available columns in table 'grid':
 * @property integer $id
 * @property integer $structure_id
 * @property integer $line
 * @property integer $left
 * @property integer $top
 * @property integer $width
 * @property integer $block_id
 *
 * The followings are the available model relations:
 * @property Block $block
 * @property Structure $structure
 */
class Grid extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('structure_id, line, left, top, width, block_id', 'required'),
			array('structure_id, line, left, top, width, block_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, structure_id, line, left, top, width, block_id', 'safe', 'on'=>'search'),
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
			'block' => array(self::BELONGS_TO, 'itnew\models\Block', 'block_id'),
			'structure' => array(self::BELONGS_TO, 'itnew\models\Structure', 'structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'structure_id' => 'Structure',
			'line' => 'Line',
			'left' => 'Left',
			'top' => 'Top',
			'width' => 'Width',
			'block_id' => 'Block',
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
		$criteria->compare('structure_id',$this->structure_id);
		$criteria->compare('line',$this->line);
		$criteria->compare('left',$this->left);
		$criteria->compare('top',$this->top);
		$criteria->compare('width',$this->width);
		$criteria->compare('block_id',$this->block_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getContainerGrids($findIn)
	{
		$criteria = new CDbCriteria();
		$criteria->addInCondition("t.id", $findIn);
		$criteria->order = "t.top, t.left";

		return $this->findAll($criteria);
	}
}
