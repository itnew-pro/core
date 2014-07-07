v<?php

/**
 * Файл класса m140707_000000_catalog.
 *
 * Миграция для каталога
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package migrations
 */
class m140707_000000_catalog extends CDbMigration
{

	/**
	 * Применяет миграцию в трансакции
	 *
	 * @return bool
	 */
	public function safeUp()
	{
		if (
			Yii::app()->db->schema->getTable("catalog")
			|| Yii::app()->db->schema->getTable("catalog_menu")
			|| Yii::app()->db->schema->getTable("catalog_item")
			|| Yii::app()->db->schema->getTable("catalog_size")
			|| Yii::app()->db->schema->getTable("catalog_field")
			|| Yii::app()->db->schema->getTable("catalog_item_field")
		) {
			return false;
		}

		$this->createTable(
			"catalog",
			array(
				"id"         => "pk",
				"price_type" => "INT NOT NULL",
				"is_article" => "INT NOT NULL",
				"is_color"   => "INT NOT NULL",
				"is_size"    => "INT NOT NULL",
				"is_price"   => "INT NOT NULL",
				"is_actions" => "INT NOT NULL",
				"date_type"  => "INT NOT NULL",
				"is_cover"   => "INT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createTable(
			"catalog_menu",
			array(
				"id"         => "pk",
				"catalog_id" => "INT NOT NULL",
				"parent_id"  => "INT NOT NULL",
				"seo_id"     => "INT NOT NULL",
				"sort"       => "INT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createTable(
			"catalog_item",
			array(
				"id"              => "pk",
				"catalog_menu_id" => "INT NOT NULL",
				"cover_id"        => "INT NOT NULL",
				"seo_id"          => "INT NOT NULL",
				"sort"            => "INT NOT NULL",
				"text_id"         => "INT NOT NULL",
				"description_id"  => "INT NOT NULL",
				"action_type"     => "INT NOT NULL",
				"old_price"       => "INT NOT NULL",
				"price"           => "INT NOT NULL",
				"color_id"        => "INT NOT NULL",
				"date"            => "TIMESTAMP",
				"article"         => "VARCHAR(255) NOT NULL",
				"catalog_size_id" => "INT",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createTable(
			"catalog_size",
			array(
				"id"              => "pk",
				"catalog_item_id" => "INT NOT NULL",
				"value"           => "VARCHAR(255) NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createTable(
			"catalog_field",
			array(
				"id"         => "pk",
				"catalog_id" => "INT NOT NULL",
				"value"      => "VARCHAR(255) NOT NULL",
				"sort"       => "INT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createTable(
			"catalog_item_field",
			array(
				"id"               => "pk",
				"catalog_item_id"  => "INT NOT NULL",
				"catalog_field_id" => "INT NOT NULL",
				"value"            => "VARCHAR(255) NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->addForeignKey("catalog_menu_catalog_id", "catalog_menu", "catalog_id", "catalog", "id");
		$this->addForeignKey("catalog_menu_seo_id", "catalog_menu", "seo_id", "seo", "id");
		$this->createIndex("catalog_menu_sort", "catalog_menu", "sort");
		$this->createIndex("catalog_menu_parent_id", "catalog_menu", "parent_id");

		$this->addForeignKey("catalog_item_catalog_menu_id", "catalog_item", "catalog_menu_id", "catalog_menu", "id");
		$this->addForeignKey("catalog_item_cover_id", "catalog_item", "cover_id", "images", "id");
		$this->addForeignKey("catalog_item_seo_id", "catalog_item", "seo_id", "seo", "id");
		$this->addForeignKey("catalog_item_text_id", "catalog_item", "text_id", "text", "id");
		$this->addForeignKey("catalog_item_description_id", "catalog_item", "description_id", "text", "id");
		$this->addForeignKey("catalog_item_catalog_size_id", "catalog_item", "catalog_size_id", "catalog_size", "id");
		$this->createIndex("catalog_item_sort", "catalog_item", "sort");
		$this->createIndex("catalog_item_price", "catalog_item", "price");
		$this->createIndex("catalog_item_color_id", "catalog_item", "color_id");

		$this->addForeignKey("catalog_size_catalog_item_id", "catalog_size", "catalog_item_id", "catalog_item", "id");

		$this->addForeignKey("catalog_field_catalog_id", "catalog_field", "catalog_id", "catalog", "id");
		$this->createIndex("catalog_field_sort", "catalog_field", "sort");

		$this->addForeignKey(
			"catalog_item_field_catalog_item_id",
			"catalog_item_field",
			"catalog_item_id",
			"catalog_item",
			"id"
		);
		$this->addForeignKey(
			"catalog_item_field_catalog_field_id",
			"catalog_item_field",
			"catalog_field_id",
			"catalog_field",
			"id"
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
		if (
			!Yii::app()->db->schema->getTable("catalog")
			|| !Yii::app()->db->schema->getTable("catalog_menu")
			|| !Yii::app()->db->schema->getTable("catalog_item")
			|| !Yii::app()->db->schema->getTable("catalog_size")
			|| !Yii::app()->db->schema->getTable("catalog_field")
			|| !Yii::app()->db->schema->getTable("catalog_item_field")
		) {
			return false;
		}

		$this->dropForeignKey("catalog_menu_catalog_id", "catalog_menu");
		$this->dropForeignKey("catalog_menu_seo_id", "catalog_menu");
		$this->dropIndex("catalog_menu_sort", "catalog_menu");
		$this->dropIndex("catalog_menu_parent_id", "catalog_menu");

		$this->dropForeignKey("catalog_item_catalog_menu_id", "catalog_item");
		$this->dropForeignKey("catalog_item_cover_id", "catalog_item");
		$this->dropForeignKey("catalog_item_seo_id", "catalog_item");
		$this->dropForeignKey("catalog_item_text_id", "catalog_item");
		$this->dropForeignKey("catalog_item_description_id", "catalog_item");
		$this->dropForeignKey("catalog_item_catalog_size_id", "catalog_item");
		$this->dropIndex("catalog_item_sort", "catalog_menu");
		$this->dropIndex("catalog_item_price", "catalog_menu");
		$this->dropIndex("catalog_item_color_id", "catalog_menu");

		$this->dropForeignKey("catalog_size_catalog_item_id", "catalog_size");

		$this->dropForeignKey("catalog_field_catalog_id", "catalog_field");
		$this->dropIndex("catalog_field_sort", "catalog_field");

		$this->dropForeignKey("catalog_item_field_catalog_item_id", "catalog_item_field");
		$this->dropForeignKey("catalog_item_field_catalog_field_id", "catalog_item_field");

		$this->dropTable("catalog");
		$this->dropTable("catalog_menu");
		$this->dropTable("catalog_item");
		$this->dropTable("catalog_size");
		$this->dropTable("catalog_field");
		$this->dropTable("catalog_item_field");

		return true;
	}
}