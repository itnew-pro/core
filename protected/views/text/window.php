<?php
use itnew\controllers\TextController;
use itnew\models\Text;

/**
 * @var TextController $this
 * @var Text           $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../text/_window", compact("model")); ?>

	<button
		class="button ajax"
		data-function="saveWindow"
		data-controller="text"
		data-action="saveWindow?id=<?php echo $model->id; ?>"
		data-post=true
		data-modelId="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>