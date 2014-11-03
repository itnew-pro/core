<?php

/**
 * Файл класса m141028_000000_catalog.
 *
 * Миграция для каталога
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package migrations
 */
class m141028_000000_catalog extends CDbMigration
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
			|| Yii::app()->db->schema->getTable("catalog_item_clone")
			|| Yii::app()->db->schema->getTable("catalog_size")
			|| Yii::app()->db->schema->getTable("catalog_field")
			|| Yii::app()->db->schema->getTable("catalog_item_field")
		) {
			return false;
		}

		$this->createTable(
			"catalog",
			array(
				"id"                    => "pk",
				"price_type"            => "INT NOT NULL",
				"size_type"             => "INT NOT NULL",
				"new_type"              => "INT NOT NULL",
				"discount_type"         => "INT NOT NULL",
				"date_type"             => "INT NOT NULL",
				"is_rating"           => "INT NOT NULL",
				"is_article"            => "INT NOT NULL",
				"is_color"              => "INT NOT NULL",
				"is_brand"              => "INT NOT NULL",
				"is_cover"              => "INT NOT NULL",
				"is_images"             => "INT NOT NULL",
				"is_description"        => "INT NOT NULL",
				"is_text"               => "INT NOT NULL",
				"price_in_short_card"   => "INT NOT NULL",
				"size_in_short_card"    => "INT NOT NULL",
				"date_in_short_card"    => "INT NOT NULL",
				"article_in_short_card" => "INT NOT NULL",
				"color_in_short_card"   => "INT NOT NULL",
				"brand_in_short_card"   => "INT NOT NULL",
				"rating_in_short_card"  => "INT NOT NULL",
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
		$this->addForeignKey("catalog_menu_catalog_id", "catalog_menu", "catalog_id", "catalog", "id");
		$this->addForeignKey("catalog_menu_seo_id", "catalog_menu", "seo_id", "seo", "id");
		$this->createIndex("catalog_menu_sort", "catalog_menu", "sort");
		$this->createIndex("catalog_menu_parent_id", "catalog_menu", "parent_id");

		$this->createTable(
			"catalog_brand",
			array(
				"id"         => "pk",
				"catalog_id" => "INT NOT NULL",
				"seo_id"     => "INT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey("catalog_brand_catalog_id", "catalog_brand", "catalog_id", "catalog", "id");
		$this->addForeignKey("catalog_brand_seo_id", "catalog_brand", "seo_id", "seo", "id");

		$this->createTable(
			"catalog_item",
			array(
				"id"              => "pk",
				"catalog_menu_id" => "INT NOT NULL",
				"cover_id"        => "INT",
				"images_id"       => "INT",
				"seo_id"          => "INT NOT NULL",
				"text_id"         => "INT",
				"description_id"  => "INT",
				"brand_id"        => "INT",
				"sort"            => "INT NOT NULL",
				"is_new"          => "INT NOT NULL",
				"discount"        => "INT NOT NULL",
				"price"           => "INT NOT NULL",
				"color_id"        => "INT NOT NULL",
				"article"         => "VARCHAR(255) NOT NULL",
				"date"            => "TIMESTAMP",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey("catalog_item_catalog_menu_id", "catalog_item", "catalog_menu_id", "catalog_menu", "id");
		$this->addForeignKey("catalog_item_cover_id", "catalog_item", "cover_id", "images", "id");
		$this->addForeignKey("catalog_item_images_id", "catalog_item", "images_id", "images", "id");
		$this->addForeignKey("catalog_item_seo_id", "catalog_item", "seo_id", "seo", "id");
		$this->addForeignKey("catalog_item_text_id", "catalog_item", "text_id", "text", "id");
		$this->addForeignKey("catalog_item_description_id", "catalog_item", "description_id", "text", "id");
		$this->addForeignKey("catalog_item_brand_id", "catalog_item", "brand_id", "catalog_brand", "id");
		$this->createIndex("catalog_item_sort", "catalog_item", "sort");
		$this->createIndex("catalog_item_is_new", "catalog_item", "is_new");
		$this->createIndex("catalog_item_discount", "catalog_item", "discount");
		$this->createIndex("catalog_item_price", "catalog_item", "price");
		$this->createIndex("catalog_item_color_id", "catalog_item", "color_id");
		$this->createIndex("catalog_item_article", "catalog_item", "article");

		$this->createTable(
			"catalog_item_clone",
			array(
				"id"              => "pk",
				"catalog_item_id" => "INT NOT NULL",
				"cover_id"        => "INT",
				"images_id"       => "INT",
				"color_id"        => "INT NOT NULL",
				"article"         => "VARCHAR(255) NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey(
			"catalog_item_clone_catalog_item_id",
			"catalog_item_clone",
			"catalog_item_id",
			"catalog_item",
			"id"
		);
		$this->addForeignKey("catalog_item_clone_cover_id", "catalog_item_clone", "cover_id", "images", "id");
		$this->addForeignKey("catalog_item_clone_images_id", "catalog_item_clone", "images_id", "images", "id");
		$this->createIndex("catalog_item_clone_color_id", "catalog_item_clone", "color_id");
		$this->createIndex("catalog_item_clone_article", "catalog_item_clone", "article");

		$this->createTable(
			"catalog_size",
			array(
				"id"              => "pk",
				"catalog_item_id" => "INT NOT NULL",
				"value"           => "FLOAT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey("catalog_size_catalog_item_id", "catalog_size", "catalog_item_id", "catalog_item", "id");
		$this->createIndex("catalog_size_value", "catalog_size", "value");

		$this->createTable(
			"catalog_field",
			array(
				"id"         => "pk",
				"catalog_id" => "INT NOT NULL",
				"value"      => "VARCHAR(255) NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey("catalog_field_catalog_id", "catalog_field", "catalog_id", "catalog", "id");

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

		$this->createTable(
			"catalog_item_rating",
			array(
				"id"               => "pk",
				"catalog_item_id"  => "INT NOT NULL",
				"ip"               => "INT UNSIGNED NOT NULL",
				"value"            => "FLOAT NOT NULL",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
		$this->addForeignKey(
			"catalog_item_rating_catalog_item_id",
			"catalog_item_rating",
			"catalog_item_id",
			"catalog_item",
			"id"
		);
		$this->createIndex("catalog_item_rating_value", "catalog_item_rating", "value");

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
			|| !Yii::app()->db->schema->getTable("catalog_item_clone")
			|| !Yii::app()->db->schema->getTable("catalog_size")
			|| !Yii::app()->db->schema->getTable("catalog_field")
			|| !Yii::app()->db->schema->getTable("catalog_item_field")
			|| !Yii::app()->db->schema->getTable("catalog_brand")
			|| !Yii::app()->db->schema->getTable("catalog_item_rating")
		) {
			return false;
		}

		$this->dropForeignKey("catalog_menu_catalog_id", "catalog_menu");
		$this->dropForeignKey("catalog_menu_seo_id", "catalog_menu");
		$this->dropIndex("catalog_menu_sort", "catalog_menu");
		$this->dropIndex("catalog_menu_parent_id", "catalog_menu");

		$this->dropForeignKey("catalog_brand_catalog_id", "catalog_brand");
		$this->dropForeignKey("catalog_brand_seo_id", "catalog_brand");

		$this->dropForeignKey("catalog_item_catalog_menu_id", "catalog_item");
		$this->dropForeignKey("catalog_item_cover_id", "catalog_item");
		$this->dropForeignKey("catalog_item_images_id", "catalog_item");
		$this->dropForeignKey("catalog_item_seo_id", "catalog_item");
		$this->dropForeignKey("catalog_item_text_id", "catalog_item");
		$this->dropForeignKey("catalog_item_description_id", "catalog_item");
		$this->dropForeignKey("catalog_item_brand_id", "catalog_item");
		$this->dropIndex("catalog_item_sort", "catalog_item");
		$this->dropIndex("catalog_item_is_new", "catalog_item");
		$this->dropIndex("catalog_item_discount", "catalog_item");
		$this->dropIndex("catalog_item_price", "catalog_item");
		$this->dropIndex("catalog_item_color_id", "catalog_item");
		$this->dropIndex("catalog_item_article", "catalog_item");

		$this->dropForeignKey("catalog_item_clone_catalog_item_id", "catalog_item_clone");
		$this->dropForeignKey("catalog_item_clone_cover_id", "catalog_item_clone");
		$this->dropForeignKey("catalog_item_clone_images_id", "catalog_item_clone");
		$this->dropIndex("catalog_item_clone_color_id", "catalog_item_clone");
		$this->dropIndex("catalog_item_clone_article", "catalog_item_clone");

		$this->dropForeignKey("catalog_size_catalog_item_id", "catalog_size");
		$this->dropIndex("catalog_size_value", "catalog_size");

		$this->dropForeignKey("catalog_field_catalog_id", "catalog_field");

		$this->dropForeignKey("catalog_item_field_catalog_item_id", "catalog_item_field");
		$this->dropForeignKey("catalog_item_field_catalog_field_id", "catalog_item_field");

		$this->dropForeignKey("catalog_item_rating_catalog_item_id", "catalog_item_rating");
		$this->dropIndex("catalog_item_rating_value", "catalog_item_rating");

		$this->dropTable("catalog");
		$this->dropTable("catalog_menu");
		$this->dropTable("catalog_item");
		$this->dropTable("catalog_item_clone");
		$this->dropTable("catalog_size");
		$this->dropTable("catalog_field");
		$this->dropTable("catalog_item_field");
		$this->dropTable("catalog_brand");
		$this->dropTable("catalog_item_rating");

		return true;
	}
}