<?php

namespace itnew\models;

use itnew\models\MenuContent;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property MenuContent[] $menuContent
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type', 'safe', 'on'=>'search'),
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
			'menuContent' => array(self::HAS_MANY, 'itnew\models\MenuContent', 'menu_id', "order" => "sort"),
			"block" => array(
				self::HAS_ONE,
				'Block',
				'content_id',
				"condition" => "block.type = :type",
				"params" => array(
					":type" => Block::TYPE_MENU,
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
			'type' => 'Type',
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
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTitle()
	{
		return Yii::t("menu", "Menu");
	}

	public function getAllContentBlocks()
	{
		$model = new Block;
		return $model->findAll(
			"t.language_id = :language_id AND type = :type",
			array(
				":language_id" => Language::getActiveId(),
				":type" => Block::TYPE_MENU,
			)
		);
	}

	public function getBlock()
	{
		if ($this->block) {
			return $this->block;
		}
		return new Block;
	}

	const TYPE_VERTICAL = 0;
	const TYPE_HORIZONTAL = 1;

	private $_typeList = array(
		self::TYPE_VERTICAL => "vertical",
		self::TYPE_HORIZONTAL => "horizontal",
	);

	public function getType()
	{
		if (!empty($this->_typeList[$this->type])) {
			return $this->_typeList[$this->type];
		}

		return null;
	}

	public function getTypeListLabels()
	{
		$list = array();
		$list[self::TYPE_VERTICAL] = Yii::t("menu", "Vertical");
		$list[self::TYPE_HORIZONTAL] = Yii::t("menu", "Horizontal");
		return $list;
	}

	public function saveSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->findByPk(Yii::app()->request->getQuery("id"))) {
				if ($block = $model->getBlock()) {
					$model->attributes = Yii::app()->request->getPost("Menu");
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
			$model->attributes = Yii::app()->request->getPost("Menu");
			$block->attributes = Yii::app()->request->getPost("Block");

			$transaction = Yii::app()->db->beginTransaction();

			if ($model->save()) {
				$block->content_id = $model->id;
				$block->type = Block::TYPE_MENU;
				$block->language_id = Language::getActiveId();

				if ($block->save()) {
					$transaction->commit();
					return $model;
				}
			}
			$transaction->rollback();
		}

		return;
	}

	public function beforeDelete()
	{
		if ($this->menuContent) {
			foreach ($this->menuContent as $model) {
				$model->delete();
			}
		}
		return parent::beforeDelete();
	}

	protected function afterDelete()
	{
		if ($this->block) {
			$this->block->delete();
		}
		return parent::afterDelete();
	}

	public function getUnusedSections()
	{
		$list = array();
		$sections = Section::model()->findAll();
		if ($sections) {
			foreach ($sections as $model) {
				$list[$model->id] = $model->seo->name;
			}
		}

		return $list;
	}

	public function getHtml()
	{
		$list = array();

		if ($this->menuContent) {
			foreach ($this->menuContent as $menu) {
				$link = null;

				if ($menu->section_id) {
					$section = Section::model()->findByPk($menu->section_id);
					if ($section) {
						$link = $section->getLink();
					}
				}

				if ($link) {
					$list[$menu->parent_id][] = array(
						"id" => $menu->id,
						"link" => $link,
					);
				}
			}
		}

		if ($list) {
			return $this->_createMenuTree($list);
		}

		return;
	}

	private function _createMenuTree($list, $parentId = 0)
	{
		$html = "";

		if (!empty($list[$parentId])) {
			$html .= "<ul>";

			foreach ($list[$parentId] as $item) {
				$html .= "<li>";
				$html .= $item["link"];
				$html .= $this->_createMenuTree($list, $item["id"]);
				$html .= "</li>";
			}

			$html .= "</ul>";
		}

		return $html;
	}
}
