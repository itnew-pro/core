<?php
/**
 * @var string $content
 */
?>

<div
	class="window window-<?php echo $this->windowType; ?> window-level-<?php echo $this->windowLevel; ?>"
	data-type="<?php echo $this->windowType; ?>"
	>
	<div class="close-window"></div>
	<div class="title"><?php echo $this->windowTitle; ?></div>
	<div class="scroll-container">
		<div class="content">
			<?php echo $content; ?>
		</div>
	</div>
	<div class="footer">
		<div class="container">
			<?php if (empty($footer)) {
				$footer = null;
			}
			echo $footer; ?>
		</div>
	</div>
</div>

<div
	class="overlay overlay-<?php echo $this->windowType; ?> overlay-level-<?php echo $this->windowLevel; ?>"
	data-type="<?php echo $this->windowType; ?>"
	></div>