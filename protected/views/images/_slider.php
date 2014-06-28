<?php
use itnew\models\ImagesContent;

/**
 * @var ImagesContent[] $images
 * @var bool $isFirst
 */
?>

<div class="chopslider">
	<a class="slide-next" href="#"></a>
	<a class="slide-prev" href="#"></a>
	<div class="slider" style="width: 940px; height: 444px;">
		<?php $isFirst = true; foreach ($images as $model) { ?>
			<div class="slide<?php if ($isFirst) { ?> cs-activeSlide<?php $isFirst = false;  } ?>">
				<img src="<?php echo $model->getFullUrl(); ?>" />
			</div>
		<?php } ?>
	</div>
	<div class="pagination">
		<?php for ($i = 0; $i < count($images); $i++) { ?>
			<span class="slider-pagination"></span>
		<?php } ?>
	</div>
</div>