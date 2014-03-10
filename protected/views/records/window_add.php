<?php echo CHtml::form(); ?>

	<?php $this->renderPartial("../partials/_seo", array("model" => $model->getSeo())); ?>

	<?php echo CHtml::activeHiddenField($model, "records_id"); ?>

	<?php $this->renderPartial("../content/_window_button", array(
		"model" => $model,
		"button" => "Add",
		"dataType" => "JSON",
		"action" => "saveAdd",
		"success" => '
			if (data["errorClass"]) {
				$(".window-records-add ." + data["errorClass"]).show();
			} else {
				hideWindow("records-add");
				hideWindow("records");
				$("body").append(data["recordsForm"]);
				showWindow("records-form");
				$("body").append(data["records"]);
				showWindow("records");
			}
		',
	)); ?>

<?php echo CHtml::endForm(); ?>