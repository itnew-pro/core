<?php echo CHtml::form(); ?>

	<div class="sortable">
		<?php if ($model->staffGroup) { foreach ($model->staffGroup as $group) { ?>
			<div class="move-item">
				<?php echo $group->name; ?>
			</div>
		<?php } } ?>
	</div>

	<div class="window-bottom">
		<a href="#">Добавить категорию</a>
		| <a href="#">Добавить сотрудника</a>
	</div>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>

<?php
	Yii::app()->clientScript->registerScript("staffWindow", '
		$(".sortable").sortable();
	');
?>