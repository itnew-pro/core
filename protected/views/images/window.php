<?php echo CHtml::form(); ?>

	<div id="sortable">

		<?php if ($model->imagesContent) {
			foreach ($model->imagesContent as $imagesContent) {
				$this->renderPartial("_window_item", array("model" => $imagesContent));
			}
		} ?>

		<div class="add-images image-float">
			<?php 
				$id = uniqid();
				echo CHtml::fileField("imageFiles", null, array(
					"multiple" => true,
					"id" => $id,
					"class" => "image-file-field",
				));
			?>
			<i class="c c-image"></i>
			<?php echo Html::loader("add-images"); ?>
		</div>

	</div>

	<div class="clear"></div>

	<?php echo CHtml::activeHiddenField($model, "imageContentIds"); ?>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("imageFiles", '
		$("#sortable").sortable({
			items: "> .image-window-item",
			stop: function() {
				var sortString = "";
				$(this).find(".image-window-item").each(function(){
					sortString += $(this).data("id") + ",";
				});
				$("#Images_imageContentIds").val(sortString);
			}
		});

		$(".window #'. $id .'").on("change", function() {
			$(".window #'. $id .'").hide();
			$(".window #'. $id .'").parent().find(".loader").show();
			$(".window #'. $id .'").parent().find("i.c").hide();
			uploadImages(this.files, 0);
		});

		function uploadImages(files, i)
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
							"action" => "upload",
							"language" => Yii::app()->language,
							"id" => $model->id,
						)
					) . '",
					data: formData,
					success: function(data) {
						if (data) {
							$(".window .add-images").before(data);
						}
						i++;
						uploadImages(files, i);
					}
				});
			} else {
				$(".window #'. $id .'").show();
				$(".window #'. $id .'").parent().find(".loader").hide();
				$(".window #'. $id .'").parent().find("i.c").show();
			}
		}
	');
?>