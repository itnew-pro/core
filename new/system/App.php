<?php

namespace system;

use system\db\Db;
use controllers\SectionController;
use Exception;

/**
 * Файл класса App.
 *
 * Приложение
 *
 * @package system
 */
class App
{

	public static $isDebug = false;

	/**
	 * Подключенные файлы класов
	 *
	 * @var array
	 */
	public static $classMap = array();

	/**
	 * Корневая директория
	 *
	 * @var string
	 */
	public static $rootDir = "";

	/**
	 * Идентификатор языка
	 *
	 * @var integer
	 */
	public static $languageId = 0;

	/**
	 * Идентификатор русского языка
	 *
	 * @var integer
	 */
	const LANGUAGE_RU = 1;

	/**
	 * Список языков
	 *
	 * @var array
	 */
	public static $languageList = array(
		"ru" => self::LANGUAGE_RU,
	);

	/**
	 * Запуск приложения
	 *
	 * @param string $config путь до файла настроек
	 *
	 * @throws Exception
	 *
	 * @return void
	 */
	public static function run($config = null)
	{
		$startTime = microtime(true);

		spl_autoload_register(array('system\App', "autoload"));

		$config = require($config);

		self::$isDebug = $config["isDebug"];

		ini_set("register_globals", "Off");
		if (self::$isDebug) {
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

		$host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
		$siteInfo = Db::executeQuery("SELECT * FROM sites WHERE host = '{$host}'");
		if (!$siteInfo) {
			throw new Exception("Не найден сайт");
		}

		self::$languageId = self::$languageList[$siteInfo["lang"]];

		Db::setConnect(
			$config["db"]["host"],
			$siteInfo["db_user"],
			$siteInfo["db_pass"],
			$siteInfo["db_name"],
			$config["db"]["charset"],
			true
		);

		self::_runController(self::_parseUrl($config["baseUrl"]));

		if (self::$isDebug) {
			$time = microtime(true) - $startTime;
			echo "<script>console.log(\"Время выполнения скрипта: {$time} сек.\");</script>";
		}
	}

	/**
	 * Производит запуск контроллера
	 *
	 * @param string[] $params разбитый на куски URL
	 *
	 * @return void
	 */
	private static function _runController($params = array())
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

		$controller = new SectionController($language, $section, $param1, $param2);
		$controller->$action();
	}

	/**
	 * Разбивает строку
	 *
	 * @param string $baseUrl базовый URL
	 *
	 * @return string[]
	 */
	private static function _parseUrl($baseUrl = "/")
	{
		$items = array();

		$url = substr($_SERVER["REQUEST_URI"], strlen($baseUrl));
		if (!$url) {
			return $items;
		}

		$explodeForGet = explode("?", $url, 2);

		$urlExplode = explode("/", $explodeForGet[0]);
		foreach ($urlExplode as $item) {
			if ($item) {
				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * Автоматическая загрузка классов
	 *
	 * @param string $className название класса
	 *
	 * @return bool
	 */
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