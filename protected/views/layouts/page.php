<?php
use itnew\models\Structure;
use itnew\components\Html;

/**
 * @var string $content
 */
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $this->pageTitle; ?></title>

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,300,700&subset=latin,cyrillic-ext'
		  rel='stylesheet' type='text/css'>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

	<!-- убрать -->
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/jquery.mousewheel-3.0.6.pack.js"></script>

	<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/fancybox/jquery.fancybox.css"/>
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/fancybox/jquery.fancybox.pack.js"></script>

	<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/fancybox/jquery.fancybox-thumbs.css"/>
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/fancybox/jquery.fancybox-thumbs.js"></script>

	<link rel="stylesheet"
		  href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/fancybox/jquery.fancybox-buttons.css"/>
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/fancybox/jquery.fancybox-buttons.js"></script>

	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/jquery.id.chopslider-2.2.0.free.min.js"></script>
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/jquery.id.cstransitions-1.2.min.js"></script>
	<!-- убрать -->

	<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/css.css"/>

	<?php if (!Yii::app()->user->isGuest) { ?>
		<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/admin.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
		<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/tinymce/tinymce.min.js"></script>
	<?php } ?>

	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/js.js"></script>

	<?php if (Structure::model()->isCss()) { ?>
		<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/static/<?php
			echo Yii::app()->params["siteId"]; ?>/css.css"/>
	<?php } ?>
	<?php if (Structure::model()->isJs()) { ?>
		<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/static/<?php
			echo Yii::app()->params["siteId"]; ?>/js.js"></script>
	<?php } ?>
</head>
<body>
<?php
echo $content;

$hide = null;
if (!Yii::app()->user->isGuest) {
	$hide = "hide";
}

echo CHtml::ajaxButton(
	null,
	$this->createUrl(
		"ajax/index",
		array(
			"controller" => "login",
			"action"     => "form",
			"language"   => Yii::app()->language,
		)
	),
	array(
		"beforeSend" => 'function() {
				$(".loader-login-button").show();
			}',
		"success"    => 'function(html) {
				$(".loader-login-button").hide();
				$("body").append(html);
				showWindow("login");
			}',
		"error" => 'function (xhr) {
			getExceptionError(xhr);
		}'
	),
	array(
		"id"    => "login-button",
		"class" => $hide,
		"live"  => false,
	)
);

echo Html::loader("login-button");

$hide = null;
if (Yii::app()->user->isGuest) {
	$hide = "hide";
}

echo CHtml::ajaxButton(
	null,
	$this->createUrl(
		"ajax/index",
		array(
			"controller" => "login",
			"action"     => "logout",
			"language"   => Yii::app()->language,
		)
	),
	array(
		"beforeSend" => 'function() {
				$(".loader-logout-button").show();
			}',
		"success"    => 'function(html) {
				$(".loader-logout-button").hide();
				$("#login-button").removeClass("hide");
				$("#logout-button").addClass("hide");
				window.location.replace("");
			}'
	),
	array(
		"id"    => "logout-button",
		"class" => $hide,
		"live"  => false,
	)
);

echo Html::loader("logout-button");
?>

<?php if (!Yii::app()->user->isGuest) { ?>

	<div id="panel-tabs">

		<?php
		foreach (array("section", "content") as $tab) {
			switch ($tab) {
				case 'section':
					$title = "Sections";
					break;
				case 'content':
					$title = "Content";
					break;
				case 'settings':
					$title = "Settings";
					break;
			}
			echo CHtml::ajaxButton(
				Yii::t($tab, $title),
				$this->createUrl(
					"ajax/index",
					array(
						"controller" => $tab,
						"action"     => "panel",
						"language"   => Yii::app()->language,
					)
				),
				array(
					"beforeSend" => 'function() {
							$(".loader-panel-tab-' . $tab . '").show();
						}',
					"success"    => 'function(html) {
							$(".loader-panel-tab-' . $tab . '").hide();
							$("#panel").remove();
							$("#subpanel").remove();
							$(".panel-tab").removeClass("active");
							$("body").append(html);
							$("#panel-tabs").addClass("active");
							$("#panel-tab-' . $tab . '").addClass("active");
						}'
				),
				array(
					"id"    => "panel-tab-{$tab}",
					"class" => "panel-tab"
				)
			);

			echo Html::loader("panel-tab-{$tab}");

		}
		?>

	</div>

<?php } ?>

</body>
</html>