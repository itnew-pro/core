<?php
use itnew\models\Images;
use itnew\controllers\ImagesController;

/**
 * @var Images           $model
 * @var ImagesController $this
 */
?>

<div <?php if (empty($notMultiple)) { ?>class="sortable"<?php } ?>>

	<?php if ($model->imagesContent) {
		foreach ($model->imagesContent as $imagesContent) {
			$this->renderPartial("/images/_window_item", array("model" => $imagesContent));
		}
	} ?>
	<div class="add-images image-float">
		<?php
		echo CHtml::fileField(
			"imageFiles",
			null,
			array(
				"multiple" => empty($notMultiple) ? true : false,
				"class"    => "image-file-field",
				"data-id" => $model->id,
			)
		);
		?>
		<i class="c c-image"></i>
	</div>

</div>

<div class="clear"></div>

<?php echo CHtml::activeHiddenField(
	$model,
	"imageContentIds",
	array(
		"name"  => !empty($name) ? "{$name}[imageContentIds]" : CHtml::modelName($model) . "[imageContentIds]",
		"class" => "imageContentIds"
	)
); ?>