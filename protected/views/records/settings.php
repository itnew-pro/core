<?php
use itnew\models\Records;
use itnew\models\Images;

/**
 * @var Records $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("/partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("/content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "date"); ?>
		<?php echo CHtml::activeDropDownList($model, "date", $model->getDateTypes()); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_detail", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_detail", array("class" => "checkbox-label")); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox(
			$model,
			"isCover",
			array("class" => "checkbox", "checked" => $model->hasCover())
		); ?>
		<?php echo CHtml::activeLabel($model, "isCover", array("class" => "checkbox-label")); ?>

		<div class="form-block-container form-block-container-cover">
			<div class="form-block">
				<div class="form-internal">
					<?php echo CHtml::activeLabel($model->getCover(), "width"); ?>
					<?php echo CHtml::activeTextField(
						$model->getCover(),
						"width",
						array("class" => "blue-form form-small", "name" => "Cover[width]")
					); ?>
					px
				</div>
				<div class="form-internal">
					<?php echo CHtml::activeLabel($model->getCover(), "height"); ?>
					<?php echo CHtml::activeTextField(
						$model->getCover(),
						"height",
						array("class" => "blue-form form-small", "name" => "Cover[height]")
					); ?>
					px
				</div>
			</div>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox(
			$model,
			"isImages",
			array("class" => "checkbox", "checked" => $model->hasImages())
		); ?>
		<?php echo CHtml::activeLabel($model, "isImages", array("class" => "checkbox-label")); ?>

		<div class="form-block-container form-block-container-images">
			<div class="form-block">
				<div class="form-internal">
					<?php echo CHtml::activeLabel($model->getImages(), "width"); ?>
					<?php echo CHtml::activeTextField(
						$model->getImages(),
						"width",
						array("class" => "blue-form form-small")
					); ?>
					px
				</div>
				<div class="form-internal">
					<?php echo CHtml::activeLabel($model->getImages(), "height"); ?>
					<?php echo CHtml::activeTextField(
						$model->getImages(),
						"height",
						array("class" => "blue-form form-small")
					); ?>
					px
				</div>
			</div>

			<div class="form-block">
				<?php echo CHtml::activeLabel($model->getImages(), "view"); ?>
				<?php echo CHtml::activeDropDownList(
					$model->getImages(),
					"view",
					$model->getImages()->getViewList(),
					array("id" => "images-view")
				); ?>
			</div>

			<div class="form-block">
				<div class="hide form-style-images-view form-thumb-images-view">
					<div class="form-internal">
						<?php echo CHtml::activeLabel($model->getImages(), "thumb_width"); ?>
						<?php echo CHtml::activeTextField(
							$model->getImages(),
							"thumb_width",
							array("class" => "blue-form form-small")
						); ?>
						px
					</div>
					<div class="form-internal">
						<?php echo CHtml::activeLabel($model->getImages(), "thumb_height"); ?>
						<?php echo CHtml::activeTextField(
							$model->getImages(),
							"thumb_height",
							array("class" => "blue-form form-small")
						); ?>
						px
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $this->renderPartial("/content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>