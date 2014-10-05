<?php

namespace itnew\models;

use CActiveRecord;
use Yii;

/**
 * Файл класса Feedback.
 *
 * Модель для таблицы "feedback"
 *
 * @property integer $id                  идентификатор
 * @property string  $email_to            адрес получателя
 * @property integer $is_name             наличие поля Name
 * @property integer $is_name_required    обязательное ли поле Name
 * @property string  $name_label          название метки Name
 * @property string  $email_from_label    название метки Email
 * @property integer $is_phone            наличие поля Phone
 * @property integer $is_phone_required   обязательное ли поле Phone
 * @property string  $phone_label         название метки Phone
 * @property integer $phone_mask          тип маски для Phone
 * @property integer $is_adress           наличие поля Adress
 * @property integer $is_adress_required  обязательное ли поле Adress
 * @property string  $adress_label        название метки Adress
 * @property integer $is_subject          наличие поля Subject
 * @property integer $is_subject_required обязательное ли поле Subject
 * @property string  $subject_label       название метки Subject
 * @property integer $is_message          наличие поля Message
 * @property integer $is_message_required обязательное ли поле Message
 * @property string  $message_label       название метки Message
 * @property string  $send_label          название кнопки отправления
 *
 * @property Block   $block               модель блока
 */
class Feedback extends CActiveRecord
{

	/**
	 * Возвращает имя связанной таблицы базы данных
	 *
	 * @return string
	 */
	public function tableName()
	{
		return 'feedback';
	}

	/**
	 * Возвращает правила проверки для атрибутов модели
	 *
	 * @return string[]
	 */
	public function rules()
	{
		return array(
			array(
				'email_to, name_label, email_from_label, phone_label, adress_label, subject_label, message_label,
					send_label',
				'length',
				"max" => 255
			),
			array(
				'is_name, is_name_required, is_phone, is_phone_required, phone_mask, is_adress, is_adress_required,
					is_subject, is_subject_required, is_message, is_message_required',
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
			"block" => array(
				self::HAS_ONE,
				'itnew\models\Block',
				'content_id',
				"condition" => "block.type = :type",
				"params"    => array(
					":type" => Block::TYPE_FEEDBACK,
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
				"blockType" => Block::TYPE_FEEDBACK,
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
			"is_name_required"    => "",
			"is_phone_required"   => "",
			"is_adress_required"  => "",
			"is_subject_required" => "",
			"is_message_required" => "",
			"name_label"          => ($this->name_label) ? $this->name_label : Yii::t("feedback", "Name"),
			"email_from_label"    => ($this->email_from_label) ? $this->email_from_label : Yii::t("feedback", "Email"),
			"phone_label"         => ($this->phone_label) ? $this->phone_label : Yii::t("feedback", "Phone"),
			"adress_label"        => ($this->adress_label) ? $this->adress_label : Yii::t("feedback", "Adress"),
			"subject_label"       => ($this->subject_label) ? $this->subject_label : Yii::t("feedback", "Subject"),
			"message_label"       => ($this->message_label) ? $this->message_label : Yii::t("feedback", "Message"),
			"send_label"          => Yii::t("feedback", "Send button text"),
			"email_to"            => Yii::t("feedback", "Recipient's email"),
		);
	}

	/**
	 * Возвращает статическую модель указанного класса.
	 *
	 * @param string $className название класса
	 *
	 * @return Feedback
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
		return Yii::t("feedback", "Feedback");
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
}