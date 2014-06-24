<?php
/**
 * @var string $content
 */
?>

<div id="panel">
	<div class="container container-<?php echo $this->panelType; ?>">
		<i class="close"></i>
		<div class="title"><?php echo $this->panelTitle; ?></div>
		<div class="description"><?php echo $this->panelDescription; ?></div>
		<div class="scroll-container">
			<?php echo $content; ?>
		</div>
	</div>
</div>