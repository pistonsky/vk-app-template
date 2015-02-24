<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="<?php echo Yii::$app->request->baseUrl;?>/css/bootstrap.min.css">
</head>
<div id="page">
<body>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/underscore-min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/backbone-min.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/bootstrap.min.js"></script>
	<script src="//vk.com/js/api/xd_connection.js?2" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/openapi.js" type="text/javascript"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/vk.js"></script>	
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/md5.js"></script>
	<script src="<?php echo Yii::$app->request->baseUrl;?>/js/jquery-ui.min.js"></script>
	<?= $content ?>
</body>
</div>
</html>