<?php

/**
 * This is the model class for table "images".
 *
 * The followings are the available columns in table 'images':
 * @property integer $id
 * @property integer $many
 * @property integer $view
 * @property integer $width
 * @property integer $height
 * @property integer $thumb_width
 * @property integer $thumb_height
 *
 * The followings are the available model relations:
 * @property ImagesContent[] $imagesContent
 */
class Images extends CActiveRecord
{
	public $imageContentIds = "";

	private $_views = array(
		0 => "simple",
		1 => "litebox",
		2 => "slider",
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'images';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('many, view, width, height, thumb_width, thumb_height', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, many, view, width, height, thumb_width, thumb_height', 'safe', 'on'=>'search'),
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
			'imagesContent' => array(self::HAS_MANY, 'ImagesContent', 'images_id', "order" => "sort"),
			"block" => array(
				self::HAS_ONE,
				'Block',
				'content_id',
				"condition" => "block.type = :type",
				"params" => array(
					":type" => Block::TYPE_IMAGE,
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
			'view' => Yii::t("images", "View"),
			'width' => Yii::t("images", "Max width"),
			'height' => Yii::t("images", "Max height"),
			'thumb_width' => Yii::t("images", "Thumbnail width"),
			'thumb_height' => Yii::t("images", "Thumbnail height"),
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
		$criteria->compare('many',$this->many);
		$criteria->compare('view',$this->view);
		$criteria->compare('width',$this->width);
		$criteria->compare('height',$this->height);
		$criteria->compare('thumb_width',$this->thumb_width);
		$criteria->compare('thumb_height',$this->thumb_height);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Images the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTitle()
	{
		return Yii::t("images", "Images");
	}

	public function getAllContentBlocks()
	{
		$model = new Block;
		return $model->findAll(
			"t.language_id = :language_id AND type = :type",
			array(
				":language_id" => Language::getActiveId(),
				":type" => Block::TYPE_IMAGE,
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

	public function getViewList()
	{
		$list = array();
		$list[0] = Yii::t("images", "Simple images");
		$list[1] = Yii::t("images", "Increasing thumbnail");
		$list[2] = Yii::t("images", "Slider");
		return $list;
	}

	public function saveSettings()
	{
		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->findByPk(Yii::app()->request->getQuery("id"))) {
				if ($block = $model->getBlock()) {
					$model->attributes = Yii::app()->request->getPost("Images");
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
			$model->attributes = Yii::app()->request->getPost("Images");
			$block->attributes = Yii::app()->request->getPost("Block");

			$transaction = Yii::app()->db->beginTransaction();

			if ($model->save()) {
				$block->content_id = $model->id;
				$block->type = Block::TYPE_IMAGE;
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
		if ($this->imagesContent) {
			foreach ($this->imagesContent as $model) {
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

	public function saveContent()
	{
		if ($images = Yii::app()->request->getPost("Images")) {
			if (!empty($images["imageContentIds"])) {
				$sort = 1;
				foreach (explode(",", $images["imageContentIds"]) as $pk) {
					if ($pk) {
						if ($model = ImagesContent::model()->findByPk($pk)) {
							$model->sort = $sort;
							$model->save();
							$sort++;
						}
					}
				}
			}
		}
	}

	public function getTemplateName()
	{
		if (!empty($this->_views[$this->view])) {
			return "_" . $this->_views[$this->view];
		}

		return  "_" . $this->_views[0];
	}
}
