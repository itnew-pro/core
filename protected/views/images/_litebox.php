<?php foreach ($images as $model) { ?>
	<a
		class="none-decoration fancybox"
		rel="<?php echo $model->images_id; ?>"
		href="<?php echo $model->getFullUrl(); ?>"
		title="<?php echo $model->alt; ?>"
		><img src="<?php echo $model->getThumbUrl(); ?>" alt="<?php echo $model->alt; ?>"></a>
<?php } ?>

<?php
Yii::app()->clientScript->registerScript(
	"fancybox",
	'
			$(".fancybox").fancybox({
				openEffect	: "elastic",
				closeEffect	: "elastic",

				helpers	: {
					thumbs	: {
						width	: 50,
						height	: 50
					},
					buttons	: {}
				}
			});
		'
);
?>