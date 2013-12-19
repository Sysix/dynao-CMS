<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $this->get('name'); ?> - <?= dyn::get('hp_name'); ?></title>
    <meta name="description" content="<?= $this->get('description') ?>">
	<meta name="keywords" content="<?= $this->get('keywords') ?>">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="/templates/default/css/style.css" rel="stylesheet">
</head>

<body>
	
    <header>
    	<div class="container">
    		<div class="row">
            	<div class="col-lg-12">
                	<a href="<?= dyn::get('hp_url'); ?>" class="logo">
                    	<?= dyn::get('hp_name'); ?>
                    </a>
                    
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
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        	Navigation <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li><a href="#">Separated link</a></li>
                        </ul>
                    </div>
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
                	<?php echo slot::getSlot('boxen'); ?>
                    
                    <div class="row">
        				<div class="col-md-12">
               				<?php echo dyn::get('content'); ?> 
                    	</div>
                    </div>
                </section>
            </div>
        </div>
    	<div class="row">
        	<div class="col-lg-12">
                <section id="footer">
               		&copy; Copyright 2013
                </section>
            </div>
        </div>
    </div>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
</body>
</html>
