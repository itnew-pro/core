<?php

namespace migrations;

use system\db\Migration;

/**
 * Файл класса m141115_000010_texts.
 *
 * @package migrations
 */
class m141115_000010_texts extends Migration
{

	public function run()
	{
		$this->createTable(
			"texts",
			array(
				"id"     => "pk",
				"rows"   => "integer",
				"editor" => "integer",
				"tag"    => "integer",
				"text"   => "text",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);
	}
}