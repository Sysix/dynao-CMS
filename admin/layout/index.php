<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo backend::getTitle(); ?> - Backend</title>
    
	<?php echo layout::getCSS(); ?>
    
</head>

<body>

	<header id="head">
    
    	<h1><?php echo backend::getPageName(); ?></h1>
        
        <!--<a class="btn btn-default" href="#"><i class="fa fa-plus"></i><span>Neue Seite</span></a>-->
        
        <div id="panel">
        	
            <i class="fa fa-user"></i>
            
        	<span><?php echo dyn::get('user')->get('firstname')." ".dyn::get('user')->get('name'); ?></span> <i class="fa fa-chevron-down"></i>
            
            <ul>
            	<li><a href="<?php echo dyn::get('hp_url'); ?>" target="_blank"><?php echo lang::get('visit_site'); ?></a></li>
                <li><a href="index.php?logout=1"><?php echo lang::get('logout'); ?></a></li>
            </ul>
            
        </div>
        
    	<div id="expand" class="fa fa-bars"></div>
        
        <div class="clearfix"></div>
    
    </header>

	<section id="left">

    	<a id="logo" href="http://dynao.de" target="_blank">
        	<img src="layout/img/logo.svg" alt="dynao CMS Logo" /> <span>dynao CMS</span>
        </a>

        <h4><?= lang::get('main_navi'); ?></h4>

        <?php echo backend::getNavi(); ?>

        <h4><?= lang::get('addon_navi'); ?></h4>

        <hr />

        <?php echo backend::getAddonNavi(); ?>

        <div id="tool">
        	<ul>
            	<li><a href="http://dynao.de" target="_blank" class="fa fa-globe"></a></li>
            	<li><a href="index.php?logout=1" class="fa fa-sign-out"></a></li>
            </ul>
        </div>

    </section>
    
    <section id="content">
        <?php echo backend::getSubnavi(); ?>
    	<?php echo dyn::get('content'); ?>
    </section>

	<?php echo layout::getJS(); ?>
</body>
</html>

