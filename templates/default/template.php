<!DOCTYPE html>
<html>
<head>
<meta content="charset=UTF-8" />
<title>Default Template</title>
</head>

<body>

	<?php echo dyn::get('content'); ?>
<h2>Slots</h2>
<?php

$slot = slot::factory(1);
echo $slot->getContent();
?>
</body>
</html>
