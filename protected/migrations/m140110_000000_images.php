<?php

class m140110_000000_images extends CDbMigration
{

	public function safeUp()
	{
		if (!Yii::app()->db->schema->getTable("images")) {
			$this->createTable(
				"images",
				array(
					"id" => "pk",
					"many" => "INT NOT NULL",
					"view" => "INT NOT NULL",
					"width" => "INT NOT NULL",
					"height" => "INT NOT NULL",
					"thumb_width" => "INT NOT NULL",
					"thumb_height" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);
		}

		if (!Yii::app()->db->schema->getTable("images_content")) {
			$this->createTable(
				"images_content",
				array(
					"id" => "pk",
					"file" => "VARCHAR(255) NOT NULL",
					"images_id" => "INT NOT NULL",
					"sort" => "INT NOT NULL",
					"alt" => "VARCHAR(255) NOT NULL",
					"link" => "VARCHAR(255) NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey(
				"images_content_images_id", "images_content", "images_id", "images", "id"
			);
		}
	}

	public function safeDown()
	{
		if (Yii::app()->db->schema->getTable("images_content")) {
			$this->dropForeignKey("images_content_images_id", "images_content");
			$this->dropTable("images_content");
		}

		if (Yii::app()->db->schema->getTable("images")) {
			$this->dropTable("images");
		}
	}
}