<div class="seo form-block">
	<div>
		<?php
			echo CHtml::activeLabel($model, "name");
			echo CHtml::activeTextField($model, "name", array("class" => "blue-form"));
		?>
		<div class="error error-name-empty">
			<?php echo Yii::t("seo", "Name can not be empty"); ?>
		</div>
	</div>

	<div>
		<?php
			echo CHtml::activeLabel($model, "url");
			echo CHtml::activeTextField($model, "url", array("class" => "blue-form"));
		?>
		<div class="error error-url-empty">
			<?php echo Yii::t("seo", "URL can not be empty"); ?>
		</div>
		<div class="error error-url-exist">
			<?php echo Yii::t("seo", "This URL is already in use"); ?>
		</div>
	</div>

	<div class="seo-title">
		<a href="#" class="dotted"><?php echo Yii::t("seo", "SEO optimization"); ?></a>
	</div>

	<div class="seo-optimization">
		<div>
			<?php
				echo CHtml::activeLabel($model, "title");
				echo CHtml::activeTextField($model, "title", array("class" => "blue-form"));
			?>
		</div>

		<div>
			<?php
				echo CHtml::activeLabel($model, "keywords");
				echo CHtml::activeTextField($model, "keywords", array("class" => "blue-form"));
			?>
		</div>

		<div>
			<?php
				echo CHtml::activeLabel($model, "description");
				echo CHtml::activeTextArea($model, "description",
					array("class" => "blue-form", "rows" => 3));
			?>
		</div>
	</div>
</div>

<?php
	Yii::app()->clientScript->registerScript("seo", '
		$("#Seo_name").on("keyup", function() {
			$(".error-name-empty").hide();
			$(".error-url-empty").hide();
			$(".error-url-exist").hide();
			$("#Seo_url").val($(this).val().translit());
		});

		$("#Seo_url").on("keyup", function() {
			$(".error-url-empty").hide();
			$(".error-url-exist").hide();
			$("#Seo_url").val($(this).val().translit());
		});

		$(".seo-title a").on("click", function(){
			$(".seo-optimization").slideToggle(200);
			return false;
		});

		if (
			$("#Seo_title").val()
			|| $("#Seo_keywords").val()
			|| $("#Seo_description").val()
		) {
			$(".seo-optimization").show();
		}
	');
?>