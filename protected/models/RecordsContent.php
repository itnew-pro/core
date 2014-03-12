<?php

/**
 * This is the model class for table "records_content".
 *
 * The followings are the available columns in table 'records_content':
 * @property integer $id
 * @property integer $records_id
 * @property integer $cover
 * @property string $date
 * @property integer $seo_id
 * @property integer $images
 * @property integer $text
 * @property integer $description
 * @property integer $sort
 * @property integer is_published
 *
 * The followings are the available model relations:
 * @property Images $cover
 * @property Text $description
 * @property Images $images
 * @property Records $records
 * @property Seo $seo
 * @property Text $text
 */
class RecordsContent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'records_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('records_id, seo_id, images, text, description', 'required'),
			array('records_id, cover, seo_id, images, text, description, sort', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, records_id, cover, date, seo_id, images, text, description, sort, is_published', 'safe', 'on'=>'search'),
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
			'coverRelation' => array(self::BELONGS_TO, 'Images', 'cover'),
			'descriptionRelation' => array(self::BELONGS_TO, 'Text', 'description'),
			'imagesRelation' => array(self::BELONGS_TO, 'Images', 'images'),
			'records' => array(self::BELONGS_TO, 'Records', 'records_id'),
			'seo' => array(self::BELONGS_TO, 'Seo', 'seo_id'),
			'textRelation' => array(self::BELONGS_TO, 'Text', 'text'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'records_id' => 'Records',
			'cover' => 'Cover',
			'date' => 'Date',
			'seo_id' => 'Seo',
			'images' => 'Images',
			'text' => 'Text',
			'description' => 'Description',
			'sort' => 'Sort',
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
		$criteria->compare('records_id',$this->records_id);
		$criteria->compare('cover',$this->cover);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('seo_id',$this->seo_id);
		$criteria->compare('images',$this->images);
		$criteria->compare('text',$this->text);
		$criteria->compare('description',$this->description);
		$criteria->compare('sort',$this->sort);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RecordsContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getSeo()
	{
		if ($this->seo) {
			return $this->seo;
		}

		return new Seo;
	}

	public function saveAdd()
	{
		$errorClass = null;
		$id = 0;

		$seoPost = Yii::app()->request->getPost("Seo");
		$recordsContent = Yii::app()->request->getPost("RecordsContent");

		if ($seoPost && $recordsContent) {
			if (!$seoPost["name"]) {
				$errorClass = "error-name-empty";
			} else if (!$seoPost["url"]) {
				$errorClass = "error-url-empty";
			} else {
				$criteria = new CDbCriteria;
				$criteria->with = "seo";
				$criteria->condition = "seo.url = :url AND t.records_id = :records_id";
				$criteria->params = array(
					"url" => $seoPost["url"],
					"records_id" => $recordsContent["records_id"],
				);
				if ($this->find($criteria)) {
					$errorClass = "error-url-exist";
				}
			}

			if (!$errorClass) {
				$record = Records::model()->findByPk($recordsContent["records_id"]);
				if ($record) {
					$transaction = $this->dbConnection->beginTransaction();
					$transactionSuccess = false;

					$seo = new Seo;
					$seo->attributes = $seoPost;
					$cover = $record->getCoverClone();
					$images = $record->getImagesClone();
					$text = Text::model()->getDefaultTextModel();
					$description = Text::model()->getDefaultDescriptionModel();

					if (
						$seo->save()
						&& $cover->save()
						&& $images->save()
						&& $text->save()
						&& $description->save()
					) { 
						$model = new self;
						$model->records_id = $record->id;
						$model->cover = $cover->id;
						$model->date = date("Y-m-d H:i:s", time());
						$model->seo_id = $seo->id;
						$model->images = $images->id;
						$model->text = $text->id;
						$model->description = $description->id;
						$model->sort = $this->_getNewSort();
						if ($model->save()) {
							$id = $model->id;
							$transactionSuccess = true;
						} else {
							return $model->text;
						}
					}

					if ($transactionSuccess) {
						$transaction->commit();

					} else {
						$transaction->rollback();
					}
				}
			}
		}

		return array(
			"errorClass" => $errorClass,
			"id"         => $id,
			"recordsId"  => $recordsContent["records_id"],
		);
	}

	private function _getNewSort()
	{
		return 10;
	}

	protected function afterDelete()
	{
		if ($this->coverRelation) {
			$this->coverRelation->delete();
		}
		if ($this->seo) {
			$this->seo->delete();
		}
		if ($this->imagesRelation) {
			$this->imagesRelation->delete();
		}
		if ($this->textRelation) {
			$this->textRelation->delete();
		}
		if ($this->descriptionRelation) {
			$this->descriptionRelation->delete();
		}

		return parent::afterDelete();
	}

	public function getCover()
	{
		if ($this->coverRelation) {
			return $this->coverRelation;
		}

		return new Images;
	}

	public function getImages()
	{
		if ($this->imagesRelation) {
			return $this->imagesRelation;
		}

		return new Images;
	}

	public function getDescription()
	{
		if ($this->descriptionRelation) {
			return $this->descriptionRelation;
		}

		return new Text;
	}

	public function getText()
	{
		if ($this->textRelation) {
			return $this->textRelation;
		}

		return new Text;
	}

	public function getWindowDate()
	{
		if ($this->date) {
			$timestamp = CDateTimeParser::parse($this->date, "yyyy-MM-dd HH:mm:ss");
			if ($timestamp) {
				return date("d.m.Y", $timestamp);
			}
		}

		return null;
	}
}