<?php
use itnew\models\Records;

/**
 * @var Records $model
 */
?>

<div class="content-records content-records-<?php echo $model->id; ?>">
	<h1><?php echo $model->block->name; ?></h1>
	<?php
	if ($model->recordsContent) {
		foreach ($model->recordsContent as $recordsContent) {
			$this->renderPartial(
				"/records/_card",
				array(
					"model"   => $recordsContent,
					"records" => $model,
				)
			);
		}
	}
	?>
</div>