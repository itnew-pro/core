<?php

use itnew\components\Html;
use itnew\models\Feedback;

/**
 * @var Feedback $model
 */
?>

<div class="content-feedback content-feedback-<?php echo $model->id; ?>">
	<?php echo CHtml::form(); ?>

	<?php
	if ($model->is_name) {
		echo CHtml::textField(
			"feedback{$model->id}Name",
			null,
			array(
				"placeholder" => $model->getAttributeLabel("name_label") . (($model->is_name_required) ? "*" : ""),
				"class"       => ($model->is_name_required) ? "has-error" : "",
				"data-error"  => ($model->is_name_required) ? "required" : "",
			)
		);
	}
	?>
	<?php if ($model->is_name_required) { ?>
		<div class="error" id="feedback<?php echo $model->id; ?>Name-required">
			<?php echo Yii::t(
				"feedback",
				"Field «field» is required",
				array(
					"field" => $model->getAttributeLabel("name_label")
				)
			); ?>
		</div>
	<?php } ?>

	<?php
	echo CHtml::textField(
		"feedback{$model->id}Email",
		null,
		array(
			"placeholder" => $model->getAttributeLabel("email_from_label") . "*",
			"class"       => "has-error",
			"data-error"  => "emailRequired",
		)
	);
	?>
	<div class="error" id="feedback<?php echo $model->id; ?>Email-emailRequired">
		<?php echo Yii::t("feedback", "Incorrect email"); ?>
	</div>

	<?php
	if ($model->is_phone) {
		echo CHtml::textField(
			"feedback{$model->id}Phone",
			null,
			array(
				"placeholder" => $model->getAttributeLabel("phone_label") . (($model->is_phone_required) ? "*" : ""),
				"class"       => ($model->is_phone_required) ? "has-error" : "",
				"data-error"  => ($model->is_phone_required) ? "required" : "",
			)
		);
	}
	?>
	<?php if ($model->is_phone_required) { ?>
		<div class="error" id="feedback<?php echo $model->id; ?>Phone-required">
			<?php echo Yii::t(
				"feedback",
				"Field «field» is required",
				array(
					"field" => $model->getAttributeLabel("phone_label")
				)
			); ?>
		</div>
	<?php } ?>

	<?php
	if ($model->is_adress) {
		echo CHtml::textField(
			"feedback{$model->id}Adress",
			null,
			array(
				"placeholder" => $model->getAttributeLabel("adress_label") . (($model->is_adress_required) ? "*" : ""),
				"class"       => ($model->is_adress_required) ? "has-error" : "",
				"data-error"  => ($model->is_adress_required) ? "required" : "",
			)
		);
	}
	?>
	<?php if ($model->is_adress_required) { ?>
		<div class="error" id="feedback<?php echo $model->id; ?>Adress-required">
			<?php echo Yii::t(
				"feedback",
				"Field «field» is required",
				array(
					"field" => $model->getAttributeLabel("adress_label")
				)
			); ?>
		</div>
	<?php } ?>

	<?php
	if ($model->is_subject) {
		echo CHtml::textField(
			"feedback{$model->id}Subject",
			null,
			array(
				"placeholder" =>
					$model->getAttributeLabel("subject_label") .
					(($model->is_subject_required) ? "*" : ""),
				"class"       => ($model->is_subject_required) ? "has-error" : "",
				"data-error"  => ($model->is_subject_required) ? "required" : "",
			)
		);
	}
	?>
	<?php if ($model->is_subject_required) { ?>
		<div class="error" id="feedback<?php echo $model->id; ?>Subject-required">
			<?php echo Yii::t(
				"feedback",
				"Field «field» is required",
				array(
					"field" => $model->getAttributeLabel("subject_label")
				)
			); ?>
		</div>
	<?php } ?>

	<?php
	if ($model->is_message) {
		echo CHtml::textArea(
			"feedback{$model->id}Message",
			null,
			array(
				"placeholder" =>
					$model->getAttributeLabel("message_label") .
					(($model->is_message_required) ? "*" : ""),
				"class"       => ($model->is_message_required) ? "has-error" : "",
				"data-error"  => ($model->is_message_required) ? "required" : "",
			)
		);
	}
	?>
	<?php if ($model->is_message_required) { ?>
		<div class="error" id="feedback<?php echo $model->id; ?>Message-required">
			<?php echo Yii::t(
				"feedback",
				"Field «field» is required",
				array(
					"field" => $model->getAttributeLabel("message_label")
				)
			); ?>
		</div>
	<?php } ?>

	<?php echo CHtml::hiddenField("id", $model->id); ?>

	<?php echo Html::getButtonWithLoader(
		"feedback-button",
		($model->send_label) ? $model->send_label : Yii::t("feedback", "Send")
	); ?>


	<?php echo CHtml::endForm(); ?>
	<div class="success"><?php echo Yii::t("feedback", "Message has been successfully sent!") ?></div>
	<div class="not-success"><?php echo Yii::t(
			"feedback",
			"Sorry. An error has occurred. We already know about it."
		) ?></div>
</div>