<?php
echo 1;
$dbHost = "localhost";
$dbUser = "itnew";
$dbPass = "mypasS77";
$dbName = "_sites";

$host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);

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
		define("SITE_ID",        $array["id"]);
		define("DB_USERNAME",    $array["db_user"]);
		define("DB_PASSWORD",    $array["db_pass"]);
		define("DB_NAME",        $array["db_name"]);
		define("LANG",           $array["lang"]);
		define("DOMAIN",         $array["host"]);
		define("ADMIN_LOGIN",    $array["admin_login"]);
		define("ADMIN_PASSWORD", $array["admin_password"]);

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

		$settings =
			$protectedDir .
			DIRECTORY_SEPARATOR .
			"config" .
			DIRECTORY_SEPARATOR .
			"settings.php";
		require_once($settings);
			
		require_once($yii);
		Yii::createWebApplication($config)->run();

	}
}