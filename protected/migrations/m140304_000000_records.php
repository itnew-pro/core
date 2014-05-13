<?php

/**
 * Файл класса m140304_000000_records.
 *
 * Миграция для записей
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package migrations
 */
class m140304_000000_records extends CDbMigration
{

	/**
	 * Применяет миграцию в трансакции
	 *
	 * @return bool
	 */
	public function safeUp()
	{
		if (!Yii::app()->db->schema->getTable("records")) {
			$this->createTable(
				"records",
				array(
					"id"           => "pk",
					"date"         => "INT NOT NULL",
					"is_detail"    => "INT NOT NULL",
					"cover"        => "INT",
					"images"       => "INT",
					"structure_id" => "INT",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey("records_cover", "records", "cover", "images", "id");
			$this->addForeignKey("records_images", "records", "images", "images", "id");
			$this->addForeignKey("records_structure_id", "records", "structure_id", "structure", "id");
		}

		if (!Yii::app()->db->schema->getTable("records_clone")) {
			$this->createTable(
				"records_clone",
				array(
					"id"             => "pk",
					"records_id"     => "INT NOT NULL",
					"is_date"        => "INT NOT NULL",
					"is_detail"      => "INT NOT NULL",
					"is_cover"       => "INT NOT NULL",
					"is_description" => "INT NOT NULL",
					"count"          => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey("records_clone_records_id", "records_clone", "records_id", "records", "id");
		}

		if (!Yii::app()->db->schema->getTable("records_content")) {
			$this->createTable(
				"records_content",
				array(
					"id"           => "pk",
					"records_id"   => "INT NOT NULL",
					"cover"        => "INT",
					"date"         => "TIMESTAMP",
					"seo_id"       => "INT NOT NULL",
					"images"       => "INT NOT NULL",
					"text"         => "INT NOT NULL",
					"description"  => "INT NOT NULL",
					"sort"         => "INT NOT NULL",
					"is_published" => "INT NOT NULL",
				),
				"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
			);

			$this->addForeignKey("records_content_records_id", "records_content", "records_id", "records", "id");
			$this->addForeignKey("records_content_cover", "records_content", "cover", "images", "id");
			$this->addForeignKey("records_content_seo_id", "records_content", "seo_id", "seo", "id");
			$this->addForeignKey("records_content_images", "records_content", "images", "images", "id");
			$this->addForeignKey("records_content_text", "records_content", "text", "text", "id");
			$this->addForeignKey("records_content_description", "records_content", "description", "text", "id");
			$this->createIndex("records_content_sort", "records_content", "sort");
		}
	}

	/**
	 * Откатывает миграцию в трансакции
	 *
	 * @return bool
	 */
	public function safeDown()
	{
		if (Yii::app()->db->schema->getTable("records_content")) {
			$this->dropForeignKey("records_content_records_id", "records_content");
			$this->dropForeignKey("records_content_cover", "records_content");
			$this->dropForeignKey("records_content_seo_id", "records_content");
			$this->dropForeignKey("records_content_images", "records_content");
			$this->dropForeignKey("records_content_text", "records_content");
			$this->dropForeignKey("records_content_description", "records_content");
			$this->dropIndex("records_content_sort", "records_content");
			$this->dropTable("records_content");
		}

		if (Yii::app()->db->schema->getTable("records_clone")) {
			$this->dropForeignKey("records_clone_records_id", "records_clone");
			$this->dropTable("records_clone");
		}

		if (Yii::app()->db->schema->getTable("records")) {
			$this->dropForeignKey("records_cover", "records");
			$this->dropForeignKey("records_images", "records");
			$this->dropForeignKey("records_structure_id", "records");
			$this->dropTable("records");
		}
	}
}