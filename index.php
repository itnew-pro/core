<?php

exit();

$dbHost = "localhost";
$dbUser = "itnew";
$dbPass = "mypasS77";
$dbName = "_sites";

$host = $_SERVER["HTTP_HOST"];

if (
	mysql_connect($dbHost, $dbUser, $dbPass)
	&& mysql_select_db($dbName)
	&& mysql_query("set names 'utf8'")
) {
	$query = mysql_query("SELECT * FROM sites WHERE host = '{$host}'");
	$array = mysql_fetch_array($query);
	if ($array) {
		
		/**
		 * Settings
		 */
		define("DB_HOST",      "localhost");
		define("DB_USERNAME",  "itnew");
		define("DB_PASSWORD",  "mypasS77");
		define("DB_NAME",      "dev");
		define("LANG",         "ru");
		define("BASE_URL",     "");
		define("DOMAIN",       "dev.itnew.pro");

		/**
		 * Errors
		 */
		define('YII_DEBUG', true);
		ini_set ("error_reporting", E_ALL);
		ini_set ("display_errors", "On");
		ini_set ("register_globals", "Off");
		ini_set ("register_long_arrays", "Off");
		ini_set ("magic_quotes_gpc", "Off");
		ini_set ("magic_quotes_runtime", "Off");
		ini_set ("allow_url_fopen", "On");
		ini_set ("short_open_tag", "Off");

		/**
		 * Yii protected path
		 */
		$protectedDir = 
			__DIR__ .
			DIRECTORY_SEPARATOR .
			"protected";

		/**
		 * Yii application
		 */
		$yii = 
			$protectedDir .
			DIRECTORY_SEPARATOR .
			"vendors" .
			DIRECTORY_SEPARATOR .
			"yiisoft" .
			DIRECTORY_SEPARATOR .
			"yii" .
			DIRECTORY_SEPARATOR .
			"framework" .
			DIRECTORY_SEPARATOR .
			"yii.php";

		$config =
			$protectedDir .
			DIRECTORY_SEPARATOR .
			"config" .
			DIRECTORY_SEPARATOR .
			"main.php";
			
		require_once($yii);
		Yii::createWebApplication($config)->run();

	}
}