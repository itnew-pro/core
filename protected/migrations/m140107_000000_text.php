<?php

class m140107_000000_text extends CDbMigration
{

	public function safeUp()
	{
		if (!Yii::app()->db->schema->getTable("text")) {
			$this->createTable(
				"text",
				array(
					"id" => "pk",
					"rows" => "INT NOT NULL",
					"editor" => "INT NOT NULL",
					"tag" => "INT NOT NULL",
					"text" => "text NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);
		}
	}

	public function safeDown()
	{
		if (Yii::app()->db->schema->getTable("text")) {
			$this->dropTable("text");
		}
	}
}