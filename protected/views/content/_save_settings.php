<?php
	echo CHtml::ajaxSubmitButton(
		Html::getButtonText($model),
		$this->createUrl(
			"ajax/index",
			array(
				"controller" => Yii::app()->controller->id,
				"action" => "saveSettings",
				"language" => Yii::app()->language,
				"id" => $model->id,
			)
		),
		array(
			"type" => "POST",
			"dataType" => "json",
			"beforeSend" => 'function() {
				$(".loader-subpanel-button").show();
				if (!$("#subpanel #Block_name").val()) {
					$("#subpanel .error-block-name-empty").show();
					$(".loader-subpanel-button").hide();
					return false;
				}
			}',
			"success" => 'function(data) {
				$("#subpanel").remove();
				$("#panel").remove();
				$("body").append(data["panel"]);
				$(".content-' . Yii::app()->controller->id . '-' . $model->id . '").replaceWith(data["content"]);
			}'
		),
		array(
			"class" => "button",
			"id" => uniqid(),
			"live" => false,
		)
	);
?>