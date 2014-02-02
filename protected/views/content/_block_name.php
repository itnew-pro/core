<div class="form-block">
	<?php
		echo CHtml::activeLabel($model->getBlock(), "name");
		echo CHtml::activeTextField($model->getBlock(), "name", array("class" => "blue-form"));
	?>
	<div class="error error-block-name-empty">
		<?php echo Yii::t("block", "Block name can not be empty"); ?>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScript("block", '
		$("#subpanel #Block_name").on("keyup", function() {
			$("#subpanel .error").hide();
		});
	');
?>