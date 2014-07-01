<?php
use itnew\controllers\ImagesController;
use itnew\models\Images;

/**
 * @var Images           $model
 * @var ImagesController $this
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("_window_list", compact("model")); ?>

	<button
		class="button ajax"
		data-function="saveWindow"
		data-controller="images"
		data-action="saveWindow?id=<?php echo $model->id; ?>"
		data-post=true
		data-id="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>