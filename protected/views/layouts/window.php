<?php $id = uniqid(); ?>
<div class="window window-<?php echo $this->windowType; ?> window-<?php echo $id; ?> window-level-<?php echo $this->windowLevel; ?>">
	<div class="close-window" onclick="hideWindow('<?php echo $this->windowType; ?>')"></div>
	<div class="title"><?php echo $this->windowTitle; ?></div>
	<div class="scroll-container">
		<div class="content">
			<?php echo $content; ?>
			<?php echo Html::loader("window-button"); ?>
		</div>
	</div>
	<div class="footer">
		<div class="container">
			<?php if (empty($footer)) {$footer = null;} echo $footer; ?>
		</div>
	</div>
</div>

<div class="overlay overlay-<?php echo $this->windowType; ?> overlay-level-<?php
	echo $this->windowLevel; ?>" onclick="hideWindow('<?php echo $this->windowType; ?>')"></div>

<?php
	Yii::app()->clientScript->registerScript("window", '
		var obj = $(".window-' . $id . '");
		obj.css("margin-top", "-" + (obj.height() / 2) + "px");
	');
?>