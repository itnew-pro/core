<?php
use itnew\models\RecordsContent;

/**
 * @var RecordsContent $model
 */
?>

<div class="content-records-content content-records-content-<?php echo $model->id; ?>">
	<h1><?php echo $model->seo->name; ?></h1>

	<div class="images">
		<?php $this->renderPartial(
			"../images/" . $model->imagesRelation->getTemplateName(),
			array("images" => $model->imagesRelation->imagesContent)
		); ?>
	</div>
	<div class="text">
		<?php echo $model->textRelation->text; ?>
	</div>
</div>