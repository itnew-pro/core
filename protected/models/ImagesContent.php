<?php

namespace itnew\models;

use itnew\models\Images;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;
use CUploadedFile;

/**
 * Файл класса ImagesContent.
 *
 * Модель для таблицы "images_content"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int    $id        идентификатор
 * @property string $file      путь до файла
 * @property int    $images_id идентификатор изображений
 * @property int    $sort      сортировка
 * @property string $alt       описание
 * @property string $link      ссылка
 *
 * @property Images $images    модель изображений
 */
class ImagesContent extends CActiveRecord
{

	/**
	 * Стандартная ширина
	 *
	 * @var int
	 */
	const SIZE_WIDTH = 1280;

	/**
	 * Стандартная высота
	 *
	 * @var int
	 */
	const SIZE_HEIGHT = 800;

	/**
	 * Стандартная ширина миниатюрки
	 *
	 * @var int
	 */
	const SIZE_THUMB_WIDTH = 200;

	/**
	 * Стандартная высота миниатюрки
	 *
	 * @var int
	 */
	const SIZE_THUMB_HEIGHT = 200;

	/**
	 * Ширина миниатюрки для окна
	 *
	 * @var int
	 */
	const SIZE_WINDOW_WIDTH = 150;

	/**
	 * Высота миниатюрки для окна
	 *
	 * @var int
	 */
	const SIZE_WINDOW_HEIGHT = 113;

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'images_content';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('images_id, sort', 'numerical', 'integerOnly' => true),
			array('alt, link', 'length', 'max' => 255),
			array('file', 'file', 'types' => 'jpg, jpeg, gif, png', 'allowEmpty' => true),
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
			'images' => array(
				self::BELONGS_TO,
				'itnew\models\Images',
				'images_id'
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
			'id'        => 'ID',
			'file'      => 'File',
			'images_id' => 'Images',
			'sort'      => 'Sort',
			'alt'       => 'Alt',
			'link'      => 'Link',
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return ImagesContent|null
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Загружает и обрезает изображение
	 *
	 * @param Images $images объект изображений
	 *
	 * @return ImagesContent
	 */
	public function upload(Images $images = null)
	{
		if (!$images) {
			return null;
		}

		$name = substr(md5(microtime(1)), 0, 10) . ".jpg";

		$sizes = $this->_getSizes($images);

		$model = new self;
		$model->images_id = $images->id;
		$model->file = $name;
		if (
			$model->save()
			&& CUploadedFile::getInstance($model, "file")->saveAs($model->_getUploadDir() . $name)
		) {
			$image = Yii::app()->image->load($model->_getUploadDir() . $name);
			$image->resize($sizes["width"], $sizes["height"])->quality(90)->save(
				$model->_getUploadDir() . "view_" . $name
			);
			$image->resize($sizes["thumbWidth"], $sizes["thumbHeight"])->quality(90)->save(
				$model->_getUploadDir() . "thumb_" . $name
			);
			$image->resize($sizes["windowWidth"], $sizes["windowHeight"])->quality(90)->save(
				$model->_getUploadDir() . "window_" . $name
			);

			return $model;
		}

		return null;
	}

	/**
	 * Получает путь до папки с изображениями
	 * Используется для загрузки
	 *
	 * @return string
	 */
	private function _getUploadDir()
	{
		return Yii::app()->params["staticDir"] .
			DIRECTORY_SEPARATOR .
			Yii::app()->params["siteId"] .
			DIRECTORY_SEPARATOR .
			"images" .
			DIRECTORY_SEPARATOR;
	}

	/**
	 * Получает URL изображения
	 *
	 * @param string $type тип
	 *
	 * @return string
	 */
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

	/**
	 * Получает размеры для сжатия изображения
	 *
	 * @param Images $images модель изображений
	 *
	 * @return string[]
	 */
	private function _getSizes(Images $images)
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

	/**
	 * Выполняется после удаления модели
	 *
	 * @return void
	 */
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

	/**
	 * Получает URL миниатюрки
	 *
	 * @return string
	 */
	public function getThumbUrl()
	{
		return $this->_getDir() . "thumb_" . $this->file;
	}

	/**
	 * Получает URL на оригинал
	 *
	 * @return string
	 */
	public function getFullUrl()
	{
		return $this->_getDir() . $this->file;
	}

	/**
	 * Получает URL миниатюрки для окна
	 *
	 * @return string
	 */
	public function getViewUrl()
	{
		return $this->_getDir() . "view_" . $this->file;
	}

	/**
	 * Получает физический путь до папки с изображениями
	 *
	 * @return string
	 */
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
