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
                
                if(slot::getSlot('welcome'))
                    echo slot::getSlot('welcome');
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
                	<?= slot::getSlot('boxen'); ?>
                    
                    <div class="row">
        				<div class="col-md-8">
               				<?= dyn::get('content'); ?> 
                    	</div>
        				<div class="col-md-4">
                        	<?php
								
								if(navigation::getCategoryById($this->get('id'))) {
									
									echo '<h2>Navigation</h2>';
									
									echo '<ul class="subnav">';
										
									foreach(navigation::getCategoryById($this->get('id')) as $subnavi) {
										echo '<li><a href="'.$subnavi->getUrl().'">'.$subnavi->get('name').'</a></li>';
									}
										
									echo '</ul>';
								
								}
								
							?>
                        	<h2>Sidebar</h2>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                            <p>At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
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
