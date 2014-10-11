v<?php

/**
 * Файл класса m140921_000000_feedback.
 *
 * Миграция для обратной связи
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package migrations
 */
class m140921_000000_feedback extends CDbMigration
{

	/**
	 * Применяет миграцию в трансакции
	 *
	 * @return bool
	 */
	public function safeUp()
	{
		if (Yii::app()->db->schema->getTable("feedback")) {
			return false;
		}

		$this->createTable(
			"feedback",
			array(
				"id"                  => "pk",
				"email_to"            => "VARCHAR(255) NOT NULL",
				"is_name"             => "INT NOT NULL",
				"is_name_required"    => "INT NOT NULL",
				"name_label"          => "VARCHAR(255) NOT NULL",
				"is_email_required"   => "INT NOT NULL",
				"is_phone"            => "INT NOT NULL",
				"is_phone_required"   => "INT NOT NULL",
				"phone_label"         => "VARCHAR(255) NOT NULL",
				"phone_mask"          => "INT NOT NULL",
				"is_adress"           => "INT NOT NULL",
				"is_adress_required"  => "INT NOT NULL",
				"adress_label"        => "VARCHAR(255) NOT NULL",
				"is_subject"          => "INT NOT NULL",
				"is_subject_required" => "INT NOT NULL",
				"subject_label"       => "VARCHAR(255) NOT NULL",
				"is_message"          => "INT NOT NULL",
				"is_message_required" => "INT NOT NULL",
				"message_label"       => "VARCHAR(255) NOT NULL",
				"send_label"          => "VARCHAR(255) NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		return true;
	}

	/**
	 * Откатывает миграцию в трансакции
	 *
	 * @return bool
	 */
	public function safeDown()
	{
		if (!Yii::app()->db->schema->getTable("feedback")) {
			return false;
		}

		$this->dropTable("feedback");

		return true;
	}
}