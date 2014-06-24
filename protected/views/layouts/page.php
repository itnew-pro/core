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

	<script>
		var LANG = "<?php echo Yii::app()->language; ?>";
	</script>

	<?php if (!Yii::app()->user->isGuest) { ?>
		<link rel="stylesheet" href="<?php echo Yii::app()->params["baseUrl"]; ?>/css/admin.css"/>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
		<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/tinymce/tinymce.min.js"></script>
		<script src="<?php echo Yii::app()->params["baseUrl"]; ?>/js/admin.js"></script>
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
<?php echo $content; ?>

<div id="loader"></div>

<?php if (Yii::app()->user->isGuest) { ?>
	<a href="#" id="login-button"></a>
<?php } else { ?>
	<a href="#" id="logout-button"></a>
	<div id="panel-tabs">
		<a href="#" class="panel-tab panel-tab-section" data-controller="section">
			<?php echo Yii::t("section", "Sections"); ?>
		</a>
		<a href="#" class="panel-tab panel-tab-content" data-controller="content">
			<?php echo Yii::t("content", "Content"); ?>
		</a>
	</div>
<?php } ?>

</body>
</html>