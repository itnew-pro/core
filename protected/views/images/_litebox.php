<?php
use itnew\models\Images;

/**
 * @var Images $model
 */
?>

<?php foreach ($model->imagesContent as $image) { ?>
	<a
		class="none-decoration fancybox"
		rel="<?php echo $image->images_id; ?>"
		href="<?php echo $image->getFullUrl(); ?>"
		title="<?php echo $image->alt; ?>"
		><img src="<?php echo $image->getThumbUrl(); ?>" alt="<?php echo $image->alt; ?>"></a>
<?php } ?>