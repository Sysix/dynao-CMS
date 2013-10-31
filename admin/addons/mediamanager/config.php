<?php

require(dir::addon('mediamanager', 'lib/classes/media.php'));
require(dir::addon('mediamanager', 'lib/classes/mediaUtils.php'));

backend::addNavi('Media', url::backend('media'), 'picture', 2);

?>