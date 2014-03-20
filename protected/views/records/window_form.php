<?php echo CHtml::form(); ?>

	<?php if ($model->records->cover) { ?>
		<div class="cover">
			<?php $this->renderPartial("../images/_window_list", array(
				"model" => $model->getCover(),
				"notMultiple" => true,
				"name" => "Cover",
			)); ?>
		</div>
	<?php } ?>

	<div class="<?php if ($model->records->cover) { ?>content-with-cover<?php } ?>">
		<span class="is_published">
			<?php echo CHtml::activeCheckbox($model, "is_published", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "is_published", array("class" => "checkbox-label")); ?>
		</span>

		<span class="date">
			<?php echo CHtml::activeLabel($model, "date"); ?>
			<?php echo CHtml::activeTextField($model, "date", array("value" => $model->getWindowDate(), "class" => "blue-form form-medium")); ?>
		</span>

		<?php echo CHtml::activeHiddenField($model, "id"); ?>

		<?php $this->renderPartial("../partials/_seo", array("model" => $model->getSeo())); ?>

		<?php $this->renderPartial("../text/_window", array("model" => $model->getDescription(), "name" => "Description")); ?>

		<?php if ($model->records->images) { ?>
			<?php $this->renderPartial("../images/_window_list", array("model" => $model->getImages())); ?>
		<?php } ?>

		<?php $this->renderPartial("../text/_window", array("model" => $model->getText())); ?>
	</div>

	<?php $this->renderPartial("../content/_window_button", array(
		"model" => $model,
		"button" => $model->id ? "Update" : "Add",
		"action" => "saveForm",
		"success" => '
			hideWindow("records-form");
			hideWindow("records");
			$("body").append(data);
			showWindow("records");
		',
	)); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("image-file-field-{$model->id}", '
		$("#RecordsContent_date").datepicker({
			dateFormat: "dd.mm.yy"
		});
	');
?>