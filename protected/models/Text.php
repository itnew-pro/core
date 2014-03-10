<?php

/**
 * This is the model class for table "text".
 *
 * The followings are the available columns in table 'text':
 * @property integer $id
 * @property integer $rows
 * @property integer $editor
 * @property integer $tag
 * @property string $text
 */
class Text extends CActiveRecord
{

	const DEFAULT_TEXT_SIZE = 15;
	const DEFAULT_DESCRIPTION_SIZE = 5;

	public $tagList = array(
		0 => "div",
		1 => "h1",
		2 => "h2",
		3 => "h3",
		4 => "h4",
		5 => "h5",
		6 => "h6",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'text';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rows, editor, tag', 'numerical', 'integerOnly' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, rows, editor, tag, text', 'safe', 'on'=>'search'),
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
			"block" => array(
				self::HAS_ONE,
				'Block',
				'content_id',
				"condition" => "block.type = :type",
				"params" => array(
					":type" => Block::TYPE_TEXT,
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
			'rows' => Yii::t("text", "Rows"),
			'editor' => Yii::t("text", "Editor"),
			'tag' => Yii::t("text", "Tag"),
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
		$criteria->compare('rows',$this->rows);
		$criteria->compare('editor',$this->editor);
		$criteria->compare('tag',$this->tag);
		$criteria->compare('text',$this->text,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Text the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get HTML tag's name
	 *
	 * @return string
	 */
	public function getTag()
	{
		if (!empty($this->tagList[$this->tag])) {
			return $this->tagList[$this->tag];
		}
		return $this->tagList[0];
	}

	public function getTitle()
	{
		return Yii::t("text", "Text");
	}

	public function getAllContentBlocks($notIn = null)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "language_id = :language_id AND type = :type";
		$criteria->params = array(
			":language_id" => Language::getActiveId(),
			":type" => Block::TYPE_TEXT
		);

		if ($notIn) {
			$criteria->condition .= " AND id IN ({$notIn})";
		}

		return Block::model()->findAll($criteria);
	}

	public function getThisPageBlocks()
	{
		return $this->getAllContentBlocks(Block::model()->getAllThisPageBlocksIds());
	}

	public function getEditorClass()
	{
		if ($this->editor) {
			return " tinymce";
		}
		return null;
	}

	public function saveSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->findByPk(Yii::app()->request->getQuery("id"))) {
				if ($block = $model->getBlock()) {
					$model->attributes = Yii::app()->request->getPost("Text");
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
			$model->attributes = Yii::app()->request->getPost("Text");
			$block->attributes = Yii::app()->request->getPost("Block");

			$transaction = Yii::app()->db->beginTransaction();
			if ($model->save()) {
				$block->content_id = $model->id;
				$block->type = Block::TYPE_TEXT;
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

	public function getRowsList()
	{
		$rows = array();
		$rows[0] = Yii::t("text", "Max");
		for ($i = 1; $i < 21; $i++) { 
			$rows[$i] = $i;
		}
		return $rows;
	}

	public function getBlock()
	{
		if ($this->block) {
			return $this->block;
		}
		return new Block;
	}

	protected function afterDelete()
	{
		if ($this->block) {
			$this->block->delete();
		}
		return parent::afterDelete();
	}

	public function duplicate()
	{
		$transaction = Yii::app()->db->beginTransaction();

		$textCopy = new Text;
		$textCopy->rows = $this->rows;
		$textCopy->editor = $this->editor;
		$textCopy->tag = $this->tag;
		$textCopy->text = $this->text;
	
		if ($textCopy->save()) {
				
			$blockCopy = new Block;
			$blockCopy->type = $this->block->type;
			$blockCopy->name = $this->block->name . " - " . Yii::t("common", "copy");
			$blockCopy->content_id = $textCopy->id;
			$blockCopy->language_id = $this->block->language_id;

			if ($blockCopy->save()) {
				$transaction->commit();
				return true;
			}
		}

		$transaction->rollback();
		return false;
	}

	public function saveContent()
	{
		if ($text = Yii::app()->request->getPost("Text")) {
			$this->text = $text["text"];
			$this->save();
		}
	}

	public function getDefaultTextModel()
	{
		$model = new self;
		$model->rows = self::DEFAULT_TEXT_SIZE;
		$model->editor = 1;

		return $model;
	}

	public function getDefaultDescriptionModel()
	{
		$model = new self;
		$model->rows = self::DEFAULT_DESCRIPTION_SIZE;

		return $model;
	}
}