<?php
/**
 * @var ImagesContent[] $images
 */

foreach ($images as $model) { ?>
	<?php if ($model->link) { ?><a href="<?php echo $model->link; ?>"><?php } ?>
	<img src="<?php echo $model->getViewUrl(); ?>" alt="<?php echo $model->alt; ?>">
	<?php if ($model->link) { ?></a><?php } ?>
<?php } ?>