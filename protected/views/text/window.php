<?php echo CHtml::form(); ?>

<?php $this->renderPartial("../text/_window", compact("model")); ?>

<?php $this->renderPartial("../content/_window_button", compact("model")); ?>

<?php echo CHtml::endForm(); ?>