<?php

class m140101_000000_core extends CDbMigration
{

	public function safeUp()
	{
		if (
			!Yii::app()->db->schema->getTable("admin")
			&& !Yii::app()->db->schema->getTable("block")
			&& !Yii::app()->db->schema->getTable("grid")
			&& !Yii::app()->db->schema->getTable("language")
			&& !Yii::app()->db->schema->getTable("section")
			&& !Yii::app()->db->schema->getTable("seo")
			&& !Yii::app()->db->schema->getTable("site")
			&& !Yii::app()->db->schema->getTable("structure")
		) {
			$this->createTable(
				"admin",
				array(
					"id" => "pk",
					"login" => "VARCHAR(255) NOT NULL",
					"password" => "VARCHAR(255) NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"block",
				array(
					"id" => "pk",
					"type" => "INT NOT NULL",
					"name" => "VARCHAR(255) NOT NULL",
					"content_id" => "INT NOT NULL",
					"language_id" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"grid",
				array(
					"id" => "pk",
					"structure_id" => "INT NOT NULL",
					"line" => "INT NOT NULL",
					"left" => "INT NOT NULL",
					"top" => "INT NOT NULL",
					"width" => "INT NOT NULL",
					"block_id" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"language",
				array(
					"id" => "pk",
					"abbreviation" => "VARCHAR(255) NOT NULL",
					"name" => "VARCHAR(255) NOT NULL",
					"main" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"section",
				array(
					"id" => "pk",
					"seo_id" => "INT NOT NULL",
					"language_id" => "INT NOT NULL",
					"structure_id" => "INT NOT NULL",
					"main" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"seo",
				array(
					"id" => "pk",
					"name" => "VARCHAR(255) NOT NULL",
					"url" => "VARCHAR(255) NOT NULL",
					"title" => "VARCHAR(255) NOT NULL",
					"keywords" => "VARCHAR(255) NOT NULL",
					"description" => "TEXT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);
			
			$this->createTable(
				"site",
				array(
					"id" => "pk",
					"name" => "VARCHAR(255) NOT NULL",
					"email" => "VARCHAR(255) NOT NULL",
					"title" => "VARCHAR(255) NOT NULL",
					"keywords" => "VARCHAR(255) NOT NULL",
					"description" => "TEXT NOT NULL",
					"migrate_time" => "TIMESTAMP",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->createTable(
				"structure",
				array(
					"id" => "pk",
					"size" => "INT NOT NULL",
					"width" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->insert("admin", array(
				"login" => Yii::app()->params["admin"]["login"],
				"password" => Yii::app()->params["admin"]["password"],
			));
			$this->insert("site", array(
				"name" => "Site",
				"migrate_time" => Yii::app()->params["migrateTime"],
			));
			$this->insert("language", array(
				"abbreviation" => "ru",
				"name" => "русский",
				"main" => 1
			));

			$this->addForeignKey(
				"section_structure_id", "section", "structure_id", "structure", "id"
			);
			$this->addForeignKey(
				"section_language_id", "section", "language_id", "language", "id"
			);
			$this->addForeignKey(
				"section_seo_id", "section", "seo_id", "seo", "id"
			);
			$this->addForeignKey(
				"grid_structure_id", "grid", "structure_id", "structure", "id"
			);
			$this->addForeignKey(
				"grid_block_id", "grid", "block_id", "block", "id"
			);
			$this->addForeignKey(
				"block_language_id", "block", "language_id", "language", "id"
			);
			$this->createIndex(
				"block_content_id", "block", "content_id"
			);
		}
	}

	public function safeDown()
	{
		if (
			Yii::app()->db->schema->getTable("admin")
			&& Yii::app()->db->schema->getTable("block")
			&& Yii::app()->db->schema->getTable("grid")
			&& Yii::app()->db->schema->getTable("language")
			&& Yii::app()->db->schema->getTable("section")
			&& Yii::app()->db->schema->getTable("seo")
			&& Yii::app()->db->schema->getTable("site")
			&& Yii::app()->db->schema->getTable("structure")
		) {
			$this->dropForeignKey("section_structure_id", "section");
			$this->dropForeignKey("section_language_id", "section");
			$this->dropForeignKey("section_seo_id", "section");
			$this->dropForeignKey("grid_structure_id", "grid");
			$this->dropForeignKey("grid_block_id", "grid");
			$this->dropForeignKey("block_language_id", "block");
			$this->dropIndex("block_content_id", "block");

			$this->dropTable("admin");
			$this->dropTable("block");
			$this->dropTable("grid");
			$this->dropTable("language");
			$this->dropTable("section");
			$this->dropTable("seo");
			$this->dropTable("site");
			$this->dropTable("structure");
		}
	}
}