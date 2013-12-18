<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $this->get('name'); ?> - <?= dyn::get('hp_name'); ?></title>
    <meta name="description" content="<?= $this->get('description') ?>">
	<meta name="keywords" content="<?= $this->get('keywords') ?>">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/templates/default/css/style.css" rel="stylesheet">
</head>

<body>
	
    <header>
    	<div class="container">
    		<div class="row">
            	<div class="col-sm-2">
                	<a href="<?= dyn::get('hp_url'); ?>" class="logo">
                    	<?= dyn::get('hp_name'); ?>
                    </a>
                </div>
            	<div class="col-sm-10">
                	<nav>
                    	<ul>
                        	<?php
                            foreach(navigation::getCategoryById(0) as $navi) {
								
								$class = ($navi->get('id') == $this->get('id')) ? 'class="active"' : '';
								
								echo '<li '.$class.'><a href="'.$navi->getUrl().'">'.$navi->get('name').'</a>';
								if($navi->hasChild()) {
									
									echo '<ul>';
									
									foreach(navigation::getCategoryById($navi->get('id')) as $subnavi) {
										echo '<li><a href="'.$subnavi->getUrl().'">'.$subnavi->get('name').'</a>';
									}
									
									echo '</ul>';
									
								}
								echo '</li>';
								
							}
							?>
                        </ul>
                        <div class="clearfix"></div>
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
                
                if($this->isStart())
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
                    
               		<?php echo dyn::get('content'); ?> 
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
</body>
</html>
