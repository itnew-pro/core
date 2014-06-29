<?php
use itnew\models\Section;
use itnew\components\Html;

/**
 * @var Section $sections
 */
?>

	<div class="section-list">
		<?php if ($sections) {
			foreach ($sections as $section) {
				?>
				<div class="section-item section-<?php echo $section->id; ?>">
					<?php echo $section->seo->name; ?>
					<button
						class="settings ajax"
						data-function="showSectionSettings"
						data-controller="section"
						data-action="settings?id=<?php echo $section->id; ?>"
						data-modelId="<?php echo $section->id; ?>"
						></button>
					<?php if ($section->main) { ?>
						<i class="home"></i>
					<?php } ?>
				</div>
			<?php
			}
		} ?>
	</div>

<?php $this->renderPartial("/partials/_add_panel"); ?>