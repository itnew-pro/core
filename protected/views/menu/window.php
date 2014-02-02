<?php echo CHtml::form(); ?>

	<?php $sectionsId = uniqid(); ?>
	<div class="sections" id="<?php echo $sectionsId; ?>">
		<?php if ($model->getUnusedSections()) { foreach ($model->getUnusedSections() as $id => $name) { ?>
			<div 
				data-id="<?php echo $id; ?>"
				data-type="section"
				data-level="0"
				class="section content-move-item"
			>
				<i class="level-up"></i>
				<i class="level-down"></i>
				<?php echo $name; ?>
			</div>
		<?php } } ?>
	</div>

	<?php $sortableId = uniqid(); ?>
	<div class="sortable" id="<?php echo $sortableId; ?>">
		
	</div>

	<div class="clear"></div>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("menuWindow", '
		function setMoveActions()
		{
			$(".content-move-item .level-down").unbind("click");
			$(".content-move-item .level-down").on("click", function(){
				var level = parseInt($(this).parent().data("level"));
				marginLeft = (level + 1) * 25;
				$(this).parent().css("margin-left", marginLeft);
				$(this).parent().data("level", level + 1);
				$(this).parent().find(".level-up").show();
			});

			$(".content-move-item .level-up").unbind("click");
			$(".content-move-item .level-up").on("click", function(){
				var level = parseInt($(this).parent().data("level"));
				marginLeft = (level - 1) * 25;
				$(this).parent().css("margin-left", marginLeft);
				$(this).parent().data("level", level - 1);
				if (level < 2) {
					$(this).hide();
				}
			});
		}

		$(".window-menu #' . $sectionsId . ' .section").on("click", function(){
			$(this).find(".level-up").hide();
			$(this).find(".level-down").show();
			var menuItem = $(this).get(0).outerHTML;
			$(this).remove();
			$(".window-menu #' . $sortableId . '").append(menuItem);
			setMoveActions();
		});

		$(".sortable").sortable({
			stop: function() {

			}
		});

		setMoveActions();
	');
?>