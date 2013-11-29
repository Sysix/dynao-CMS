<?php

include_once(__DIR__.'/composer/Autoload/ClassLoader.php');
$loader = new ClassLoader();
$loader->addPrefix(false, dir::vendor());
return $loader;

?>