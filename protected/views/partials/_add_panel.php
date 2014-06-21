<?php
/**
 * @var CController $this
 */
?>

<div class="add-panel">
	<i class="plus"></i>
	<?php
	echo CHtml::ajaxLink(
		Yii::t("common", "Add"),
		$this->createUrl(
			"ajax/index",
			array(
				"controller" => Yii::app()->controller->id,
				"action"     => "settings",
				"language"   => Yii::app()->language,
			)
		),
		array(
			"beforeSend" => 'function(){

				}',
			"success"    => 'function(data) {
					$("#subpanel").remove();
					$("body").append(data);
					$("#panel .scroll-container *").removeClass("active");
				}',
		),
		array(
			"class" => "add dotted",
			"id"    => uniqid(),
			"live"  => false,
		)
	);
	?>
	<i class="arrow-blue"></i>
</div>