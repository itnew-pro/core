<?php

/**
 * This is the model class for table "seo".
 *
 * The followings are the available columns in table "seo":
 *
 * @property integer   $id
 * @property string    $name
 * @property string    $url
 * @property string    $title
 * @property string    $keywords
 * @property string    $description
 *
 * The followings are the available model relations:
 * @property Section[] $sections
 */
class Seo extends CActiveRecord
{
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return "seo";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array("name, url", "required"),
			array("name, url, title, keywords, description", "length", "max" => 512),

			array("id, name, url, title, keywords, description", "safe", "on" => "search"),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			"sections" => array(self::HAS_MANY, "Section", "seo_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			"name"        => Yii::t("seo", "Name"),
			"url"         => Yii::t("seo", "Page url"),
			"title"       => Yii::t("seo", "Title"),
			"keywords"    => Yii::t("seo", "Keywords"),
			"description" => Yii::t("seo", "Description"),
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
		$criteria = new CDbCriteria;

		$criteria->compare("id", $this->id);
		$criteria->compare("name", $this->name, true);
		$criteria->compare("url", $this->url, true);
		$criteria->compare("title", $this->title, true);
		$criteria->compare("keywords", $this->keywords, true);
		$criteria->compare("description", $this->description, true);

		return new CActiveDataProvider($this, array(
			"criteria" => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 *
	 * @param string $className active record class name.
	 *
	 * @return Seo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Gets error empty class
	 *
	 * @return string
	 */
	public static function getEmptyClass()
	{
		$seo = Yii::app()->request->getPost("Seo");
		if (!$seo["name"]) {
			return "name-empty";
		} else if (!$seo["url"]) {
			return "url-empty";
		}
		return;
	}
}
