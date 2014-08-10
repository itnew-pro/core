<?php
use itnew\models\Images;

/**
 * @var Images $model
 */
?>

<?php foreach ($model->imagesContent as $image) { ?>
	<?php if ($image->link) { ?><a href="<?php echo $image->link; ?>"><?php } ?>
	<img src="<?php echo $image->getViewUrl(); ?>" alt="<?php echo $image->alt; ?>">
	<?php if ($image->link) { ?></a><?php } ?>
<?php } ?>