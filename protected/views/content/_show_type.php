<?php
use itnew\models\Structure;
use itnew\models\Block;

/**
 * @var Block[] $blocks
 */
?>

<div class="content-show-type">
	<?php echo Yii::t("content", "Show"); ?>:
	<?php if (Structure::isContentShowPage()) { ?>
		<a
			href="#"
			class="dotted ajax"
			data-function="updatePanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="panel?contentShow=all"
			><?php echo Yii::t("content", "all"); ?></a>
		| <strong><?php echo Yii::t("content", "from page"); ?></strong>
	<?php } else { ?>
		<strong><?php echo Yii::t("content", "all"); ?></strong> |
		<?php
		echo CHtml::ajaxLink(
			Yii::t("content", "from page"),
			$this->createUrl(
				"ajax/index",
				array(
					"controller"  => Yii::app()->controller->id,
					"action"      => "panel",
					"language"    => Yii::app()->language,
					"contentShow" => "page",
				)
			),
			array(
				"beforeSend" => 'function(){
					}',
				"success"    => 'function(data) {
						$("#subpanel").remove();
						$("#panel").remove();
						$("body").append(data);
					}',
			),
			array(
				"class" => "dotted",
				"id"    => uniqid(),
				"live"  => false,
			)
		);
		?>
	<?php } ?>
</div>