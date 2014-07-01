<?php
use itnew\models\RecordsContent;

/**
 * @var RecordsContent $model
 */
?>

<?php echo CHtml::form(); ?>

<?php if ($model->records->cover) { ?>
	<div class="cover">
		<?php $this->renderPartial(
			"../images/_window_list",
			array(
				"model"       => $model->getCover(),
				"notMultiple" => true,
				"name"        => "Cover",
			)
		); ?>
	</div>
<?php } ?>

	<div class="<?php if ($model->records->cover) { ?>content-with-cover<?php } ?>">
		<span class="is_published">
			<?php echo CHtml::activeCheckbox($model, "is_published", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "is_published", array("class" => "checkbox-label")); ?>
		</span>

		<span class="date">
			<?php echo CHtml::activeLabel($model, "date"); ?>
			<?php echo CHtml::activeTextField(
				$model,
				"date",
				array("value" => $model->getWindowDate(), "class" => "datepicker blue-form form-medium")
			); ?>
		</span>

		<?php echo CHtml::activeHiddenField($model, "id"); ?>

		<?php $this->renderPartial("/partials/_seo", array("model" => $model->getSeo())); ?>

		<?php $this->renderPartial(
			"../text/_window",
			array("model" => $model->getDescription(), "name" => "Description")
		); ?>

		<?php if ($model->records->images) { ?>
			<?php $this->renderPartial("/images/_window_list", array("model" => $model->getImages())); ?>
		<?php } ?>

		<?php $this->renderPartial("/text/_window", array("model" => $model->getText())); ?>
	</div>

	<button
		class="button ajax"
		data-function="saveRecordsFormWindow"
		data-controller="records"
		data-action="saveForm?id=<?php echo $model->id; ?>"
		data-post=true
		data-id="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>