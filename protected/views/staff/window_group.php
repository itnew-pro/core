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

	<button
		class="button ajax"
		data-function="saveStaffGroupWindow"
		data-controller="staff"
		data-action="saveGroup?id=<?php echo $model->id; ?>"
		data-post=true
		data-modelId="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", $model->id ? "Update" : "Add"); ?></button>

<?php echo CHtml::endForm(); ?>