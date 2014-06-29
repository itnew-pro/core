<?php
use itnew\models\Block;

/**
 * @var Block[] $blocks
 */
?>

<?php foreach ($blocks as $block) { ?>
	<div class="content-menu-block-item content-menu-block-<?php echo $block->id; ?>">
		<a
			href="#"
			class="link ajax"
			data-function="show<?php echo ucfirst(Yii::app()->controller->id); ?>Window"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="window?id=<?php echo $block->content_id; ?>&name=<?php echo $block->name ?>"
		><i class="c c-<?php echo Yii::app()->controller->id; ?>"></i><?php echo $block->name; ?></a>

		<a
			href="#"
			class="settings ajax"
			data-function="show<?php echo ucfirst(Yii::app()->controller->id); ?>Subpanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="settings?id=<?php echo $block->content_id; ?>"
			></a>
	</div>
<?php } ?>