<?php
/**
 * @var CActiveRecord $model
 */
?>

<?php if (!$model->isNewRecord) { ?>
	<div class="subpanel-links form-block">
		<a
			href="#"
			class="delete dotted ajax"
			data-function="updatePanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="delete?id=<?php echo $model->id; ?>"
			data-confirm=true
			><?php echo Yii::t("common", "Delete"); ?></a>
		<a
			href="#"
			class="dotted ajax"
			data-function="updatePanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="duplicate?id=<?php echo $model->id; ?>"
			data-confirm=true
			><?php echo Yii::t("common", "Duplicate"); ?></a>
	</div>
<?php } ?>