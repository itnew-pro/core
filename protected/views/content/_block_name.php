<?php

use itnew\models\Block;

/**
 * @var mixed $model
 */
?>

<div class="form-block">
	<?php
	$block = $model->getBlock();
	echo CHtml::activeLabel($block, "name");
	echo CHtml::activeTextField(
		$block,
		"name",
		array(
			"class"      => "blue-form has-errors",
			"data-error" => "required",
		)
	);
	?>
	<div class="error" id="<?php echo CHtml::activeId($block, "name"); ?>-required">
		<?php echo Yii::t("block", "Block name can not be empty"); ?>
	</div>
</div>