<?php
use itnew\models\Records;

/**
 * @var Records $model
 */
?>

<?php echo CHtml::form(); ?>

	<div class="sortable">
		<?php if ($model->recordsContent) {
			foreach ($model->recordsContent as $record) {
				?>
				<div class="move-item" data-id="<?php echo $record->id; ?>" id="record-<?php echo $record->id; ?>">

					<?php
					echo CHtml::ajaxLink(
						$record->seo->name,
						$this->createUrl(
							"ajax/index",
							array(
								"controller" => "records",
								"action"     => "windowForm",
								"language"   => Yii::app()->language,
								"id"         => $record->id,
							)
						),
						array(
							"beforeSend" => 'function(){

							}',
							"success"    => 'function(data) {
								$("body").append(data);
								showWindow("records-form");
							}',
						),
						array(
							"class" => "dotted",
							"id"    => uniqid(),
							"live"  => false,
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
								"action"     => "deleteRecordsContent",
								"language"   => Yii::app()->language,
								"id"         => $record->id,
							)
						),
						array(
							"beforeSend" => 'function(){

							}',
							"success"    => 'function(data) {
								$(".window #record-' . $record->id . '").remove();
							}',
						),
						array(
							"class"   => "none-decoration delete",
							"id"      => uniqid(),
							"live"    => false,
							"onclick" => "if (!confirm('Восстановить будет невозможно! \\r\\n Вы действительно хотите удалить безвозвратно?')){return;}",
						)
					);
					?>
				</div>
			<?php
			}
		} ?>
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
				"success"    => 'function(data) {
						$("body").append(data);
						showWindow("records-add");
					}',
			),
			array(
				"class" => "link dotted",
				"id"    => uniqid(),
				"live"  => false,
			)
		);
		?>
	</div>

<?php echo CHtml::activeHiddenField($model, "contentIds"); ?>

	<button
		class="button ajax"
		data-function="saveWindow"
		data-controller="records"
		data-action="saveWindow?id=<?php echo $model->id; ?>"
		data-post=true
		data-modelId="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>

<?php
Yii::app()->clientScript->registerScript(
	"staffWindow",
	'
			$(".sortable").sortable({
				stop: function() {
					var sortString = "";
					$(this).find(".move-item").each(function(){
						sortString += $(this).data("id") + ",";
					});
					$("#Records_contentIds").val(sortString);
				}
			});
		'
);
?>