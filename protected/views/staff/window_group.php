<?php
use itnew\models\StaffGroup;

/**
 * @var StaffGroup $model
 */
?>

<?php echo CHtml::form(); ?>

	<div class="form-internal">
		<?php echo CHtml::activeLabel($model, "name"); ?>
		<?php echo CHtml::activeTextField($model, "name", array("class" => "blue-form")); ?>
	</div>

<?php echo CHtml::activeHiddenField($model, "staff_id"); ?>
<?php echo CHtml::activeHiddenField($model, "id"); ?>

<?php $this->renderPartial(
	"../content/_window_button",
	array(
		"model"   => $model,
		"button"  => $model->id ? "Update" : "Add",
		"action"  => "saveGroup",
		"success" => '
			hideWindow("staff-group");
			hideWindow("staff");
			$("body").append(data);
			showWindow("staff");
		',
	)
); ?>

<?php echo CHtml::endForm(); ?>