<?php echo CHtml::form(); ?>

	<div class="form-internal">
		<?php echo CHtml::activeLabel($model, "name"); ?>
		<?php echo CHtml::activeTextField($model, "name", array("class" => "blue-form")); ?>
	</div>

	<?php $this->renderPartial("../content/_window_button", array(
		"model" => $model,
		"button" => "Add",
		"action" => "saveGroup",
	)); ?>

<?php echo CHtml::endForm(); ?>