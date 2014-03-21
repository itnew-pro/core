<?php

/**
 * This is the model class for table "structure".
 *
 * The followings are the available columns in table 'structure':
 * @property integer $id
 * @property integer $size
 * @property integer $width
 *
 * The followings are the available model relations:
 * @property Grid[] $grid
 * @property Section[] $sections
 */
class Structure extends CActiveRecord
{

	/**
	 * Default grid's size
	 */
	const DEFAULT_SIZE = 64;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('width', 'required'),
			array('width', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, width', 'safe', 'on'=>'search'),
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
			'grid' => array(self::HAS_MANY, 'Grid', 'structure_id', "order" => "grid.line"),
			'sections' => array(self::HAS_MANY, 'Section', 'structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'width' => 'Width',
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
		$criteria->compare('width',$this->width);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Structure the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Gets strusture of current page
	 *
	 * @return self|null
	 */
	public static function getModel()
	{
		if (!Yii::app()->request->getQuery("controller")) {
			if (!Yii::app()->request->getQuery("content")) {
				if ($section = Section::model()->getActive()) {
					return $section->structure;
				}
			}
		}

		return null;
	}

	public static function isContentShowPage()
	{
		if (Yii::app()->request->getQuery("contentShow")) {
			Yii::app()->request->cookies['contentShow'] = new CHttpCookie(
				"contentShow",
				Yii::app()->request->getQuery("contentShow")
			);
		}

		if (Yii::app()->request->cookies["contentShow"] == "page") {
			return true;
		}

		return false;
	}

	public function beforeDelete()
	{
		if ($this->grid) {
			foreach ($this->grid as $grid) {
				$grid->delete();
			}
		}
		return parent::beforeDelete();
	}

	public static function isCss()
	{
		$file = 
			__DIR__ .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			"static/" .
			Yii::app()->params["siteId"] .
			"/css.css";
		if (file_exists($file)) {
			return true;
		}

		return false;
	}

	public static function isJs()
	{
		$file = 
			__DIR__ .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			".." .
			DIRECTORY_SEPARATOR .
			"static/" .
			Yii::app()->params["siteId"] .
			"/js.js";
		if (file_exists($file)) {
			return true;
		}

		return false;
	}
}