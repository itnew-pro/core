<?php

namespace itnew\models;

use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "images_content".
 *
 * The followings are the available columns in table 'images_content':
 * @property integer $id
 * @property string $file
 * @property integer $images_id
 * @property integer $sort
 * @property string $alt
 * @property string $link
 *
 * The followings are the available model relations:
 * @property Images $images
 */
class ImagesContent extends CActiveRecord
{

	const SIZE_WIDTH = 1280;
	const SIZE_HEIGHT = 800;
	const SIZE_THUMB_WIDTH = 200;
	const SIZE_THUMB_HEIGHT = 200;
	const SIZE_WINDOW_WIDTH = 150;
	const SIZE_WINDOW_HEIGHT = 113;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'images_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('images_id, sort', 'numerical', 'integerOnly'=>true),
			array('alt, link', 'length', 'max'=>255),
			array('file', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, file, images_id, sort, alt, link', 'safe', 'on'=>'search'),
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
			'images' => array(self::BELONGS_TO, 'Images', 'images_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'file' => 'File',
			'images_id' => 'Images',
			'sort' => 'Sort',
			'alt' => 'Alt',
			'link' => 'Link',
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
		$criteria->compare('file',$this->file,true);
		$criteria->compare('images_id',$this->images_id);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('alt',$this->alt,true);
		$criteria->compare('link',$this->link,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImagesContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function upload($images = null)
	{
		if ($images) {
			$name = substr(md5(microtime(1)), 0, 10) . ".jpg";

			$sizes = $this->_getSizes($images);

			$model = new self;
			$model->images_id = $images->id;
			$model->file = $name;
			if($model->save()){
				if (CUploadedFile::getInstance($model, "file")->saveAs($model->_getUploadDir() . $name)) {
					
					$image = Yii::app()->image->load($model->_getUploadDir() . $name);
					$image->
						resize($sizes["width"], $sizes["height"])->
						quality(90)->
						save($model->_getUploadDir() . "view_" . $name);
					$image->
						resize($sizes["thumbWidth"], $sizes["thumbHeight"])->
						quality(90)->
						save($model->_getUploadDir() . "thumb_" . $name);
					$image->
						resize($sizes["windowWidth"], $sizes["windowHeight"])->
						quality(90)->
						save($model->_getUploadDir() . "window_" . $name);

					return $model;
				}
			}
		}

		return;
	}

	private function _getUploadDir()
	{
		return Yii::app()->params["staticDir"] .
			DIRECTORY_SEPARATOR .
			Yii::app()->params["siteId"] .
			DIRECTORY_SEPARATOR .
			"images" .
			DIRECTORY_SEPARATOR;
	}

	public function getUrl($type = "view")
	{
		switch ($type) {
			case "view":
				$name = "view_" . $this->file;
				break;
			case "thumb":
				$name = "thumb_" . $this->file;
				break;
			case "window":
				$name = "window_" . $this->file;
				break;
			default:
				$name = $this->file;
				break;
		}

		return $this->_getDir() . $name;
	}

	private function _getSizes($images)
	{
		$sizes = array();

		if ($images->width) {
			$sizes["width"] = $images->width;
		} else {
			$sizes["width"] = self::SIZE_WIDTH;
		}
		if ($images->height) {
			$sizes["height"] = $images->height;
		} else {
			$sizes["height"] = self::SIZE_HEIGHT;
		}

		if ($images->thumb_width) {
			$sizes["thumbWidth"] = $images->thumb_width;
		} else {
			$sizes["thumbWidth"] = self::SIZE_THUMB_WIDTH;
		}
		if ($images->thumb_height) {
			$sizes["thumbHeight"] = $images->thumb_height;
		} else {
			$sizes["thumbHeight"] = self::SIZE_THUMB_HEIGHT;
		}

		$sizes["windowWidth"] = self::SIZE_WINDOW_WIDTH;
		$sizes["windowHeight"] = self::SIZE_WINDOW_HEIGHT;

		return $sizes;
	}

	public function afterDelete()
	{
		if (file_exists($this->_getUploadDir() . $this->file)) {
			unlink($this->_getUploadDir() . $this->file);
		}
		if (file_exists($this->_getUploadDir() . "view_" . $this->file)) {
			unlink($this->_getUploadDir() . "view_" . $this->file);
		}
		if (file_exists($this->_getUploadDir() . "thumb_" . $this->file)) {
			unlink($this->_getUploadDir() . "thumb_" . $this->file);
		}
		if (file_exists($this->_getUploadDir() . "window_" . $this->file)) {
			unlink($this->_getUploadDir() . "window_" . $this->file);
		}
		
		parent::afterDelete();
	}

	public function getThumbUrl()
	{
		return $this->_getDir() . "thumb_" . $this->file;
	}

	public function getFullUrl()
	{
		return $this->_getDir() . $this->file;
	}

	public function getViewUrl()
	{
		return $this->_getDir() . "view_" . $this->file;
	}

	private function _getDir()
	{
		return Yii::app()->params["baseUrl"] .
			DIRECTORY_SEPARATOR .
			"static" .
			DIRECTORY_SEPARATOR .
			Yii::app()->params["siteId"] .
			DIRECTORY_SEPARATOR .
			"images" .
			DIRECTORY_SEPARATOR;
	}
}
