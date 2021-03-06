<?php
use itnew\models\Section;

/**
 * @var Section $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("/partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("/partials/_seo", array("model" => $model->getSeo())); ?>

	<div class="remember form-block">
		<?php
		if ($model->main) {
			echo CHtml::activeCheckbox($model, "main", array("disabled" => "disabled", "class" => "checkbox"));
			echo CHtml::activeLabel($model, "main", array("class" => "checkbox-label checkbox-disabled"));
		} else {
			echo CHtml::activeCheckbox($model, "main", array("class" => "checkbox"));
			echo CHtml::activeLabel($model, "main", array("class" => "checkbox-label"));
		}
		?>
	</div>

<?php echo CHtml::activeHiddenField($model, "id"); ?>

	<button
		class="button ajax"
		data-function="saveSectionSubpanel"
		data-controller="section"
		data-action="saveSettings"
		data-post=true
		data-json=true
		><?php echo ($model->isNewRecord) ? Yii::t("common", "Add") : Yii::t("common", "Update"); ?></button>

<?php CHtml::endForm(); ?>