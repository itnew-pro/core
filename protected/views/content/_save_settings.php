<?php
use itnew\components\Html;

/**
 * @var mixed $model
 */
?>

<button
	class="button ajax"
	data-function="saveSettings"
	data-controller="<?php echo Yii::app()->controller->id; ?>"
	data-action="saveSettings?id=<?php echo $model->id; ?>"
	data-post=true
	data-json=true
	data-modelId="<?php echo $model->id; ?>"
	><?php echo Html::getButtonText($model); ?></button>