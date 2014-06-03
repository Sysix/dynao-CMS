<?php

$navi = array(
	'licence' => array(lang::get('licence'), 'graduation-cap'),
	'general' => array(lang::get('general'), 'cogs'),
	'database' => array(lang::get('db'), 'database'),
	'finish' => array(lang::get('finish'), 'thumbs-o-up')
	);
	
layout::addCSS('http://fonts.googleapis.com/css?family=Lato:300,400,700');
layout::addCSS('http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');
layout::addCSS('http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
layout::addCSS('../admin/layout/css/style.css');

layout::addJS('http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js');
layout::addJS('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
layout::addJS('http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js');
layout::addJS('../admin/layout/js/session.js');
layout::addJS('../admin/layout/js/headroom.js');
layout::addJS('../admin/layout/js/swipe.js');
layout::addJS('../admin/layout/js/scripts.js');

?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<title><?php echo lang::get('installation'); ?> - dynao CMS</title>
    
    <?php echo layout::getCSS(); ?>
    
</head>

<body>

	<header id="head">
    
    	<h1><?php echo lang::get('installation'); ?></h1>
        
    	<div id="expand" class="fa fa-bars"></div>
        
        <div class="clearfix"></div>
    
    </header>

	<section id="left">
    
    	<a id="logo" href="http://dynao.de" target="_blank">
        	<img src="../admin/layout/img/logo.svg" alt="dynao CMS Logo" /> <span>dynao CMS</span>
        </a>
        
        <h4><? echo lang::get('main_navi'); ?></h4>
        <ul>
			<?php
            
				foreach($navi as $href=>$options) {
				
					$class = ($href == $page) ? 'active ' : '';
				
					echo '<li class="'.$class.'"><a><i class="fa fa-'.$options[1].'"></i><span>'.$options[0].'</span></a></li>';
				
				}
            
            ?>
        </ul>
    
    </section>
    
    <section id="content">
        <?php
        	include('pages/'.$page.'.php');
		?>
    </section>

	<?php echo layout::getJS(); ?>
</body>
</html>