<?php
/**
 * @var string[] $blocks
 */
?>

<?php
$this->renderPartial("_show_type");
?>

<?php foreach ($blocks as $key => $value) { ?>
	<div class="content-menu-block-item content-menu-block-<?php echo $value; ?>">
		<a
			href="#"
			class="link ajax"
			data-function="updatePanel"
			data-controller="<?php echo $value; ?>"
			data-action="panel"
			><i class="c c-<?php echo $value; ?>"></i><?php echo $key; ?></a>
	</div>
<?php } ?>