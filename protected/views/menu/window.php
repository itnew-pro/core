<?php
use itnew\models\Menu;
use itnew\controllers\MenuController;

/**
 * @var MenuController $this
 * @var Menu           $model
 */
?>

<?php echo CHtml::form(); ?>

<?php $sectionsId = uniqid(); ?>
	<div class="sections" id="<?php echo $sectionsId; ?>">
		<?php if ($model->getUnusedSections()) {
			foreach ($model->getUnusedSections() as $id => $name) {
				?>
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
			<?php
			}
		} ?>
	</div>

<?php $sortableId = uniqid(); ?>
	<div class="sortable" id="<?php echo $sortableId; ?>">

	</div>

	<div class="clear"></div>

<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>