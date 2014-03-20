<?php foreach ($images as $model) { ?>
	<img src="<?php echo $model->getViewUrl(); ?>" alt="<?php echo $model->alt; ?>">
<?php } ?>