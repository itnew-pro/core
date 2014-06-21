<?php
/**
 * @var string $content
 */
?>

	<div id="panel">
		<div class="container container-<?php echo $this->panelType; ?>">
			<i class="close"></i>

			<div class="title"><?php echo $this->panelTitle; ?></div>
			<div class="description"><?php echo $this->panelDescription; ?></div>
			<div class="scroll-container">
				<?php echo $content; ?>
			</div>
		</div>
	</div>

<?php
Yii::app()->clientScript->registerScript(
	"panel",
	'
			$("#panel").on("click", ".close", function() {
				$("#panel").remove();
				$("#subpanel").remove();
				$(".panel-tab").removeClass("active");
				$("#panel-tabs").removeClass("active");
			});

			setPanelScrollContainerMaxHeight();
			$(window).resize(function(){
				setPanelScrollContainerMaxHeight();
			});

			var panelPaddingBottom = parseInt($("#panel .container").css("padding-bottom"));

			function setPanelScrollContainerMaxHeight()
			{
				var panelListHeight =
					$(window).outerHeight()
					- 40
					- $("#panel .title").outerHeight()
					- $("#panel .description").outerHeight()
					- panelPaddingBottom;
				$("#panel .scroll-container").css("max-height", panelListHeight);
			}
		'
);
?>