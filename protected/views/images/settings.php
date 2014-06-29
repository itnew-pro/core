<?php
use itnew\models\Images;
use itnew\controllers\ImagesController;

/**
 * @var Images           $model
 * @var ImagesController $this
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("/partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("/content/_block_name", compact("model")); ?>

	<div class="form-block">
		<div class="form-internal">
			<?php echo CHtml::activeLabel($model, "width"); ?>
			<?php echo CHtml::activeTextField($model, "width", array("class" => "blue-form form-small")); ?>
			px
		</div>
		<div class="form-internal">
			<?php echo CHtml::activeLabel($model, "height"); ?>
			<?php echo CHtml::activeTextField($model, "height", array("class" => "blue-form form-small")); ?>
			px
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "view"); ?>
		<?php echo CHtml::activeDropDownList($model, "view", $model->getViewList(), array("id" => "imagesViewType")); ?>
	</div>

	<div class="form-block hide form-style">
		<div class="form-internal">
			<?php echo CHtml::activeLabel($model, "thumb_width"); ?>
			<?php echo CHtml::activeTextField($model, "thumb_width", array("class" => "blue-form form-small")); ?>
			px
		</div>
		<div class="form-internal">
			<?php echo CHtml::activeLabel($model, "thumb_height"); ?>
			<?php echo CHtml::activeTextField($model, "thumb_height", array("class" => "blue-form form-small")); ?>
			px
		</div>
	</div>

<?php $this->renderPartial("/content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>