<?php

/**
 * Файл с настройками.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package config
 */

Yii::setPathOfAlias("itnew", __DIR__ . DIRECTORY_SEPARATOR . "..");

return array(
	'controllerNamespace' => '\itnew\controllers',
	'controllerPath'      => __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "controllers",
	"sourceLanguage" => "en_us",
	"language"       => LANG,
	"basePath"       => dirname(__FILE__) . DIRECTORY_SEPARATOR . "..",
	"name"           => "Сайт",
	"charset"        => "UTF-8",
	"import"         => array(
		"application.models.*",
		"application.components.*",
		"application.controllers.*",
		"application.helpers.*",
	),
	"preload"        => array("log"),
	"components"     => array(
		"db"           => array(
			"connectionString" => "mysql:host=localhost; dbname=" . DB_NAME,
			"emulatePrepare"   => true,
			"username"         => DB_USERNAME,
			"password"         => DB_PASSWORD,
			"charset"          => "utf8",
		),
		"urlManager"   => array(
			"urlFormat"      => "path",
			'showScriptName' => false,
			"rules"          => array(

				"<language:\w+>/ajax/<controller:\w+>/<action:\w+>/" => "site/index",
				"<language:\w+>/<section:[\w_-]+>/<level1:[\w_-]+>/" => "site/index",
				"<language:\w+>/<section:[\w_-]+>/"                  => "site/index",
				"<language:\w+>/"                                    => "site/index",
				"/"                                                  => "/site/index",
			),
		),
		"clientScript" => array(
			"packages" => array(
				"jquery"   => false,
				"jqueryui" => false,
			),
		),
		"log"          => array(
			"class"  => "CLogRouter",
			"routes" => array(
				array(
					"class"   => "CFileLogRoute",
					"levels"  => "*",
					"except"  => "system.*",
					"logFile" => "log.log",
				),
				array(
					"class"   => "CFileLogRoute",
					"levels"  => "error, warning",
					"except"  => "protected.*",
					"logFile" => "system.log",
				),
			),
		),
		"user"         => array(
			"allowAutoLogin" => true,
		),
		'image'        => array(
			'class'  => 'application.extensions.image.CImageComponent',
			'driver' => 'GD',
			'params' => array('directory' => '/opt/local/bin'),
		),
	),
	"modules"        => array(
		"gii" => array(
			"class"          => "system.gii.GiiModule",
			"password"       => "q123456",
			"ipFilters"      => false,
			"generatorPaths" => array("application.gii"),
		),
	),
	"params"         => array(
		"staticDir"   => "/var/www/vhosts/__ITnew__/__SITES__",
		"baseUrl"     => "",
		"siteId"      => SITE_ID,
		"domain"      => DOMAIN,
		"migrateTime" => "2014-03-04 00:00:00",
		"admin"       => array(
			"login"    => ADMIN_LOGIN,
			"password" => ADMIN_PASSWORD,
		),
	),
);