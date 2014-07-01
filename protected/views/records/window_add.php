<?php
use itnew\models\RecordsContent;

/**
 * @var RecordsContent $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("/partials/_seo", array("model" => $model->getSeo())); ?>

<?php echo CHtml::activeHiddenField($model, "records_id"); ?>

	<button
		class="button ajax"
		data-function="saveNewRecordsWindow"
		data-controller="records"
		data-action="saveAdd?id=<?php echo $model->id; ?>"
		data-post=true
		data-json=true
		data-id="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Add"); ?></button>

<?php echo CHtml::endForm(); ?>