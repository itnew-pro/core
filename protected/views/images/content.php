<?php
use itnew\models\Images;
use itnew\controllers\ImagesController;

/**
 * @var Images           $model
 * @var ImagesController $this
 */
?>

<div class="content-images content-images-<?php echo $model->id; ?>">
	<?php $this->renderPartial("/images/" . $model->getTemplateName(), compact("model")); ?>
</div>