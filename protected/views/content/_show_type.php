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
		<strong><?php echo Yii::t("content", "all"); ?></strong>
		| <a
			href="#"
			class="dotted ajax"
			data-function="updatePanel"
			data-controller="<?php echo Yii::app()->controller->id; ?>"
			data-action="panel?contentShow=page"
			><?php echo Yii::t("content", "from page"); ?></a>
	<?php } ?>
</div>