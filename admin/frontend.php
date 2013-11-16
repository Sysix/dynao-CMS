<?php

dyn::add('content', ob_get_contents());

ob_end_clean();

include(dir::template().DIRECTORY_SEPARATOR.dyn::get('template').DIRECTORY_SEPARATOR."template.php");

var_dump(slot::getArray());

?>