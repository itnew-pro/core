<?php
use itnew\models\Menu;
use itnew\controllers\MenuController;

/**
 * @var MenuController $this
 * @var Menu           $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("/partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("/content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "type"); ?>
		<?php echo CHtml::activeDropDownList($model, "type", $model->getTypeListLabels()); ?>
	</div>

<?php $this->renderPartial("/content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>