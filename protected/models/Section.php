<?php

namespace itnew\models;

use itnew\models\Seo;
use itnew\models\Language;
use itnew\models\Structure;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "section".
 *
 * The followings are the available columns in table 'section':
 * @property integer $id
 * @property integer $seo_id
 * @property integer $language_id
 * @property integer $structure_id
 * @property integer $main
 *
 * The followings are the available model relations:
 * @property Seo $seo
 * @property Language $language
 * @property Structure $structure
 */
class Section extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'section';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seo_id, language_id, structure_id, main', 'required'),
			array('seo_id, language_id, structure_id, main', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seo_id, language_id, structure_id, main', 'safe', 'on'=>'search'),
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
			'seo' => array(self::BELONGS_TO, 'itnew\models\Seo', 'seo_id'),
			'language' => array(self::BELONGS_TO, 'itnew\models\Language', 'language_id'),
			'structure' => array(self::BELONGS_TO, 'itnew\models\Structure', 'structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'main' => Yii::t("section", "Home"),
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
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('structure_id',$this->structure_id);
		$criteria->compare('main',$this->main);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Section the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Gets active section
	 *
	 * @return self
	 */
	public function getActive($section)
	{
		if ($section) {
			return $this->with("seo")->find(
				"seo.url = :url AND t.language_id = :language_id",
				array(
					":url" => $section,
					":language_id" => Language::getActiveId()
				)
			);
		}
		return $this->find(
			"main = :main AND language_id = :language_id",
			array(":main" => 1, ":language_id" => Language::getActiveId())
		);
	}

	/**
	 * Saves form from subpanel
	 *
	 * @return string error class
	 */
	public function saveForm($seo)
	{
		$section = Yii::app()->request->getPost("Section");

		if ($section["id"]) {
			$model = $this->findByPk($section["id"]);
		} else {
			$model = new self;
			$model->seo = new Seo;
			$model->main = 0;
		}

		if ($model->isNewRecord) {
			if ($this->with("seo")->find(
				"seo.url = :url",
				array(":url" => $seo["url"])
			)) {
				return $errorClass = "url-exist";
			}
		} else if ($this->with("seo")->find(
			"seo.url = :url AND t.id != :id",
			array(":url" => $seo["url"], ":id" => $model->id)
		)) {
			return $errorClass = "url-exist";
		} 

		$model->seo->attributes = $seo;
		$model->seo->save();

		if ($model->isNewRecord) {
			$model->seo_id = $model->seo->id;

			$model->structure = new Structure;
			$model->structure->width = Structure::WIDTH;
			$model->structure->save();
			$model->structure_id = $model->structure->id;

			$model->language_id = Language::getActiveId();
		}

		if ($section["main"]) {
			$sectionModels = $this->findAll();
			if ($sectionModels) {
				foreach ($sectionModels as $sectionModel) {
					$sectionModel->main = 0;
					$sectionModel->save();
				}
			}
			$model->main = 1;
		}
		$model->save();

		return;
	}

	protected function afterDelete()
	{
		if ($this->seo) {
			$this->seo->delete();
		}
		if ($this->structure) {
			$this->structure->delete();
		}
		return parent::afterDelete();
	}

	public function duplicate()
	{
		$transaction = Yii::app()->db->beginTransaction();

		if ($this->seo && $this->structure) {
			$seoCopy = new Seo;
			$seoCopy->name = $this->seo->name . " - " . Yii::t("common", "copy");
			$seoCopy->url = $this->seo->url . "-copy";
			$seoCopy->title = $this->seo->title;
			$seoCopy->keywords = $this->seo->keywords;
			$seoCopy->description = $this->seo->description;

			if ($seoCopy->save()) {
				$structureCopy = new Structure;
				$structureCopy->width = $this->structure->width;
				$structureCopy->size = $this->structure->size;

				if ($structureCopy->save()) {
					if ($this->structure->grid) {
						foreach ($this->structure->grid as $grid) {
							$gridCopy = new Grid;
							$gridCopy->attributes = $grid->attributes;
							$gridCopy->structure_id = $structureCopy->id;
							$gridCopy->id = null;
							$gridCopy->save();
						}
					}

					$sectionCopy = new Section;
					$sectionCopy->seo_id = $seoCopy->id;
					$sectionCopy->structure_id = $structureCopy->id;
					$sectionCopy->language_id = $this->language_id;
					$sectionCopy->main = 0;

					if ($sectionCopy->save()) {
						$transaction->commit();
						return true;
					}
				}
			}
		}

		$transaction->rollback();
		return false;
	}

	public function getSeo()
	{
		if ($this->seo) {
			return $this->seo;
		}
		return new Seo;
	}

	public function getLink()
	{
		if ($this->seo) {
			$url = $this->getUrl();

			$active = null;
			if (Yii::app()->request->url === $url) {
				$active = "class=\"active\"";
			}

			return "<a href=\"{$url}\" {$active}>{$this->seo->name}</a>";
		}

		return;
	}

	public function beforeDelete()
	{
		if ($this->menuContent) {
			foreach ($this->menuContent as $menuContent) {
				$menuContent->delete();
			}
		}
		return parent::beforeDelete();
	}

	public function getUrl()
	{
		return Yii::app()->createUrl("site/index", array(
			"language" => Yii::app()->language,
			"section" => $this->seo->url,
		));
	}
}
