<?php
use itnew\models\Staff;

/**
 * @var Staff $model
 */
?>

<?php if ($model->staffGroup) { ?>
	<div class="content-staff content-staff-<?php echo $model->id; ?>">

		<?php foreach ($model->staffGroup as $group) {
			if ($group->staffContent) { ?>
				<div class="group">
					<div class="group-title">
						<?php echo $group->name; ?>
					</div>

					<?php foreach ($group->staffContent as $staff) { ?>
						<div class="card default-card">
							<div class="photo">
								<?php if ($staff->images) { ?>
									<?php $this->renderPartial(
										"../images/content",
										array("model" => $staff->images)
									); ?>
								<?php } else { ?>

								<?php } ?>
							</div>

							<div class="content content-with-photo">
								<div class="title">
									<?php echo $staff->seo->name; ?>
								</div>

								<div class="description">
									<?php echo $staff->getDescription(); ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					<?php } ?>

				</div>
			<?php }
		} ?>

	</div>
<?php } ?>