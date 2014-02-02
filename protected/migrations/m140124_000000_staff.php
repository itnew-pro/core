<?php

class m140124_000000_staff extends CDbMigration
{

	public function safeUp()
	{
		if (!Yii::app()->db->schema->getTable("staff")) {
			$this->createTable(
				"staff",
				array(
					"id" => "pk",
					"photo" => "INT NOT NULL",
					"description" => "INT NOT NULL",
					"text" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);
		}

		if (!Yii::app()->db->schema->getTable("staff_group")) {
			$this->createTable(
				"staff_group",
				array(
					"id" => "pk",
					"staff_id" => "INT NOT NULL",
					"seo_id" => "INT NOT NULL",
					"sort" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey(
				"staff_group_staff_id", "staff_group", "staff_id", "staff", "id"
			);
			$this->addForeignKey(
				"staff_group_seo_id", "staff_group", "seo_id", "seo", "id"
			);
		}

		if (!Yii::app()->db->schema->getTable("staff_content")) {
			$this->createTable(
				"staff_content",
				array(
					"id" => "pk",
					"seo_id" => "INT NOT NULL",
					"photo" => "INT NOT NULL",
					"description" => "INT NOT NULL",
					"text" => "INT NOT NULL",
					"group_id" => "INT NOT NULL",
					"sort" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey(
				"staff_content_seo_id", "staff_content", "seo_id", "seo", "id"
			);
			$this->addForeignKey(
				"staff_content_photo", "staff_content", "photo", "images", "id"
			);
			$this->addForeignKey(
				"staff_content_description", "staff_content", "description", "text", "id"
			);
			$this->addForeignKey(
				"staff_content_text", "staff_content", "text", "text", "id"
			);
			$this->addForeignKey(
				"staff_content_group_id", "staff_content", "group_id", "staff_group", "id"
			);
		}
	}

	public function safeDown()
	{
		if (Yii::app()->db->schema->getTable("staff_content")) {
			$this->dropForeignKey("staff_content_seo_id", "staff_content");
			$this->dropForeignKey("staff_content_photo", "staff_content");
			$this->dropForeignKey("staff_content_description", "staff_content");
			$this->dropForeignKey("staff_content_text", "staff_content");
			$this->dropForeignKey("staff_content_group_id", "staff_content");
			$this->dropTable("staff_content");
		}

		if (Yii::app()->db->schema->getTable("staff_group")) {
			$this->dropForeignKey("staff_group_staff_id", "staff_group");
			$this->dropForeignKey("staff_group_seo_id", "staff_group");
			$this->dropTable("staff_group");
		}

		if (Yii::app()->db->schema->getTable("staff")) {
			$this->dropTable("staff");
		}
	}
}