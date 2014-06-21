<?php
use itnew\models\ImagesContent;

/**
 * @var ImagesContent $model
 */
?>

<div class="image-float image-window-item image-window-item-<?php echo $model->id; ?>"
	 data-id="<?php echo $model->id; ?>">
	<img src="<?php echo $model->getUrl("window"); ?>"/>
	<?php
	echo CHtml::ajaxLink(
		"<i class=\"close\"></i>",
		$this->createUrl(
			"ajax/index",
			array(
				"controller" => "images",
				"action"     => "deleteImage",
				"language"   => Yii::app()->language,
				"id"         => $model->id,
			)
		),
		array(
			"beforeSend" => 'function() {
					$(".window .image-window-item-' . $model->id . '").remove();
				}',
			"success"    => 'function(data) {
				}'
		),
		array(
			"class" => "close-container none-decoration",
			"id"    => uniqid(),
			"live"  => false,
		)
	);
	?>
</div>