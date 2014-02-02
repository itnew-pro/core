<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $this->pageTitle; ?></title>

	<link rel="stylesheet" href="/css/css.css"/>
	<?php if (!Yii::app()->user->isGuest) { ?>
		<link rel="stylesheet" href="/css/admin.css"/>
	<?php } ?>
	<link rel="stylesheet" href="/style.css"/>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<?php if (!Yii::app()->user->isGuest) { ?>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/tinymce/tinymce.min.js"></script>
	<?php } ?>
	<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/js.js"></script>
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