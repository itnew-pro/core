<?php
use itnew\models\RecordsContent;
use itnew\models\Records;

/**
 * @var Records $model
 */
?>

<?php
$recordsContent = RecordsContent::model()->getModelBySeoUrlAndRecordsId(
	Yii::app()->request->getQuery("level1"),
	$model->id
);
if ($recordsContent) {
	$this->renderPartial("/records/_item", array("model" => $recordsContent));
} else {
	$this->renderPartial("/records/_list", compact("model"));
}
?>