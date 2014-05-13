<div class="section-list">
	<?php if ($sections) {
		foreach ($sections as $section) { ?>
			<div class="section-item section-<?php echo $section->id; ?>">
				<?php
				echo $section->seo->name;

				echo CHtml::ajaxButton(
					null,
					$this->createUrl(
						"ajax/index",
						array(
							"controller" => "section",
							"action"     => "settings",
							"language"   => Yii::app()->language,
							"id"         => $section->id,
						)
					),
					array(
						"beforeSend" => 'function(){
							$("#panel .section-' . $section->id . '")
								.find(".loader-section-settings").show();
						}',
						"success"    => 'function(data) {
							$("#panel .section-' . $section->id . '")
								.find(".loader-section-settings").hide();
							$("#subpanel").remove();
							$("body").append(data);
							$("#panel .section-item").removeClass("active");
							$("#panel .section-' . $section->id . '").addClass("active");
							$("#subpanel").on("click", ".close", function() {
								$("#panel .section-item").removeClass("active");
							});
						}',
					),
					array(
						"class" => "settings",
						"id"    => uniqid(),
						"live"  => false,
					)
				);

				echo Html::loader("section-settings");

				if ($section->main) {
					?>

					<i class="home"></i>

				<?php } ?>
			</div>
		<?php }
	} ?>
</div>

<?php
$this->renderPartial("../partials/_add_panel");
?>