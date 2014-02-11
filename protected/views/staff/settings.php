<?php echo CHtml::form(); ?>

	<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
	<?php $this->renderPartial("../content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_group", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_group", array("class" => "checkbox-label")); ?>
	</div>

	<?php $this->renderPartial("../content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>