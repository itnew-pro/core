<?php

/**
 * This is the model class for table "block".
 *
 * The followings are the available columns in table 'block':
 * @property integer $id
 * @property integer $type
 * @property string $name
 * @property integer $content_id
 * @property integer $language_id
 *
 * The followings are the available model relations:
 * @property Grid[] $grid
 */
class Block extends CActiveRecord
{

	const TYPE_TEXT = 1;
	const TYPE_IMAGE = 2;
	const TYPE_MENU = 3;
	const TYPE_STAFF = 4;

	/**
	 * Content types
	 *
	 * @var array
	 */
	public $types = array(
		self::TYPE_TEXT => "text",
		self::TYPE_IMAGE => "images",
		self::TYPE_MENU => "menu",
		self::TYPE_STAFF => "staff",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'block';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, name, content_id, language_id', 'required'),
			array('type, content_id, language_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, name, content_id, language_id', 'safe', 'on'=>'search'),
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
			'grid' => array(self::HAS_MANY, 'Grid', 'block_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type' => 'Type',
			'name' => Yii::t("block", "Name"),
			'content_id' => 'Content',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('content_id',$this->content_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Block the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getHtml()
	{
		if (!empty($this->types[$this->type])) {
			list($controller) = Yii::app()->createController($this->types[$this->type]);
			return $controller->getContent($this->content_id);
		}
		return null;
	}

	public function getAllContentBlocks($pageArray = false)
	{
		$blocksArray = array();
		foreach ($this->types as $key => $type) {
			if (($pageArray && in_array($key, $pageArray))|| !$pageArray) {
				$model = ucfirst($type);
				$model = $model::model();
				$blocksArray[$model->getTitle()] = $type;
			}
		}
		return $blocksArray;
	}

	public function getThisPageBlocks()
	{
		return $this->getAllContentBlocks($this->getAllThisPageBlocksTypes());
	}

	private function _getAllThisPageBlocks()
	{
		$blocks = array();

		if (Yii::app()->session["structureId"]) {
			$structure = Structure::model()->findByPk(Yii::app()->session["structureId"]);
			if ($structure) {
				$grids = $structure->grid;
				if ($grids) {
					foreach ($grids as $grid) {
						$block = $grid->block;
						if ($block) {
							$blocks[] = $block;
						}
					}
				}
			}
		}

		return $blocks;
	}

	public function getAllThisPageBlocksTypes()
	{
		$blocksTypes = array();

		$blocks = $this->_getAllThisPageBlocks();
		if ($blocks) {
			foreach ($blocks as $block) {
				$blocksTypes[] = $block->type;
			}
		}

		if ($blocksTypes) {
			$blocksTypes = array_unique($blocksTypes);
		}

		return $blocksTypes;
	}

	public function getAllThisPageBlocksIds()
	{
		$blocksIdsString = null;
		$blocksIds = array();

		$blocks = $this->_getAllThisPageBlocks();
		if ($blocks) {
			foreach ($blocks as $block) {
				$blocksIds[] = $block->id;
			}
		}

		if ($blocksIds) {
			$blocksIds = array_unique($blocksIds);
			sort($blocksIds);
			$blocksIdsString = implode(",", $blocksIds);
		}

		return $blocksIdsString;
	}
}