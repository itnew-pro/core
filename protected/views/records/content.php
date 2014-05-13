<?php
$recordsContent = RecordsContent::model()->getModelBySeoUrlAndRecordsId(
	Yii::app()->request->getQuery("level1"),
	$model->id
);
if ($recordsContent) {
	$this->renderPartial("_item", array("model" => $recordsContent));
} else {
	$this->renderPartial("_list", compact("model"));
}

?>