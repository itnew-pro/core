<?php echo CHtml::form(); ?>

	<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
	<?php $this->renderPartial("../content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php $imageUniqId = uniqid(); echo CHtml::activeLabel($model, "type"); ?>
		<?php echo CHtml::activeDropDownList($model, "type", $model->getTypeList()); ?>
	</div>

	<?php $this->renderPartial("../content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>