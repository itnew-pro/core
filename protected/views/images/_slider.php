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

<?php
Yii::app()->clientScript->registerScript(
	"chopslider",
	'
			$(".chopslider .slider").chopSlider({
				slide : ".slide",
				nextTrigger : ".chopslider a.slide-next",
				prevTrigger : ".chopslider a.slide-prev",
				hideTriggers : true,
				sliderPagination : ".chopslider .slider-pagination",
				autoplay : true,
				autoplayDelay : 5000,
				t2D : csTransitions["half"]["3"],
				noCSS3 : csTransitions["noCSS3"]["random"],
				mobile : csTransitions["mobile"]["random"],
				onStart: function(){},
				onEnd: function(){}
		    });
		'
);
?>