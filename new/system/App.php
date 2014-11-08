<?php

namespace system;

use system\db\Db;
use controllers\Section;

class App
{

	public static $classMap = array();
	public static $rootDir = "";

	public static function run($config = null)
	{
		spl_autoload_register(array('system\App', "autoload"));

		$config = require($config);

		ini_set("register_globals", "Off");
		if ($config["isDebug"]) {
			ini_set("error_reporting", E_ALL);
			ini_set("display_errors", "On");
		} else {
			ini_set("error_reporting", false);
			ini_set("display_errors", "Off");
		}

		self::$rootDir = $config["rootDir"];

		Db::setConnect(
			$config["db"]["host"],
			$config["db"]["user"],
			$config["db"]["password"],
			$config["db"]["base"],
			$config["db"]["charset"]
		);

		self::_runController(self::_parseUrl($config["baseUrl"]));
	}

	private static function _runController($params)
	{
		$action = "actionIndex";
		$language = "";
		$section = "";
		$param1 = "";
		$param2 = "";

		if (isset($params[0])) {
			$language = $params[0];
		}

		if (isset($params[1])) {
			$section = $params[1];
		}

		$controller = new Section($language, $section, $param1, $param2);
		$controller->$action();
	}

	private static function _parseUrl($baseUrl = "/")
	{
		$items = array();

		$url = substr($_SERVER["REQUEST_URI"], strlen($baseUrl));
		if (!$url) {
			return $items;
		}

		$explodeForGet = explode("?", $url, 2);

		$urlExplode = explode("/", $explodeForGet[0]);
		foreach($urlExplode as $item) {
			if ($item) {
				$items[] = $item;
			}
		}

		return $items;
	}

	public static function autoload($className)
	{
		if (array_key_exists($className, self::$classMap)) {
			return false;
		}

		include(self::$rootDir . str_replace("\\", "/", $className) . ".php");
		self::$classMap[] = $className;

		return true;
	}
}