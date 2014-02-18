<?php

	if (empty($action)) {
		$action = "saveWindow";
	}

	if (empty($button)) {
		$button = "Update";
	}

	if (empty($success)) {
		$success = '
			hideWindow("' . Yii::app()->controller->id . '");
			$(".content-' . Yii::app()->controller->id . '-' . $model->id . '")
				.html($("#' . Yii::app()->controller->id . '-' . $model->id . '").val());
		';
	}

	echo CHtml::ajaxSubmitButton(
		Yii::t("common", $button), 
		$this->createUrl(
			"ajax/index",
			array(
				"controller" => Yii::app()->controller->id,
				"action" => $action,
				"language" => Yii::app()->language,
				"id" => $model->id,
			)
		),
		array(
			"type" => "POST",
			"beforeSend" => 'function() {
				$(".loader-window-button").show();
			}',
			"success" => 'function(data) {
				$(".loader-window-button").hide();
				' . $success . '
			}'
		),
		array(
			"class" => "button",
			"id" => uniqid(),
			"live" => false,
		)
	);
?>