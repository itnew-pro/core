<?php
use itnew\controllers\FeedbackController;
use itnew\models\Structure;

/**
 * @var FeedbackController $this
 */
?>

<?php
$this->renderPartial("/content/_show_type");
$this->renderPartial("/content/_blocks", compact("blocks"));

if (!Structure::isContentShowPage()) {
	$this->renderPartial("/partials/_add_panel");
}
?>