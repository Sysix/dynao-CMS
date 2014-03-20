<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo backend::getTitle(); ?> - Backend</title>
    
	<?php echo layout::getCSS(); ?>
    
</head>

<body>

	<header>
    
    	<a href="http://dynao.de" target="_blank" class="logo">
        	<img src="layout/img/logo.png" alt="Logo" />
        </a>
        
        <div id="user">
        	<?php echo dyn::get('user')->get('firstname')." ".dyn::get('user')->get('name'); ?> <i class="fa fa-chevron-down"></i>
            
            <ul>
            	<li><a href="<?php echo dyn::get('hp_url'); ?>" target="_blank"><?php echo lang::get('visit_site'); ?></a></li>
                <li><a href="index.php?logout=1"><?php echo lang::get('logout'); ?></a></li>
            </ul>
            
        </div>
    
    </header>
    
    <nav>
    	
        <h3>Navigation</h3>
        
        <!--
    	<ul>
        	<li><a href=""><span>Dashboard</span><i class="fa fa-desktop"></i></a></li>
        	<li class="active"><a href=""><span>Inhalte</span><i class="fa fa-list-ol"></i></a><i class="fa fa-chevron-down expand"></i>
            	<ul>
                	<li><a href="">Struktur</a></li>
                	<li><a href="">Module</a></li>
                	<li><a href="">Slots</a></li>
                </ul>
            </li>
        	<li><a href=""><span>Addons</span><i class="fa fa-code-fork"></i></a><i class="fa fa-chevron-down expand"></i>
            	<ul>
                	<li><a href="">Struktur</a></li>
                	<li><a href="">Module</a></li>
                	<li><a href="">Slots</a></li>
                </ul>
           	</li>
        	<li><a href=""><span>Benutzer</span><i class="fa fa-group"></i></a><i class="fa fa-chevron-down expand"></i>
            	<ul>
                	<li><a href="">Struktur</a></li>
                	<li><a href="">Module</a></li>
                	<li><a href="">Slots</a></li>
                </ul>
            </li>
        	<li><a href=""><span>Einstellungen</span><i class="fa fa-cogs"></i></a></li>
        </ul>
        -->
        
        <?php echo backend::getNavi(); ?>
        
        <h3>Addons</h3>
        
        <hr />
        
        <!--
        <ul>
        	<li><a href=""><span>SEO Addon</span><i class="fa fa-map-marker"></i></a></li>
        	<li><a href=""><span>Meta Infos</span><i class="fa fa-code"></i></a><i class="fa fa-chevron-down expand"></i>
            	<ul>
                	<li><a href="">Kategorien</a></li>
                	<li><a href="">Artikel</a></li>
                </ul>
            </li>
        </ul>
        -->
        
        <?php echo backend::getAddonNavi(); ?>
        
    </nav>
    
    <section>
    	
        <div id="top">
        
        	<h1><?php echo backend::getPageName(); ?></h1>
            
            <span id="nav-expand" class="fa fa-bars"></span>
        	
            <!--
        	<ul class="subnav">
        		<li class="active"><a href="">General</a></li>
        		<li><a href="">Next Page</a></li>
        	</ul>
            -->
            <?php echo backend::getSubnavi(); ?>
        	
        </div>
        
        <div id="content">
        	<?php echo dyn::get('content'); ?>
        </div>
        
    </section>

	<?php echo layout::getJS(); ?>
</body>
</html>
