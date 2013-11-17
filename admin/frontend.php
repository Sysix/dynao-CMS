<?php

dyn::add('content', ob_get_contents());

ob_end_clean();

echo $TEMPLATE;

?>