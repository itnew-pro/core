<?php
use itnew\models\Catalog;

/**
 * @var Catalog $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("../content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "price_type"); ?>:
		<?php echo CHtml::activeDropDownList(
			$model,
			"price_type",
			$model->getPriceTypeList(),
			array("empty" => Yii::t("catalog", "no"))
		); ?>
		<div class="form-block-container<?php echo (!$model->price_type) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "price_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "price_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "size_type"); ?>:
		<?php echo CHtml::activeDropDownList(
			$model,
			"size_type",
			$model->getSizeTypeList(),
			array("empty" => Yii::t("catalog", "no"))
		); ?>
		<div class="form-block-container<?php echo (!$model->size_type) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "size_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "size_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "new_type"); ?>:
		<?php echo CHtml::activeDropDownList(
			$model,
			"new_type",
			$model->getColorList(),
			array("empty" => Yii::t("catalog", "no"))
		); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "discount_type"); ?>:
		<?php echo CHtml::activeDropDownList(
			$model,
			"discount_type",
			$model->getColorList(),
			array("empty" => Yii::t("catalog", "no"))
		); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "date_type"); ?>:
		<?php echo CHtml::activeDropDownList(
			$model,
			"date_type",
			$model->getDateTypeList(),
			array("empty" => Yii::t("catalog", "no"))
		); ?>
		<div class="form-block-container<?php echo (!$model->date_type) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "date_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "date_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_rating", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_rating", array("class" => "checkbox-label")); ?>
		<div class="form-block-container<?php echo (!$model->is_rating) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "rating_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "rating_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_article", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_article", array("class" => "checkbox-label")); ?>
		<div class="form-block-container<?php echo (!$model->is_article) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "article_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "article_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_color", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_color", array("class" => "checkbox-label")); ?>
		<div class="form-block-container<?php echo (!$model->is_color) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "color_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "color_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_brand", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_brand", array("class" => "checkbox-label")); ?>
		<div class="form-block-container<?php echo (!$model->is_brand) ? " hide" : ""; ?>">
			<?php echo CHtml::activeCheckbox($model, "brand_in_short_card", array("class" => "checkbox")); ?>
			<?php echo CHtml::activeLabel($model, "brand_in_short_card", array("class" => "checkbox-label")); ?>
		</div>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_cover", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_cover", array("class" => "checkbox-label")); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_images", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_images", array("class" => "checkbox-label")); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_description", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_description", array("class" => "checkbox-label")); ?>
	</div>

	<div class="form-block">
		<?php echo CHtml::activeCheckbox($model, "is_text", array("class" => "checkbox")); ?>
		<?php echo CHtml::activeLabel($model, "is_text", array("class" => "checkbox-label")); ?>
	</div>

<?php $this->renderPartial("../content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>