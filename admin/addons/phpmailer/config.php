<?php

if(dyn::get('backend')) {
	
	backend::addAddonNavi(lang::get('phpmailer'), url::backend('phpmailer'), -1, function() {
        return dir::addon('phpmailer', 'page'.DIRECTORY_SEPARATOR.'phpmailer.php');
    });
	
}

?>