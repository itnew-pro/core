<?php echo CHtml::form(); ?>

	<div class="sortable">
		<?php if ($model->recordsContent) { foreach ($model->recordsContent as $record) { ?>
			<div class="move-item" data-id="<?php echo $record->id; ?>" id="record-<?php echo $record->id; ?>">

				<?php
					echo CHtml::ajaxLink(
						$record->seo->name,
						$this->createUrl(
							"ajax/index",
							array(
								"controller" => "records",
								"action" => "windowForm",
								"language" => Yii::app()->language,
								"id" => $record->id,
							)
						), 
						array(
							"beforeSend" => 'function(){

							}',
							"success" => 'function(data) {
								$("body").append(data);
								showWindow("records-form");
							}',
						),
						array(
							"class" => "dotted",
							"id" => uniqid(),
							"live" => false,
						)
					);
				?>

				<?php
					echo CHtml::ajaxLink(
						"<i class=\"close\"></i>",
						$this->createUrl(
							"ajax/index",
							array(
								"controller" => "records",
								"action" => "deleteRecordsContent",
								"language" => Yii::app()->language,
								"id" => $record->id,
							)
						), 
						array(
							"beforeSend" => 'function(){

							}',
							"success" => 'function(data) {
								$(".window #record-' . $record->id . '").remove();
							}',
						),
						array(
							"class" => "none-decoration delete",
							"id" => uniqid(),
							"live" => false,
							"onclick" => "if (!confirm('Восстановить будет невозможно! \\r\\n Вы действительно хотите удалить безвозвратно?')){return;}",
						)
					);
				?>
			</div>
		<?php } } ?>
	</div>

	<div class="window-bottom">
		<?php
			echo CHtml::ajaxLink(
				Yii::t("common", "Add"),
				$this->createUrl(
					"ajax/index",
					array(
						"controller" => "records",
						"action"     => "windowAdd",
						"language"   => Yii::app()->language,
						"id"         => $model->id,
					)
				), 
				array(
					"beforeSend" => 'function(){
						
					}',
					"success" => 'function(data) {
						$("body").append(data);
						showWindow("records-add");
					}',
				),
				array(
					"class" => "link dotted",
					"id" => uniqid(),
					"live" => false,
				)
			);
		?>
	</div>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("staffWindow", '
		$(".sortable").sortable({
			stop: function() {
				//var sortString = "";
				//$(this).find(".move-item").each(function(){
				//	sortString += $(this).data("id") + ",";
				//});
				//$("#Staff_groupIds").val(sortString);
			}
		});
	');
?>