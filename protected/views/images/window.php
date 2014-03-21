<?php echo CHtml::form(); ?>

	<?php $this->renderPartial("_window_list", compact("model")); ?>

	<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>