<?php
/**
 * @var CController $this
 */
?>

<div class="add-panel">
	<i class="plus"></i>
	<a
		href="#"
		class="add dotted ajax"
		data-function="addSubpanel"
		data-controller="<?php echo Yii::app()->controller->id; ?>"
		data-action="settings"
		><?php echo Yii::t("common", "Add"); ?></a>
	<i class="arrow-blue"></i>
</div>