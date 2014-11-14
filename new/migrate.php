<?php

ini_set("error_reporting", E_ALL);
ini_set("display_errors", "On");

if (!mysql_connect("localhost", "dev2", "mypasS77")) {
	throw new Exception("Не удалось установить соединение с сервером MySQL");
}
if (!mysql_select_db("dev2")) {
	throw new Exception("Не удалось выбрать базу");
}
if (!mysql_query("SET NAMES 'utf8'") || !mysql_set_charset("utf8")) {
	throw new Exception("Не удалось задать кодировку для БД");
}

require(__DIR__ . "/system/db/Migration.php");

$dir = __DIR__ . "/migrations/";

$files = array();
$handle = opendir($dir);
if (!$handle) {
	throw new \Exception("Невозможно открыть директорию");
}

while (false !== ($file = readdir($handle))) {
	if ($file != "." && $file != "..") {
		$files[] = $file;
	}
}
closedir($handle);

sort($files);

foreach($files as $file) {

	require($dir . $file);

	$className = "\\migrations\\" . substr($file, 0, -4);

	$class = new $className;
	$class->run();
}