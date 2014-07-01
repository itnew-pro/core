<?php

namespace itnew\models;

use itnew\models\Images;
use itnew\models\Text;
use itnew\models\Records;
use itnew\models\Seo;
use CActiveRecord;
use Yii;
use CDbCriteria;
use CDateTimeParser;

/**
 * Файл класса RecordsContent.
 *
 * Модель для таблицы "records_content"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int     $id                  идентификатор
 * @property int     $records_id          идентификатор записей
 * @property int     $cover               идентификатор обложки
 * @property string  $date                дата
 * @property int     $seo_id              идентификатор СЕО
 * @property int     $images              идентификатор изображений
 * @property int     $text                идентификатор текста
 * @property int     $description         идентификатор описания
 * @property int     $sort                сортировка
 * @property int     is_published         опубликована ли запись
 *
 * @property Images  $coverRelation       модель обложки
 * @property Text    $descriptionRelation модель описания
 * @property Images  $imagesRelation      модель изображений
 * @property Records $records             модель записей
 * @property Seo     $seo                 модель СЕО
 * @property Text    $textRelation        модель текста
 */
class RecordsContent extends CActiveRecord
{

	/**
	 * Шаг сортировки
	 *
	 * @var int
	 */
	const SORT_STEP = 10;

	/**
	 * Отступ для обложки
	 *
	 * @var int
	 */
	const COVER_MARGIN = 15;

	/**
	 * Максимальная сортировка
	 *
	 * @var int
	 */
	public $maxSort = 0;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'records_content';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array("date", "length", "max" => 128),
			array('records_id, seo_id, images, text, description', 'required'),
			array(
				'id, is_published, maxSort, records_id, cover, seo_id, images, text, description, sort',
				'numerical',
				'integerOnly' => true
			),
		);
	}

	/**
	 * Возвращает связи между объектами
	 *
	 * @return string[]
	 */
	public function relations()
	{
		return array(
			'coverRelation'       => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'cover'
			),
			'descriptionRelation' => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'description'
			),
			'imagesRelation'      => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'images'
			),
			'records'             => array(
				self::BELONGS_TO,
				'itnew\models\Records',
				'records_id'
			),
			'seo'                 => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
			),
			'textRelation'        => array(
				self::BELONGS_TO,
				'itnew\models\Text',
				'text'
			),
		);
	}

	/**
	 * Возвращает подписей полей
	 *
	 * @return string[]
	 */
	public function attributeLabels()
	{
		return array(
			'date'         => Yii::t("records", "Date"),
			'is_published' => Yii::t("records", "Published"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return RecordsContent
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает модель СЕО
	 *
	 * @return Seo
	 */
	public function getSeo()
	{
		if ($this->seo) {
			return $this->seo;
		}

		return new Seo;
	}

	/**
	 * Добавляет запись
	 *
	 * @param string[] $post      поля модели переданные через POST
	 * @param string[] $seoPost   поля модели СЕО переданные через POST
	 *
	 * @return bool|string[]
	 */
	public function saveAdd($post, $seoPost)
	{
		$errorClass = null;
		$id = 0;

		if (!$seoPost["name"]) {
			return array(
				"errorClass" => "error-name-empty",
				"id"         => $id,
				"recordsId"  => $post["records_id"],
			);
		}

		if (!$seoPost["url"]) {
			return array(
				"errorClass" => "error-url-empty",
				"id"         => $id,
				"recordsId"  => $post["records_id"],
			);
		}

		$criteria = new CDbCriteria;
		$criteria->with = "seo";
		$criteria->condition = "seo.url = :url AND t.records_id = :records_id";
		$criteria->params["url"] = $seoPost["url"];
		$criteria->params["records_id"] = $post["records_id"];
		if ($this->find($criteria)) {
			return array(
				"errorClass" => "error-url-exist",
				"id"         => $id,
				"recordsId"  => $post["records_id"],
			);
		}

		$record = Records::model()->findByPk($post["records_id"]);
		if (!$record) {
			return false;
		}

		$transaction = $this->dbConnection->beginTransaction();

		$seo = new Seo;
		$seo->attributes = $seoPost;
		$cover = $record->getCoverClone();
		$images = $record->getImagesClone();
		$text = Text::model()->getDefaultTextModel();
		$description = Text::model()->getDefaultDescriptionModel();

		if (
			!$seo->save()
			|| !$cover->save()
			|| !$images->save()
			|| !$text->save()
			|| !$description->save()
		) {
			return false;
		}

		$model = new self;
		$model->records_id = $record->id;
		$model->cover = $cover->id;
		$model->date = date("Y-m-d H:i:s", time());
		$model->seo_id = $seo->id;
		$model->images = $images->id;
		$model->text = $text->id;
		$model->description = $description->id;
		$model->sort = $this->_getNewSort($record->id);

		if (!$model->save()) {
			$transaction->rollback();
			return false;
		}

		$transaction->commit();

		return array(
			"errorClass" => $errorClass,
			"id"         => $model->id,
			"recordsId"  => $post["records_id"],
		);
	}

	/**
	 * Получает сортировку
	 *
	 * @param int $recordsШd идентификатор записей
	 *
	 * @return int
	 */
	private function _getNewSort($recordsId)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'MAX(t.sort) as maxSort';
		$criteria->condition = 't.records_id = :records_id';
		$criteria->params["records_id"] = $recordsId;

		$model = $this->find($criteria);
		if ($model) {
			return $model['maxSort'] + self::SORT_STEP;
		}

		return 0;
	}

	/**
	 * Вызывается после удаления модели
	 *
	 * @return void
	 */
	protected function afterDelete()
	{
		parent::afterDelete();

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
	}

	/**
	 * Получает модель обложки
	 *
	 * @return Images
	 */
	public function getCover()
	{
		if ($this->coverRelation) {
			return $this->coverRelation;
		}

		return new Images;
	}

	/**
	 * Получает модель изображений
	 *
	 * @return Images
	 */
	public function getImages()
	{
		if ($this->imagesRelation) {
			return $this->imagesRelation;
		}

		return new Images;
	}

	/**
	 * Получает модель описания
	 *
	 * @return Text
	 */
	public function getDescription()
	{
		if ($this->descriptionRelation) {
			return $this->descriptionRelation;
		}

		return new Text;
	}

	/**
	 * Получает модель текста
	 *
	 * @return Text
	 */
	public function getText()
	{
		if ($this->textRelation) {
			return $this->textRelation;
		}

		return new Text;
	}

	/**
	 * Получает дату для окна
	 *
	 * @return string
	 */
	public function getWindowDate()
	{
		if (!$this->date) {
			return null;
		}

		$timestamp = CDateTimeParser::parse($this->date, "yyyy-MM-dd HH:mm:ss");
		if ($timestamp) {
			return date("d.m.Y", $timestamp);
		}

		return null;
	}

	public function saveForm($post, $seo, $description, $images, $text)
	{
		$model = $this->findByPk($post["id"]);
		if (!$model) {
			return 0;
		}

		$transaction = Yii::app()->db->beginTransaction();

		$model->is_published = $post["is_published"];
		if (!empty($post["date"])) {
			$timestamp = CDateTimeParser::parse($post["date"], "dd.MM.yyyy");
			if ($timestamp) {
				$model->date = date("Y-m-d H:i:s", $timestamp);
			}
		}

		if (!$model->save() || !$model->seo) {
			$transaction->rollback();
			return 0;
		}

		$model->seo->attributes = $seo;
		if (!$model->seo->save()) {
			$transaction->rollback();
			return 0;
		}

		if ($text && $model->textRelation) {
			$model->textRelation->attributes = $text;
			$model->textRelation->save();
		}

		if ($description && $model->descriptionRelation) {
			$model->descriptionRelation->attributes = $description;
			$model->descriptionRelation->save();
		}

		if ($images && $model->imagesRelation) {
			$model->imagesRelation->saveContent($images);
		}

		$transaction->commit();

		return $model->records->id;
	}

	/**
	 * Получает URL
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->records->getUrl() . "/" . $this->seo->url . "/";
	}

	/**
	 * Получает модель по URL и идентификатору записей
	 *
	 * @param string $url       URL
	 * @param int    $recordsId идентификатор записей
	 *
	 * @return RecordsContent
	 */
	public function getModelBySeoUrlAndRecordsId($url, $recordsId)
	{
		if (!$url || !$recordsId) {
			return null;
		}

		$criteria = new CDbCriteria;
		$criteria->with = array("seo");
		$criteria->condition = "seo.url = :url AND t.records_id = :records_id";
		$criteria->params["url"] = $url;
		$criteria->params["records_id"] = $recordsId;

		return $this->find($criteria);
	}
}