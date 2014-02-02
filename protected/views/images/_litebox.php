<?php foreach ($images as $model) { ?>
	<a class="none-decoration" href="<?php echo $model->getFullUrl(); ?>">
		<img src="<?php echo $model->getThumbUrl(); ?>">
	</a>
<?php } ?>

<?php
	Yii::app()->clientScript->registerScript("fancybox", '
		
	');
?>