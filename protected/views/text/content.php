<?php
use itnew\models\Text;

/**
 * @var Text $model
 */
?>

<<?php
	echo $model->getTag();
?> class="content-text content-text-<?php echo $model->id; ?>"><?php echo $model->text; ?></<?php
	echo $model->getTag();
?>>