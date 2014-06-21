<?php
use itnew\models\Structure;

/**
 * @var Structure $model
 */
?>

<?php if ($model) { ?>
	<div id="wrapper" class="structure-<?php echo $model->id; ?>">
		<?php foreach ($model->getLines() as $lineNumber => $grids) { ?>
			<div class="line-<?php echo $lineNumber; ?>">
				<div class="container" style="width: <?php echo $model->getWidth(); ?>">
					<div class="row">
						<?php foreach ($model->getLineTree($grids) as $gridContainer) { ?>
							<div class="col-<?php echo $gridContainer["col"]; ?> col-offset-<?php echo $gridContainer["offset"]; ?>">
								<div class="container">
									<div class="row row-offset-<?php echo (12 - $gridContainer["col"]); ?>">
										<?php $top = 0; foreach ($gridContainer["grids"] as $grid) { ?>
											<?php if ($top < $grid["top"]) { $top = $grid["top"]; ?>
												<div class="clear"></div>
											<?php } ?>
											<div class="col-<?php echo $grid["col"]; ?> col-offset-<?php echo $grid["offset"]; ?>">
												<?php
												$type = $grid["block"]->getType();
												$modelContent = $grid["block"]->getContentModel();
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
<?php } ?>