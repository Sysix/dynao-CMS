<?php
backend::addAddonNavi(lang::get('imexport'), url::backend('import'),'circle', -1, function() {
 return dir::addon('imexport', 'page'.DIRECTORY_SEPARATOR.'import.php');
});
?>