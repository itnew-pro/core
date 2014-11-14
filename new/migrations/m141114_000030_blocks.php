<?php

namespace migrations;

use system\db\Migration;

/**
 * Файл класса m141114_000030_blocks.
 *
 * @package migrations
 */
class m141114_000030_blocks extends Migration
{

	public function run()
	{
		$this->createTable(
			"blocks",
			array(
				"id"       => "pk",
				"language" => "integer",
				"name"     => "string",
				"type"     => "integer",
				"content"  => "integer",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createIndex("blocks_language", "blocks", "language");
		$this->createIndex("blocks_type", "blocks", "type");
		$this->createIndex("blocks_content", "blocks", "content");
	}
}