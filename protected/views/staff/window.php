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
					<a
						href="#"
						class="none-decoration edit ajax"
						data-function="showStaffGroupWindow"
						data-controller="staff"
						data-action="windowGroup?id=<?php echo $group->id; ?>&name=<?php echo $model->block->name; ?>"
						><i class="settings"></i></a>
					<a
						href="#"
						class="none-decoration delete ajax"
						data-function="deleteStaffGroup"
						data-controller="staff"
						data-action="deleteGroup?id=<?php echo $group->id; ?>"
						data-moduleId="<?php echo $group->id; ?>"
						data-confirm="true"
						><i class="close"></i></a>
				</div>
			<?php }
		} ?>
	</div>

	<div class="window-bottom">
		<?php if ($model->is_group) { ?>
			<a
				href="#"
				class="link dotted ajax"
				data-function="showStaffGroupWindow"
				data-controller="staff"
				data-action="windowGroup?name=<?php echo $model->block->name; ?>&staff_id=<?php echo $model->id; ?>"
				><?php echo Yii::t("staff", "Add group"); ?></a>
		<?php } else { ?>
			<a href="#">Добавить сотрудника</a>
		<?php } ?>
	</div>

<?php echo CHtml::activeHiddenField($model, "groupIds"); ?>

	<button
		class="button ajax"
		data-function="saveWindow"
		data-controller="staff"
		data-action="saveWindow?id=<?php echo $model->id; ?>"
		data-post=true
		data-modelId="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>