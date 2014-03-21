<?php

class Html extends CHtml
{

	public static function loader($class = false)
	{
		if ($class) {
			$class = " loader-{$class}";
		}
		return "
			<div class=\"loader{$class}\">
				<div class=\"f_circleG frotateG_01\"></div>
				<div class=\"f_circleG frotateG_02\"></div>
				<div class=\"f_circleG frotateG_03\"></div>
				<div class=\"f_circleG frotateG_04\"></div>
				<div class=\"f_circleG frotateG_05\"></div>
				<div class=\"f_circleG frotateG_06\"></div>
				<div class=\"f_circleG frotateG_07\"></div>
				<div class=\"f_circleG frotateG_08\"></div>
			</div>
		";
	}

	/**
	 * Gets submit button text for subpanel
	 *
	 * @return string
	 */
	public static function getButtonText($model = null)
	{
		if ($model) {
			if (!$model->isNewRecord) {
				return Yii::t("common", "Update");
			}
		}
		return Yii::t("common", "Add");
	}

}