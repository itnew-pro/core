<?php
use itnew\models\Images;
use itnew\components\Html;
use itnew\controllers\ImagesController;

/**
 * @var Images $model
 * @var ImagesController $this
 */
?>

	<div <?php if (empty($notMultiple)) { ?>class="sortable"<?php } ?>>

		<?php if ($model->imagesContent) {
			foreach ($model->imagesContent as $imagesContent) {
				$this->renderPartial("../images/_window_item", array("model" => $imagesContent));
			}
		} ?>
		<div class="add-images image-float">
			<?php $id = uniqid();
			echo CHtml::fileField(
				"imageFiles",
				null,
				array(
					"multiple" => empty($notMultiple) ? true : false,
					"id"       => $id,
					"class"    => "image-file-field",
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
		"name" => !empty($name) ? "{$name}[imageContentIds]" : get_class($model) . "[imageContentIds]",
		"id"   => !empty($name) ? "{$name}_imageContentIds" : get_class($model) . "_imageContentIds",
	)
); ?>

<?php
Yii::app()->clientScript->registerScript(
	"image-file-field-{$model->id}",
	'
			$(".sortable").sortable({
				items: "> .image-window-item",
				stop: function() {
					var sortString = "";
					$(this).find(".image-window-item").each(function(){
						sortString += $(this).data("id") + ",";
					});
					$("#' . (!empty($name) ? $name : get_class($model)) . '_imageContentIds").val(sortString);
			}
		});

		$(".window #' . $id . '").on("change", function() {
			var $object = $(this);
			$object.hide();
			$object.parent().find(".loader").show();
			$object.parent().find("i.c").hide();
			uploadImages' . $model->id . '(this.files, 0, $object);
		});

		function uploadImages' . $model->id . '(files, i, $object)
		{
			if (i < files.length) {
				var formData = new FormData();
				formData.append("ImagesContent[file]", files[i]);

				$.ajax({
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					url: "' .
	$this->createUrl(
		"ajax/index",
		array(
			"controller" => "images",
			"action"     => "upload",
			"language"   => Yii::app()->language,
			"id"         => $model->id,
		)
	) . '",
					data: formData,
					success: function(data) {
						if (data) {
							$object.parent().before(data);
						}
						i++;
						uploadImages' . $model->id . '(files, i, $object);
					}
				});
			} else {
				$object.show();
				$object.parent().find(".loader").hide();
				$object.parent().find("i.c").show();
			}
		}
	'
);
?>