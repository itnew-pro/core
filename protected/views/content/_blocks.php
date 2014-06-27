<?php
use itnew\models\Block;
use itnew\components\Html;

/**
 * @var Block[] $blocks
 */
?>

<?php foreach ($blocks as $block) { ?>
	<div class="content-menu-block-item content-menu-block-<?php echo $block->id; ?>">
		<a
			href="#"
			class="link ajax"
			data-function="showWindow"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="window?id=<?php echo $block->content_id; ?>&name=<?php echo $block->name ?>"
		><i class="c c-<?php echo Yii::app()->controller->id; ?>"></i><?php echo $block->name; ?></a>

		<a
			href="#"
			class="settings ajax"
			data-function="updateSubpanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="settings?id=<?php echo $block->content_id; ?>"
			></a>
	</div>
<?php } ?>