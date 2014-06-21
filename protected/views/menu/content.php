<?php
use itnew\models\Menu;

/**
 * @var Menu $model
 */
?>

<div class="menu-content menu-content-<?php echo $model->id; ?> menu-type-<?php echo $model->getType(); ?>">
	<?php echo $model->getHtml(); ?>
</div>