<?php
use itnew\models\Structure;
use itnew\controllers\RecordsController;

/**
 * @var RecordsController $this
 */
?>

<?php
$this->renderPartial("../content/_show_type");
$this->renderPartial("../content/_blocks", compact("blocks"));

if (!Structure::isContentShowPage()) {
	$this->renderPartial("../partials/_add_panel");
}
?>