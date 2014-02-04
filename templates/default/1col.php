<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
    <title><?= $this->get('name'); ?> - <?= dyn::get('hp_name'); ?></title>
    <meta name="description" content="<?= $this->get('description') ?>">
	<meta name="keywords" content="<?= $this->get('keywords') ?>">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="templates/default/css/style.css" rel="stylesheet">
</head>

<body>
	
    <header>
    	<div class="container">
    		<div class="row">
            	<div class="col-lg-12">
                	<a href="<?= dyn::get('hp_url'); ?>" class="logo">
                    	<?= dyn::get('hp_name'); ?>
                    </a>
                    
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle">
                        	Navigation <span class="caret"></span>
                        </button>
                    </div>
                    
                	<nav>
                    	<ul>
                        	<?php
                            foreach(navigation::getCategoryById(0) as $navi) {
								
								$class = ($navi->get('id') == $this->get('id')) ? 'class="active"' : '';
								
								echo '<li '.$class.'><a href="'.$navi->getUrl().'">'.$navi->get('name').'</a></li>';
								
							}
							?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    
    <section id="top">
    	<div class="container">
    		<div class="row">
        		<div class="col-lg-12">
				<?php
                
                if(block::getBlock('welcome'))
                    echo block::getBlock('welcome');
                else
                    echo '<h1>'.$this->get('name').'</h1>';
                
                ?>
        		</div>
        	</div>
        </div>
    </section>
    
    <div class="container">
    	<div class="row">
        	<div class="col-lg-12">
                <section id="content">
                	<?= block::getBlock('boxen'); ?>
                    
                    <div class="row">
        				<div class="col-md-12">
               				<?= dyn::get('content'); ?> 
                    	</div>
                    </div>
                </section>
            </div>
        </div>
    	<div class="row">
        	<div class="col-lg-12">
                <section id="footer">
               		&copy; Copyright 2013 - Design by <a href="http://dynao.de" target="_blank">dynao.de</a>
                </section>
            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    <script src="templates/default/js/template.js"></script>
</body>
</html>
