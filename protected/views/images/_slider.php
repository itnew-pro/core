<?php
use itnew\models\Images;

/**
 * @var Images $model
 * @var bool   $isFirst
 */
?>

<div class="chopslider">
	<a class="slide-next" href="#"></a>
	<a class="slide-prev" href="#"></a>
	<?php $images = $model->imagesContent; ?>
	<div class="slider" style="width: <?php echo $model->width; ?>px; height: <?php echo $model->height; ?>px;">
		<?php $isFirst = true; foreach ($images as $image) { ?>
			<div class="slide<?php if ($isFirst) { ?> cs-activeSlide<?php $isFirst = false;  } ?>">
				<img src="<?php echo $image->getViewUrl(); ?>" />
			</div>
		<?php } ?>
	</div>
	<div class="pagination">
		<?php for ($i = 0; $i < count($images); $i++) { ?>
			<span class="slider-pagination"></span>
		<?php } ?>
	</div>
</div>