<?php
use itnew\models\Section;
use itnew\components\Html;

/**
 * @var Section $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../partials/_delete_duplicate", compact("model")); ?>
<?php $this->renderPartial("../partials/_seo", array("model" => $model->getSeo())); ?>

	<div class="remember form-block">
		<?php
		if ($model->main) {
			echo CHtml::activeCheckbox($model, "main", array("disabled" => "disabled", "class" => "checkbox"));
			echo CHtml::activeLabel($model, "main", array("class" => "checkbox-label checkbox-disabled"));
		} else {
			echo CHtml::activeCheckbox($model, "main", array("class" => "checkbox"));
			echo CHtml::activeLabel($model, "main", array("class" => "checkbox-label"));
		}
		?>
	</div>

<?php echo CHtml::activeHiddenField($model, "id"); ?>

<?php
echo CHtml::ajaxSubmitButton(
	Html::getButtonText($model),
	$this->createUrl(
		"ajax/index",
		array(
			"controller" => "section",
			"action"     => "saveSettings",
			"language"   => Yii::app()->language,
		)
	),
	array(
		"type"       => "POST",
		"dataType"   => "json",
		"beforeSend" => 'function() {
					$(".loader-subpanel-button").show();
				}',
		"success"    => 'function(data) {
					$(".loader-subpanel-button").hide();
					if (data["error"]) {
						$(".error-" + data["error"]).show();
					} else {
						$("#subpanel").remove();
						$("#panel").remove();
						$("body").append(data["panel"]);
					}
				}'
	),
	array(
		"class" => "button",
		"id"    => uniqid(),
		"live"  => false,
	)
);
?>

<?php CHtml::endForm(); ?>