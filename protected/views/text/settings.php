<?php
use itnew\models\Text;

/**
 * @var Text $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("../content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "editor", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "editor", array("class" => "checkbox-label")); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "rows"); ?>:
		<?php echo CHtml::activeDropDownList($model, "rows", $model->getRowsList()); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "tag"); ?>:
		<?php echo CHtml::activeDropDownList($model, "tag", $model->tagList); ?>
	</div>

<?php $this->renderPartial("../content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>