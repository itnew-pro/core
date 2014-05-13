<?php echo CHtml::form(); ?>

	<div class="text-form">
		<?php
		echo CHtml::activeLabel($model, "login");
		echo CHtml::activeTextField($model, "login", array("class" => "text-field"));
		?>
		<div class="error error-user-empty">
			<?php echo Yii::t("admin", "User can not be empty"); ?>
		</div>
		<div class="error error-user-not-exist">
			<?php echo Yii::t("admin", "User does not exist"); ?>
		</div>
	</div>

	<div class="text-form">
		<?php
		echo CHtml::activeLabel($model, "password");
		echo CHtml::activePasswordField($model, "password", array("class" => "text-field"));
		?>
		<div class="error error-password-empty">
			<?php echo Yii::t("admin", "Password can not be empty"); ?>
		</div>
		<div class="error error-password-wrong">
			<?php echo Yii::t("admin", "Incorrect password"); ?>
		</div>
	</div>

	<div class="checkbox-form">
		<?php
		echo CHtml::activeCheckbox($model, "remember");
		echo CHtml::activeLabel($model, "remember");
		?>
	</div>

<?php
echo CHtml::ajaxSubmitButton(
	Yii::t("admin", "Ok"),
	$this->createUrl(
		"ajax/index",
		array(
			"controller" => "login",
			"action"     => "login",
			"language"   => Yii::app()->language,
		)
	),
	array(
		"type"       => "POST",
		"beforeSend" => 'function() {
					$(".loader-window-button").show();
				}',
		"success"    => 'function(data) {
					$(".loader-window-button").hide();
					if (data) {
						$(".window-login .error-" + data).show();
					} else {
						hideWindow("login");
						$("#login-button").addClass("hide");
						$("#logout-button").removeClass("hide");
						window.location.replace("");
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

<?php echo CHtml::endForm(); ?>

<?php
Yii::app()->clientScript->registerScript(
	"login",
	'
			$(".window-login input").on("keyup", function() {
				$(".window-login .error").hide();
			});
		'
);
?>