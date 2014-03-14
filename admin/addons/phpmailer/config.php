<?php

if(dyn::get('backend')) {
	
	backend::addAddonNavi(lang::get('phpmailer'), url::backend('phpmailer'));
	
}

?>