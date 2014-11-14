<?php

namespace migrations;

use system\db\Migration;

/**
 * Файл класса m141114_000040_grids.
 *
 * @package migrations
 */
class m141114_000040_grids extends Migration
{

	public function run()
	{
		$this->createTable(
			"grids",
			array(
				"id"         => "pk",
				"section_id" => "integer",
				"block_id"   => "integer",
				"line"       => "integer",
				"left"       => "integer",
				"top"        => "integer",
				"width"      => "integer",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->addForeignKey("grids_section_id", "grids", "section_id", "sections", "id");
		$this->addForeignKey("grids_block_id", "grids", "block_id", "blocks", "id");
	}
}