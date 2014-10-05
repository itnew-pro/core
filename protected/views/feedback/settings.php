<?php
use itnew\models\Feedback;
use itnew\models\Site;

/**
 * @var Feedback $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("../content/_block_name", compact("model")); ?>

	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "email_to"); ?>
		<?php echo CHtml::activeTextField(
			$model,
			"email_to",
			array(
				"class"      => "blue-form has-errors",
				"data-error" => "email",
				"value"      => ($model->email_to) ? $model->email_to : Site::getEmail(),
			)
		); ?>
		<div class="error" id="<?php echo CHtml::activeId($model, "email_to"); ?>-email">
			<?php echo Yii::t("feedback", "Incorrect email"); ?>
		</div>
	</div>
	<div class="form-block">
	<div class="check-name-require-container">
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_name",
			array(
				"class"   => "checkbox",
				"checked" => $model->isNewRecord || $model->is_name
			)
		); ?>
		<div class="gray-field"></div>
		<?php echo CHtml::activeTextField(
			$model,
			"name_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("name_label"),
			)
		); ?>
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_name_required",
			array(
				"class" => "checkbox-required"
			)
		); ?>
		<?php echo CHtml::activeLabel(
			$model,
			"is_name_required",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>

	<div class="check-name-require-container">
		<?php echo CHtml::checkBox(
			"emailCheck",
			true,
			array(
				"class"    => "checkbox",
				"disabled" => true
			)
		); ?>
		<?php echo CHtml::activeTextField(
			$model,
			"email_from_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("email_from_label"),
			)
		); ?>
		<?php echo CHtml::checkBox(
			"emailRequired",
			true,
			array(
				"class"    => "checkbox-required",
				"disabled" => true
			)
		); ?>
		<?php echo CHtml::label(
			null,
			"emailRequired",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>

	<div class="check-name-require-container">
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_phone",
			array(
				"class"   => "checkbox",
				"checked" => $model->is_phone
			)
		); ?>
		<div class="gray-field"></div>
		<?php echo CHtml::activeTextField(
			$model,
			"phone_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("phone_label"),
			)
		); ?>
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_phone_required",
			array(
				"class" => "checkbox-required"
			)
		); ?>
		<?php echo CHtml::activeLabel(
			$model,
			"is_phone_required",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>

	<div class="check-name-require-container">
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_adress",
			array(
				"class"   => "checkbox",
				"checked" => $model->is_adress
			)
		); ?>
		<div class="gray-field"></div>
		<?php echo CHtml::activeTextField(
			$model,
			"adress_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("adress_label"),
			)
		); ?>
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_adress_required",
			array(
				"class" => "checkbox-required"
			)
		); ?>
		<?php echo CHtml::activeLabel(
			$model,
			"is_adress_required",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>

	<div class="check-name-require-container">
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_subject",
			array(
				"class"   => "checkbox",
				"checked" => $model->isNewRecord || $model->is_subject
			)
		); ?>
		<div class="gray-field"></div>
		<?php echo CHtml::activeTextField(
			$model,
			"subject_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("subject_label"),
			)
		); ?>
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_subject_required",
			array(
				"class" => "checkbox-required"
			)
		); ?>
		<?php echo CHtml::activeLabel(
			$model,
			"is_subject_required",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>

	<div class="check-name-require-container">
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_message",
			array(
				"class"   => "checkbox",
				"checked" => $model->isNewRecord || $model->is_message
			)
		); ?>
		<div class="gray-field"></div>
		<?php echo CHtml::activeTextField(
			$model,
			"message_label",
			array(
				"class" => "blue-form",
				"value" => $model->getAttributeLabel("message_label"),
			)
		); ?>
		<?php echo CHtml::activeCheckbox(
			$model,
			"is_message_required",
			array(
				"class" => "checkbox-required"
			)
		); ?>
		<?php echo CHtml::activeLabel(
			$model,
			"is_message_required",
			array(
				"class" => "checkbox-required-label"
			)
		); ?>
	</div>
	</div>
	<div class="form-block">
		<?php echo CHtml::activeLabel($model, "send_label"); ?>
		<?php echo CHtml::activeTextField(
			$model,
			"send_label",
			array(
				"class" => "blue-form",
				"value" => ($model->send_label) ? $model->send_label : Yii::t("feedback", "Send"),
			)
		); ?>
	</div>

<?php $this->renderPartial("../content/_save_settings", compact("model")); ?>

<?php echo CHtml::endForm(); ?>