<?php

namespace migrations;

use system\db\Migration;

/**
 * Файл класса m141114_000020_sections.
 *
 * @package migrations
 */
class m141114_000020_sections extends Migration
{

	public function run()
	{
		$this->createTable(
			"sections",
			array(
				"id"       => "pk",
				"seo_id"   => "integer",
				"language" => "integer",
				"width"    => "integer",
				"is_main"  => "boolean",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->addForeignKey("sections_seo_id", "sections", "seo_id", "seo", "id");
		$this->createIndex("sections_language_id", "sections", "language_id");
		$this->createIndex("sections_is_main", "sections", "is_main");
	}
}