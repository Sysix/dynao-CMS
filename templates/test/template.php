<!DOCTYPE html>
<html>
<head>
<meta content="charset=UTF-8" />
<title>Test Template</title>
</head>

<body>

	<?php slot::set('slotname'); ?>
    
    <?php slot::set('slotname2'); ?>
    
    <?php echo dyn::get('content'); ?>
    
    <?php var_dump(slot::getArray()); ?>

</body>
</html>
