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

	<input class="button" type="submit" value="<?php echo Yii::t("admin", "Ok"); ?>" />

<?php echo CHtml::endForm(); ?>