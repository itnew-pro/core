<?php
use itnew\models\Block;
use itnew\components\Html;

/**
 * @var Block[] $blocks
 * @var string  $value
 */
?>

<?php
$this->renderPartial("_show_type");
?>

<?php foreach ($blocks as $key => $value) { ?>
	<div class="content-menu-block-item content-menu-block-<?php echo $value; ?>">
		<?php
		echo CHtml::ajaxLink(
			"<i class='c c-{$value}'></i>{$key}",
			$this->createUrl(
				"ajax/index",
				array(
					"controller" => $value,
					"action"     => "panel",
					"language"   => Yii::app()->language
				)
			),
			array(
				"beforeSend" => 'function(){
						$(".content-menu-block-' . $value . ' i.c").hide();
						$(".content-menu-block-' . $value . ' .loader").show();
					}',
				"success"    => 'function(data) {
						$("#panel").remove();
						$("body").append(data);
					}',
			),
			array(
				"class" => "link",
				"id"    => uniqid(),
				"live"  => false,
			)
		);

		?>
	</div>
<?php } ?>