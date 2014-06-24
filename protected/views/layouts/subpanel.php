<?php
use itnew\components\Html;

/**
 * @var string $content
 */
?>

<div id="subpanel">
	<i class="close"></i>

	<div class="title"><?php echo $this->subpanelTitle; ?></div>
	<div class="scroll-container">
		<?php echo $content; ?>
	</div>
</div>

<?php
Yii::app()->clientScript->registerScript(
	"panel",
	'
			$("#subpanel").on("click", ".close", function() {
				$("#subpanel").remove();
			});

			setSubpanelScrollContainerMaxHeight();
			$(window).resize(function(){
				setSubpanelScrollContainerMaxHeight();
			});

			function setSubpanelScrollContainerMaxHeight()
			{
				var subpanelListHeight =
					$(window).outerHeight()
					- 130
					- $("#subpanel .title").outerHeight()
				$("#subpanel .scroll-container").css("max-height", subpanelListHeight);
			}
		'
);
?>