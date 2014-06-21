<?php

namespace itnew\models;

use itnew\models\Structure;
use itnew\models\Block;
use itnew\models\Language;
use CActiveRecord;
use Yii;
use CActiveDataProvider;
use CDbCriteria;

/**
 * Файл класса Text.
 *
 * Модель для таблицы "text"
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package models
 *
 * @property int    $id            идентификатор
 * @property int    $rows          количество строк
 * @property int    $editor        редактор
 * @property int    $tag           идентификатор тега
 * @property string $text          текст
 *
 * @property Block  $block         модель блока
 */
class Text extends CActiveRecord
{

	/**
	 * Стандартное количество строк в редакторе
	 *
	 * @var int
	 */
	const DEFAULT_TEXT_SIZE = 15;

	/**
	 * Стандартное количество строк в мини редакторе
	 *
	 * @var int
	 */
	const DEFAULT_DESCRIPTION_SIZE = 5;

	/**
	 * Теги
	 *
	 * @var string[]
	 */
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
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'text';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array('text', 'length'),
			array('rows, editor, tag', 'numerical', 'integerOnly' => true),
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
			"block" => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_TEXT,
				),
			),
		);
	}

	/**
	 * Возвращает список поведений модели
	 *
	 * @return string[]
	 */
	public function behaviors()
	{
		return array(
			"ContentBehavior" => array(
				"class"     => "itnew\behaviors\ContentBehavior",
				"blockType" => Block::TYPE_TEXT,
			)
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
			'rows'   => Yii::t("text", "Rows"),
			'editor' => Yii::t("text", "Editor"),
			'tag'    => Yii::t("text", "Tag"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Text
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Получает название
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return Yii::t("text", "Text");
	}

	/**
	 * Сохраняет контент
	 *
	 * @param string $post поля модели переданные через POST
	 *
	 * @return bool
	 */
	public function saveContent($post = array())
	{
		$this->text = $post["text"];

		return $this->save();
	}

	/**
	 * Вызывается после удаления модели
	 *
	 * @return void
	 */
	protected function afterDelete()
	{
		parent::afterDelete();

		if ($this->block) {
			$this->block->delete();
		}
	}

	/**
	 * Получает название тега
	 *
	 * @return string
	 */
	public function getTag()
	{
		if (empty($this->tagList[$this->tag])) {
			return $this->tagList[0];
		}

		return $this->tagList[$this->tag];
	}

	/**
	 * Получает название класса редактора
	 *
	 * @return string
	 */
	public function getEditorClass()
	{
		if ($this->editor) {
			return " tinymce";
		}

		return null;
	}

	/**
	 * Получает список с количеством строк редактора
	 *
	 * @return int[]
	 */
	public function getRowsList()
	{
		$rows = array();

		$rows[0] = Yii::t("text", "Max");
		for ($i = 1; $i < 21; $i++) {
			$rows[$i] = $i;
		}

		return $rows;
	}

	/**
	 * Делает дубликат
	 *
	 * @return bool
	 */
	public function duplicate()
	{
		$textCopy = new Text;
		$textCopy->rows = $this->rows;
		$textCopy->editor = $this->editor;
		$textCopy->tag = $this->tag;
		$textCopy->text = $this->text;

		$transaction = Yii::app()->db->beginTransaction();
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

	/**
	 * Получает новую стандартную модель для текста
	 *
	 * @return Text
	 */
	public function getDefaultTextModel()
	{
		$model = new self;
		$model->rows = self::DEFAULT_TEXT_SIZE;
		$model->editor = 1;

		return $model;
	}

	/**
	 * Получает новую стандартную модель для описания
	 *
	 * @return Text
	 */
	public function getDefaultDescriptionModel()
	{
		$model = new self;
		$model->rows = self::DEFAULT_DESCRIPTION_SIZE;

		return $model;
	}
}