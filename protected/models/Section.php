<?php

namespace itnew\models;

use itnew\models\Seo;
use itnew\models\Language;
use itnew\models\Structure;
use itnew\models\MenuContent;
use CActiveRecord;
use Yii;
use CDbCriteria;

/**
 * Файл класса Section.
 *
 * Модель для таблицы "section"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int         $id           идентификатор
 * @property int         $seo_id       идентификатор СЕО
 * @property int         $language_id  идентификатор языка
 * @property int         $structure_id идентификатор структуры
 * @property int         $main         главный раздел
 *
 * @property Seo         $seo          модель СЕО
 * @property Language    $language     модель языка
 * @property Structure   $structure    модель структуры
 * @property MenuContent $menuContent  модель элемента меню
 */
class Section extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'section';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('seo_id, language_id, structure_id, main', 'required'),
			array('seo_id, language_id, structure_id, main', 'numerical', 'integerOnly' => true),
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
			'seo'         => array(
				self::BELONGS_TO,
				'itnew\models\Seo',
				'seo_id'
			),
			'language'    => array(
				self::BELONGS_TO,
				'itnew\models\Language',
				'language_id'
			),
			'structure'   => array(
				self::BELONGS_TO,
				'itnew\models\Structure',
				'structure_id'
			),
			'menuContent' => array(
				self::HAS_ONE,
				'itnew\models\MenuContent',
				'section_id'
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
			'main' => Yii::t("section", "Home"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Section
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает активный раздел
	 *
	 * @param string $sectionUrl URL раздела
	 *
	 * @return Section
	 */
	public function getActive($sectionUrl)
	{
		$criteria = new CDbCriteria;
		$criteria->params["language_id"] = Language::getActiveId();

		if ($sectionUrl) {
			$criteria->with = array("seo");
			$criteria->condition = "seo.url = :url AND t.language_id = :language_id";
			$criteria->params["url"] = $sectionUrl;

			return $this->find($criteria);
		}

		$criteria->condition = "main = :main AND language_id = :language_id";
		$criteria->params["main"] = 1;

		return $this->find($criteria);
	}

	/**
	 * Сохраняет раздел
	 * Получает CSS-класс ошибки
	 *
	 * @param string[] $post поля модели переданные через POST
	 * @param string[] $seo  поля модели Seo переданные через POST
	 *
	 * @return string
	 */
	public function saveForm($post, $seo)
	{
		if ($post["id"]) {
			$model = $this->findByPk($post["id"]);
		} else {
			$model = new self;
			$model->seo = new Seo;
			$model->main = 0;
		}

		$criteria = new CDbCriteria;
		$criteria->with = array("seo");
		$criteria->params["url"] = $seo["url"];
		if ($model->isNewRecord) {
			$criteria->condition = "seo.url = :url";
		} else {
			$criteria->condition = "seo.url = :url AND t.id != :id";
			$criteria->params["id"] = $model->id;
		}
		if ($this->find($criteria)) {
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

		if ($post["main"]) {
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

		return null;
	}

	/**
	 * Выполняется после удаления модели
	 *
	 * @return void
	 */
	protected function afterDelete()
	{
		parent::afterDelete();

		if ($this->seo) {
			$this->seo->delete();
		}
		if ($this->structure) {
			$this->structure->delete();
		}
	}

	/**
	 * Дублирует раздел
	 *
	 * @return bool
	 */
	public function duplicate()
	{
		if (!$this->seo || !$this->structure) {
			return false;
		}

		$transaction = Yii::app()->db->beginTransaction();

		$seoCopy = new Seo;
		$seoCopy->name = $this->seo->name . " - " . Yii::t("common", "copy");
		$seoCopy->url = $this->seo->url . "-copy";
		$seoCopy->title = $this->seo->title;
		$seoCopy->keywords = $this->seo->keywords;
		$seoCopy->description = $this->seo->description;

		if (!$seoCopy->save()) {
			$transaction->rollback();
			return false;
		}

		$structureCopy = new Structure;
		$structureCopy->width = $this->structure->width;

		if (!$structureCopy->save()) {
			$transaction->rollback();
			return false;
		}

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

		$transaction->rollback();

		return false;
	}

	/**
	 * Получает модель Seo
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
	 * Получает ссылку на раздел
	 *
	 * @return string
	 */
	public function getLink()
	{
		if (!$this->seo) {
			return false;
		}

		$url = $this->getUrl();

		$active = null;
		if (Yii::app()->request->url === $url) {
			$active = "class=\"active\"";
		}

		return "<a href=\"{$url}\" {$active}>{$this->seo->name}</a>";
	}

	/**
	 * Выполняется перед удалением модели
	 *
	 * @return bool
	 */
	public function beforeDelete()
	{
		if ($this->menuContent) {
			foreach ($this->menuContent as $model) {
				$model->delete();
			}
		}

		return parent::beforeDelete();
	}

	/**
	 * Получает ссылку на раздел
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return Yii::app()->createUrl(
			"site/index",
			array(
				"language" => Yii::app()->language,
				"section"  => $this->seo->url,
			)
		);
	}
}
