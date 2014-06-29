<?php
use itnew\controllers\TextController;
use itnew\models\Structure;

/**
 * @var TextController $this
 */
?>

<?php
$this->renderPartial("/content/_show_type");
$this->renderPartial("/content/_blocks", compact("blocks"));

if (!Structure::isContentShowPage()) {
	$this->renderPartial("/partials/_add_panel");
}
?>