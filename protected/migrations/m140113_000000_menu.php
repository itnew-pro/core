<?php

class m140113_000000_menu extends CDbMigration
{

	public function safeUp()
	{
		if (!Yii::app()->db->schema->getTable("menu")) {
			$this->createTable(
				"menu",
				array(
					"id" => "pk",
					"type" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);
		}

		if (!Yii::app()->db->schema->getTable("menu_content")) {
			$this->createTable(
				"menu_content",
				array(
					"id" => "pk",
					"menu_id" => "INT NOT NULL",
					"section_id" => "INT",
					"block_id" => "INT",
					"sort" => "INT",
					"parent_id" => "INT",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey(
				"menu_content_menu_id", "menu_content", "menu_id", "menu", "id"
			);
			$this->addForeignKey(
				"menu_content_section_id", "menu_content", "section_id", "section", "id"
			);
			$this->addForeignKey(
				"menu_content_block_id", "menu_content", "block_id", "block", "id"
			);
			$this->createIndex(
				"menu_content_sort", "menu_content", "sort"
			);
		}
	}

	public function safeDown()
	{
		if (Yii::app()->db->schema->getTable("menu_content")) {
			$this->dropForeignKey("menu_content_menu_id", "menu_content");
			$this->dropForeignKey("menu_content_section_id", "menu_content");
			$this->dropForeignKey("menu_content_block_id", "menu_content");
			$this->dropIndex("menu_content_sort", "menu_content");
			$this->dropTable("menu_content");
		}

		if (Yii::app()->db->schema->getTable("menu")) {
			$this->dropTable("menu");
		}
	}
}