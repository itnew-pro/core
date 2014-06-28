<?php
use itnew\models\ImagesContent;

/**
 * @var ImagesContent[] $images
 */
?>

<?php foreach ($images as $model) { ?>
	<a
		class="none-decoration fancybox"
		rel="<?php echo $model->images_id; ?>"
		href="<?php echo $model->getFullUrl(); ?>"
		title="<?php echo $model->alt; ?>"
		><img src="<?php echo $model->getThumbUrl(); ?>" alt="<?php echo $model->alt; ?>"></a>
<?php } ?>