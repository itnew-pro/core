<?php

use models\GridModel;

/**
 * @var models\SectionModel $model
 * @var models\GridModel[]  $grids
 */
?>

<div id="wrapper" class="section-<?php echo $model->id; ?>">
	<?php foreach (GridModel::getLines($grids) as $line => $gridModels) { ?>
		<div class="line-<?php echo $line; ?>">
			<div class="container" style="width: <?php echo $model->width; ?>">
				<div class="row">
					<?php foreach (GridModel::getLineTree($grids) as $gridContainer) { ?>
						<div class="col-<?php echo $gridContainer["col"]; ?> col-offset-<?php echo $gridContainer["offset"]; ?>">
							<div class="container">
								<div class="row row-offset-<?php echo(GridModel::GRID_SIZE - $gridContainer["col"]); ?>">
									<?php $top = 0; foreach ($gridContainer["grids"] as $grid) { ?>
										<?php if ($top < $grid["top"]) { $top = $grid["top"]; ?>
											<div class="clear"></div>
										<?php } ?>
										<div class="col-<?php echo $grid["col"]; ?> col-offset-<?php echo $grid["offset"]; ?>">
											<?php
											/**
											 * @var models\BlockModel $block
											 */
											$block = $grid["block"];
											$type = $block->getType();
											$modelContent = $block->getContentModel();
											if ($type && $modelContent) {
												$this->renderPartial("/{$type}/content", array("model" => $modelContent));
											}
											?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>