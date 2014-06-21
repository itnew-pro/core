<?php
use itnew\models\RecordsContent;
use itnew\models\Records;

/**
 * @var RecordsContent $model
 * @var Records        $records
 */
?>

<div class="card default-card">
	<div class="cover">
		<a href="<?php echo $model->getUrl(); ?>">
			<?php $this->renderPartial(
				"../images/" . $model->coverRelation->getTemplateName(),
				array("images" => $model->coverRelation->imagesContent)
			); ?>
		</a>
	</div>

	<div
		class="card-container"
		<?php if ($records->cover) { ?>style="margin-left: <?php echo $records->getCoverWidth(); ?>px;"<?php } ?>
		>
		<div class="title"><a href="<?php echo $model->getUrl(); ?>"><?php echo $model->seo->name; ?></a></div>

		<div class="description"><?php echo $model->descriptionRelation->text; ?></div>
	</div>

	<div class="clear"></div>
</div>