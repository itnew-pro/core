<?php
/**
 * @var Structure $model
 */
?>

<div id="wrapper" class="structure-<?php echo $model->id; ?>">
	<?php foreach ($model->getLines() as $lineNumber => $grids) { ?>
		<div class="line-<?php echo $lineNumber; ?>">
			<div
				class="line-container"
				style="width: <?php echo $model->getWidth(); ?>; margin: <?php echo $model->getMargin(); ?>;"
				>
				<?php foreach ($model->getLineTree($grids) as $gridContainer) { ?>
					<div class="grid-container" style="
						width: <?php echo $gridContainer["width"]; ?>%;
						margin-left: <?php echo $gridContainer["left"]; ?>%;
						">
						<?php foreach ($gridContainer["blocks"] as $block) { ?>
							<div class="block-container" style="
								width: <?php echo $block["width"] ?>%;
								margin-right: <?php echo $block["width"] ?>%;
								margin-left: <?php echo $block["left"] ?>%;
								">
								<?php
								$type = $block["model"]->getType();
								$model = $block["model"]->getContentModel();
								if ($type && $model) {
									$this->renderPartial("/{$type}/content", array("model" => $model));
								}
								?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</div>
	<?php } ?>
</div>