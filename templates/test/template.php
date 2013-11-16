<!DOCTYPE html>
<html>
<head>
<meta content="charset=UTF-8" />
<title>Test Template</title>
</head>

<body>

	<?php slot::set('slotname'); ?>
    
    <?php echo dyn::get('content'); ?>

</body>
</html>
