<?php
	if ($model->getEditorClass()) {
		Yii::app()->clientScript->registerScript("textWindow", '
			tinyMCE.init({
				selector: ".tinymce",
			});
			$(".window .button").on("click", function(){
				tinyMCE.get("text-' . $model->id . '").save();
			});
		');
	}
?>

<?php echo CHtml::form(); ?>

	<?php 
		echo CHtml::activeTextArea($model, "text", array(
			"rows" => $model->rows,
			"class" => "textarea" . $model->getEditorClass(),
			"id" => "text-" . $model->id,
		));
	?>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>