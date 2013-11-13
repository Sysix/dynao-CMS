<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Backend - <?php echo dyn::get('hp_name'); ?></title>
	<?php echo layout::getCSS(); ?>
</head>

<body>
<div id="wrap">
	<div id="content">
		<?php echo dyn::get('content'); ?>
	</div><!--end #content-->	
	<div class="clearfix"></div>
</div><!--end #wrap-->
<?php echo layout::getJS(); ?>
</body>
</html>