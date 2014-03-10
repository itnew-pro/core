<?php

Yii::setPathOfAlias(
	"cms",
	__DIR__ .
	DIRECTORY_SEPARATOR .
	".." .
	DIRECTORY_SEPARATOR .
	".." .
	DIRECTORY_SEPARATOR
);

return array(
	"sourceLanguage" => "en_us",
    "language" => LANG,

	"basePath" => dirname(__FILE__) . DIRECTORY_SEPARATOR . "..",
	"name" => "Сайт",
	"charset" => "UTF-8",

	"import" => array(
		"application.models.*",
		"application.components.*",
		"application.controllers.*",
		"application.helpers.*",
	),

	"preload" => array("log"),

	"components" => array(
		"db" => array(
			"connectionString" => "mysql:host=localhost; dbname=" . DB_NAME,
			"emulatePrepare"   => true,
			"username"         => DB_USERNAME,
			"password"         => DB_PASSWORD,
			"charset"          => "utf8",
		),

		"urlManager"   => array(
			"urlFormat"      => "path",
			"rules"          => array(
				"/" => "/site/index",
				"<language:\w+>"=>"site/index",
				"<language:\w+>/<section:[\w_-]+>"=>"site/index",
				"<language:\w+>/ajax/<controller:\w+>/<action:\w+>/"=>"site/index",
			),
		),

		"clientScript" => array(
			"packages" => array(
				"jquery" => false,
				"jqueryui" => false, 
			),
		),

		"log"=>array(
            "class"=>"CLogRouter",
            "routes"=>array(
            	array(
                    "class"=>"CFileLogRoute",
                    "levels"=>"*",
                    "except"=>"system.*",
                    "logFile"=>"log.log",
                ),
                array(
                    "class"=>"CFileLogRoute",
                    "levels"=>"error, warning",
                    "except"=>"protected.*",
                    "logFile"=>"system.log",
                ),
            ),
        ),

		"user" => array(
			"allowAutoLogin" => true,
		),

		'image'=>array(
          'class'=>'application.extensions.image.CImageComponent',
            'driver'=>'GD',
            'params'=>array('directory'=>'/opt/local/bin'),
        ),
	),

	"modules"    => array(
		"gii" => array(
			"class"          => "system.gii.GiiModule",
			"password"       => "q123456",
			"ipFilters"      => false,
			"generatorPaths" => array("application.gii"),
		),
	),

	"params" => array(
		"staticDir"   => "/var/www/itnew/data/_SITES_",
		"baseUrl"     => "",
		"siteId"      => SITE_ID,
		"domain"      => DOMAIN,
		"migrateTime" => "2014-03-04 00:00:00",

		"admin" => array(
			"login"    => ADMIN_LOGIN,
			"password" => ADMIN_PASSWORD,
		),
	),
);