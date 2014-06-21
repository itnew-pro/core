<?php
use itnew\models\Staff;

/**
 * @var Staff $model
 */
?>

<?php echo CHtml::form(); ?>

	<div class="sortable">
		<?php if ($model->staffGroup) {
			foreach ($model->staffGroup as $group) { ?>
				<div class="move-item" data-id="<?php echo $group->id; ?>" id="staff-group-<?php echo $group->id; ?>">
					<?php echo $group->name; ?>

					<?php
					echo CHtml::ajaxLink(
						"<i class=\"settings\"></i>",
						$this->createUrl(
							"ajax/index",
							array(
								"controller" => "staff",
								"action"     => "windowGroup",
								"language"   => Yii::app()->language,
								"name"       => $model->block->name,
								"id"         => $group->id,
							)
						),
						array(
							"beforeSend" => 'function(){
								
							}',
							"success"    => 'function(data) {
								$("body").append(data);
								showWindow("staff-group");
							}',
						),
						array(
							"class" => "none-decoration edit",
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
								"controller" => "staff",
								"action"     => "deleteGroup",
								"language"   => Yii::app()->language,
								"id"         => $group->id,
							)
						),
						array(
							"beforeSend" => 'function(){

							}',
							"success"    => 'function(data) {
								$(".window #staff-group-' . $group->id . '").remove();
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
			<?php }
		} ?>
	</div>

	<div class="window-bottom">
		<?php if ($model->is_group) { ?>

			<?php
			echo CHtml::ajaxLink(
				Yii::t("staff", "Add group"),
				$this->createUrl(
					"ajax/index",
					array(
						"controller" => "staff",
						"action"     => "windowGroup",
						"language"   => Yii::app()->language,
						"name"       => $model->block->name,
						"staff_id"   => $model->id,
					)
				),
				array(
					"beforeSend" => 'function(){
							
						}',
					"success"    => 'function(data) {
							$("body").append(data);
							showWindow("staff-group");
						}',
				),
				array(
					"class" => "link dotted",
					"id"    => uniqid(),
					"live"  => false,
				)
			);
			?>

		<?php } else { ?>
			<a href="#">Добавить сотрудника</a>
		<?php } ?>
	</div>

<?php echo CHtml::activeHiddenField($model, "groupIds"); ?>

<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

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
					$("#Staff_groupIds").val(sortString);
				}
			});
		'
);
?>