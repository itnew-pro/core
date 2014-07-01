<?php
use itnew\models\ImagesContent;

/**
 * @var ImagesContent $model
 */
?>

<div class="image-float image-window-item image-window-item-<?php echo $model->id; ?>"
	 data-id="<?php echo $model->id; ?>">
	<img src="<?php echo $model->getUrl("window"); ?>"/>

	<a
		href="#"
		class="close-container none-decoration ajax"
		data-function="empty"
		data-controller="images"
		data-action="deleteImage?id=<?php echo $model->id; ?>"
		data-id="<?php echo $model->id; ?>"
		><i class="close"></i></a>
</div>