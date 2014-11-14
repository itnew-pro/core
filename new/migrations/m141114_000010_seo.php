<?php

namespace migrations;

use system\db\Migration;

/**
 * Файл класса m141114_000010_seo.
 *
 * @package migrations
 */
class m141114_000010_seo extends Migration
{

	public function run()
	{
		$this->createTable(
			"seo",
			array(
				"id"          => "pk",
				"name"        => "string",
				"url"         => "string",
				"title"       => "string",
				"keywords"    => "string",
				"description" => "text",
			),
			"ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci"
		);

		$this->createIndex("seo_url", "seo", "url");
	}
}