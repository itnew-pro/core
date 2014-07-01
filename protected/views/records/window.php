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
					<a
						href="#"
						class="dotted ajax"
						data-function="showRecordsFormWindow"
						data-controller="records"
						data-action="windowForm?id=<?php echo $record->id; ?>"
						><?php echo $record->seo->name; ?></a>

					<a
						href="#"
						class="none-decoration delete ajax"
						data-function="empty"
						data-controller="records"
						data-action="deleteRecordsContent?id=<?php echo $record->id; ?>"
						data-confirm=true
						><i class="close"></i></a>
				</div>
			<?php
			}
		} ?>
	</div>

	<div class="window-bottom">
		<a
			href="#"
			class="link dotted ajax"
			data-function="showRecordsAddWindow"
			data-controller="records"
			data-action="windowAdd?id=<?php echo $model->id; ?>"
			><?php echo Yii::t("common", "Add"); ?></a>
	</div>

<?php echo CHtml::activeHiddenField($model, "contentIds"); ?>

	<button
		class="button ajax"
		data-function="saveWindow"
		data-controller="records"
		data-action="saveWindow?id=<?php echo $model->id; ?>"
		data-post=true
		data-id="<?php echo $model->id; ?>"
		><?php echo Yii::t("common", "Update"); ?></button>

<?php echo CHtml::endForm(); ?>