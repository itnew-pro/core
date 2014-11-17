<?php

namespace itnew\behaviors;

use itnew\models\Block;
use itnew\models\Language;
use CActiveRecordBehavior;
use Yii;
use CDbCriteria;

/**
 * Файл класса ContentBehavior.
 *
 * Поведения для моделей контента
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package behaviors
 */
class ContentBehavior extends CActiveRecordBehavior
{

	/**
	 * Идентификатор блока
	 *
	 * @var int
	 */
	public $blockType = 0;

	/**
	 * Добавляет настройки
	 *
	 * @param string[] $blockPost данные POST для блока
	 * @param string[] $modelPost данные POST для модели
	 * @param string[] $params    дополнительные параметры
	 *
	 * @return self::owner|null
	 */
	public function addSettings($blockPost, $modelPost, $params = array())
	{
		$model = new $this->owner;
		$block = new Block;
		$model->attributes = $modelPost;
		$block->attributes = $blockPost;

		$transaction = Yii::app()->db->beginTransaction();

		foreach ($params as $param) {
			$paramModel = new $param["class"];
			$paramModel->attributes = $param["post"];
			if ($paramModel->save()) {
				$model->$param["attribute"] = $paramModel->id;
			}
		}

		if ($model->save()) {
			$block->content_id = $model->id;
			$block->type = $this->blockType;
			$block->language_id = Language::getActiveId();
			if ($block->save()) {
				$transaction->commit();

				return $model;
			}
		}

		$transaction->rollback();

		return null;
	}

	/**
	 * Обновляет настройки модели
	 *
	 * @param int $id идентификатор
	 * @param string[] $blockPost данные POST для блока
	 * @param string[] $modelPost данные POST для модели
	 * @param string[] $params    дополнительные параметры
	 *
	 * @return self::owner|null
	 */
	public function updateSettings($id, $blockPost, $modelPost, $params = array())
	{
		$model = $this->owner->findByPk($id);
		if (!$model) {
			return null;
		}

		if ($model->block) {
			$block = $model->block;
		} else {
			$block = new Block;
		}

		$model->attributes = $modelPost;
		$block->attributes = $blockPost;

		$transaction = Yii::app()->db->beginTransaction();

		foreach ($params as $param) {
			if ($model->$param["relation"]) {
				$paramModel = $model->$param["relation"];
			} else {
				$paramModel = new $param["class"];
			}
			$aaa = Yii::app()->request->getPost($param["post"]);

			$paramModel->attributes = $aaa;
			if ($paramModel->save()) {
				$model->$param["attribute"] = $paramModel->id;
			}
		}

		if ($block->save()) {
			if ($model->save()) {
				$transaction->commit();

				return $model;
			}
		}

		$transaction->rollback();

		return null;
	}

	/**
	 * Получает блок
	 *
	 * @return Block
	 */
	public function getBlock()
	{
		if ($this->owner->block) {
			return $this->owner->block;
		}

		return new Block;
	}

	/**
	 * Получает все блоки контента
	 *
	 * @param int[] $in идентификаторы блоков
	 *
	 * @return Block[]
	 */
	public function getAllContentBlocks($in = array())
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "language_id = :language_id AND type = :type";
		$criteria->params = array(
			":language_id" => Language::getActiveId(),
			":type"        => $this->blockType
		);

		if ($in) {
			$criteria->addInCondition("t.id", $in);
		}

		return Block::model()->findAll($criteria);
	}

	/**
	 * Получает все блоки на текущей странице
	 *
	 * @return Block[]
	 */
	public function getThisPageBlocks()
	{
		return $this->getAllContentBlocks(Block::model()->getAllThisPageBlocksIds());
	}
}