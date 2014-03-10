<?php

/**
 * This is the model class for table "records".
 *
 * The followings are the available columns in table 'records':
 * @property integer $id
 * @property integer $date
 * @property integer $is_detail
 * @property integer $cover
 * @property integer $images
 * @property integer $structure_id
 *
 * The followings are the available model relations:
 * @property Structure $structure
 * @property Images $cover
 * @property Images $images
 * @property RecordsContent[] $recordsContent
 */
class Records extends CActiveRecord
{

	const COVER_WIDTH = 120;
	const COVER_HEIGHT = 120;

	const IMAGES_WIDTH = 1000;
	const IMAGES_HEIGHT = 1000;
	const IMAGES_THUMB_WIDTH = 120;
	const IMAGES_THUMB_HEIGHT = 120;

	const DATE_UNDATE = 0;
	const DATE_DDMMYYYY = 1;

	public $isCover = true;
	public $isImages = true;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'records';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, is_detail, cover, images, structure_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date, is_detail, cover, images, structure_id', 'safe', 'on'=>'search'),
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
			'structure' => array(self::BELONGS_TO, 'Structure', 'structure_id'),
			'coverRelation' => array(self::BELONGS_TO, 'Images', 'cover'),
			'imagesRelation' => array(self::BELONGS_TO, 'Images', 'images'),
			'recordsContent' => array(self::HAS_MANY, 'RecordsContent', 'records_id'),
			"block" => array(
				self::HAS_ONE,
				'Block',
				'content_id',
				"condition" => "block.type = :type",
				"params" => array(
					":type" => Block::TYPE_RECORDS,
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
			'date' => Yii::t("records", "Date"),
			'is_detail' => Yii::t("records", "Detailed description"),
			'isCover' => Yii::t("records", "Cover"),
			'isImages' => Yii::t("records", "Images"),
			'structure_id' => 'Structure',
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
		$criteria->compare('date',$this->date);
		$criteria->compare('is_detail',$this->is_detail);
		$criteria->compare('cover',$this->cover);
		$criteria->compare('images',$this->images);
		$criteria->compare('structure_id',$this->structure_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Records the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getTitle()
	{
		return Yii::t("records", "Records");
	}

	public function getAllContentBlocks($notIn = null)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "language_id = :language_id AND type = :type";
		$criteria->params = array(
			":language_id" => Language::getActiveId(),
			":type" => Block::TYPE_RECORDS
		);

		if ($notIn) {
			$criteria->condition .= " AND id IN ({$notIn})";
		}

		return Block::model()->findAll($criteria);
	}

	public function getBlock()
	{
		if ($this->block) {
			return $this->block;
		}
		return new Block;
	}

	public function getDateTypes()
	{
		return array(
			self::DATE_UNDATE => Yii::t("records", "undated"),
			self::DATE_DDMMYYYY => Yii::t("records", "dd.mm.yyyy"),
		);
	}

	public function getCover()
	{
		if ($this->coverRelation) {
			return $this->coverRelation;
		}

		$model = new Images;
		$model->width = self::COVER_WIDTH;
		$model->height = self::COVER_HEIGHT;

		return $model;
	}

	public function getCoverClone()
	{
		$model = clone $this->getCover();
		$model->id = null;
		$model->isNewRecord = true;

		return $model;
	}

	public function getImages()
	{
		if ($this->imagesRelation) {
			return $this->imagesRelation;
		}

		$model = new Images;
		$model->view = Images::TYPE_LITEBOX;
		$model->width = self::IMAGES_WIDTH;
		$model->height = self::IMAGES_HEIGHT;
		$model->thumb_width = self::IMAGES_THUMB_WIDTH;
		$model->thumb_height = self::IMAGES_THUMB_HEIGHT;

		return $model;
	}

	public function getImagesClone()
	{
		$model = clone $this->getImages();
		$model->id = null;
		$model->isNewRecord = true;
		
		return $model;
	}

	public function saveSettings()
	{
		$recordsPost = Yii::app()->request->getPost("Records");

		if (Yii::app()->request->getQuery("id")) {
			if ($model = $this->findByPk(Yii::app()->request->getQuery("id"))) {
				if ($block = $model->getBlock()) {
					$model->attributes = $recordsPost;
					$block->attributes = Yii::app()->request->getPost("Block");

					if ($model->coverRelation) {
						$cover = $model->coverRelation;
					} else {
						$cover = new Images;
					}
					$cover->attributes = Yii::app()->request->getPost("Cover");

					if ($model->imagesRelation) {
						$images = $model->imagesRelation;
					} else {
						$images = new Images;
					}
					$images->attributes = Yii::app()->request->getPost("Images");

					$transaction = Yii::app()->db->beginTransaction();

					if ($cover->save() && $images->save()) {
						if ($block->save()) {
							if ($recordsPost["isCover"]) {
								$model->cover = $cover->id;
							} else {
								$model->cover = null;
							}

							if ($recordsPost["isImages"]) {
								$model->images = $images->id;
							} else {
								$model->images = null;
							}

							if ($model->save()) {
								if (!$recordsPost["isCover"]) {
									$cover->delete();
								}
								if (!$recordsPost["isImages"]) {
									$images->delete();
								}
								$transaction->commit();
								return $model;
							}
						}
					}

					$transaction->rollback();
				}
			}
		} 

		else {
			$model = new self;
			$block = new Block;
			$cover = new Images;
			$images = new Images;

			$model->attributes = $recordsPost;
			$block->attributes = Yii::app()->request->getPost("Block");
			$cover->attributes = Yii::app()->request->getPost("Cover");
			$images->attributes = Yii::app()->request->getPost("Images");

			$transaction = Yii::app()->db->beginTransaction();
			if ($cover->save() && $images->save()) {
				if ($recordsPost["isCover"]) {
					$model->cover = $cover->id;
				} else {
					$cover->delete();
				}

				if ($recordsPost["isImages"]) {
					$model->images = $images->id;
				} else {
					$images->delete();
				}

				if ($model->save()) {
					$block->content_id = $model->id;
					$block->type = Block::TYPE_RECORDS;
					$block->language_id = Language::getActiveId();
					if ($block->save()) {
						$transaction->commit();
						return $model;
					}
				}
			}
			$transaction->rollback();
		}

		return null;
	}

	public function init()
	{
		if ($this->isNewRecord) {
			$this->date = self::DATE_DDMMYYYY;
			$this->is_detail = true;
		}
	}

	public function hasCover()
	{
		if (!$this->isNewRecord) {
			if (!$this->cover) {
				return false;
			}
		}

		return true;
	}

	public function hasImages()
	{
		if (!$this->isNewRecord) {
			if (!$this->images) {
				return false;
			}
		}

		return true;
	}
}