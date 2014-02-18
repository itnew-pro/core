<?php echo CHtml::form(); ?>

	<div class="sortable">
		<?php if ($model->staffGroup) { foreach ($model->staffGroup as $group) { ?>
			<div class="move-item">
				<?php echo $group->name; ?>

				<?php
					echo CHtml::ajaxLink(
						"<i class=\"settings\"></i>",
						$this->createUrl(
							"ajax/index",
							array(
								"controller" => "staff",
								"action" => "windowGroup",
								"language" => Yii::app()->language,
								"name" => $model->block->name,
								"id" => $group->id,
							)
						), 
						array(
							"beforeSend" => 'function(){
								
							}',
							"success" => 'function(data) {
								$("body").append(data);
								showWindow("staff-group");
							}',
						),
						array(
							"class" => "none-decoration edit",
							"id" => uniqid(),
							"live" => false,
						)
					);
				?>

				<a href="#" class="none-decoration delete"><i class="close"></i></a>
			</div>
		<?php } } ?>
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
							"action" => "windowGroup",
							"language" => Yii::app()->language,
							"name" => $model->block->name,
						)
					), 
					array(
						"beforeSend" => 'function(){
							
						}',
						"success" => 'function(data) {
							$("body").append(data);
							showWindow("staff-group");
						}',
					),
					array(
						"class" => "link dotted",
						"id" => uniqid(),
						"live" => false,
					)
				);
			?>

		<?php } else { ?>
			<a href="#">Добавить сотрудника</a>
		<?php } ?>
	</div>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("staffWindow", '
		$(".sortable").sortable();
	');
?>