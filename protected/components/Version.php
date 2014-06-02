<?php

namespace itnew\components;

use CComponent;
use CDateTimeParser;
use Yii;
use itnew\models\Site;

/**
 * Файл класса Version.
 *
 * Проверяет текущую версию ядра и применяет ее к данному сайту.
 *
 * @author  Mikhail Vasilyev <mail@itnew.pro>
 * @link    http://www.itnew.pro/
 * @package components
 */
class Version extends CComponent
{

	/**
	 * Производит проверку и обновление до текущей версии
	 *
	 * @return void
	 */
	public function update()
	{
		$migrateTimes = $this->_getMigrateTimes();

		if ($migrateTimes) {
			$dir = Yii::app()->basePath . DIRECTORY_SEPARATOR . "migrations";

			$files = array();
			if ($handle = opendir($dir)) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$time = CDateTimeParser::parse(substr($file, 1, 13), "yyMMdd_HHmmss");
						$files[$time] = $file;
					}
				}
				closedir($handle);
			}

			if ($files) {
				echo "<div style=\"display: none;\">";

				$migrationsDown = array();
				$migrationsUp = array();

				foreach ($files as $time => $file) {
					if ($time > $migrateTimes["to"]) {
						$migrationsDown[$time] = $file;
					} else {
						if ($time > $migrateTimes["from"]) {
							$migrationsUp[$time] = $file;
						}
					}
				}

				if ($migrationsDown) {
					krsort($migrationsDown);
					foreach ($migrationsDown as $file) {
						require($dir . DIRECTORY_SEPARATOR . $file);
						$className = substr($file, 0, -4);
						$class = new $className;
						$class->safeDown();
					}
				}

				if ($migrationsUp) {
					ksort($migrationsUp);
					foreach ($migrationsUp as $file) {
						require($dir . DIRECTORY_SEPARATOR . $file);
						$className = substr($file, 0, -4);
						$class = new $className;
						$class->safeUp();
					}
				}

				Yii::app()->db->schema->refresh();

				echo "</div>";
			}
		}
	}

	/**
	 * Получает диапазон времени для непримененных миграций
	 *
	 * @return string[]
	 */
	private function _getMigrateTimes()
	{
		$from = 0;
		if (
			is_file(Yii::getPathOfAlias("application.models") . DIRECTORY_SEPARATOR . "Site.php")
			&& Yii::app()->db->schema->getTable('site')
		) {
			if ($site = Site::model()->find()) {
				if ($site->migrate_time) {
					$from = CDateTimeParser::parse($site->migrate_time, "yyyy-MM-dd hh:mm:ss");
					$site->migrate_time = Yii::app()->params["migrateTime"];
					$site->save();
				}
			}
		}

		$to = CDateTimeParser::parse(Yii::app()->params["migrateTime"], "yyyy-MM-dd hh:mm:ss");

		if ($from == $to) {
			return false;
		}

		if ($from > $to) {
			$from = $to;
		}

		return array("from" => $from, "to" => $to);
	}
}