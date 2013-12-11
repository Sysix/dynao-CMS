<?php

$keywords = $this->get('keywords');
$description = $this->get('description');
$title = $this->get('name');

?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $title; ?> - <?= dyn::get('hp_name'); ?></title>
    <meta name="description" content="<?= $description ?>">
	<meta name="keywords" content="<?= $keywords ?>">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="/templates/default/css/style.css" rel="stylesheet">
</head>

<body>
	
    <header>
    	<div class="container">
    		<div class="row">
            	<div class="col-sm-2">
                	<img src="templates/default/images/logo.png" alt="logo">
                    <?= dyn::get('hp_name'); ?>
                </div>
            	<div class="col-sm-10">
                	<nav>
                    	<ul>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        	<li><a href="">Navipunkt</a></li>
                        </ul>
                        <select>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        	<option>Navipunkt</option>
                        </select>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
    	<div class="row">
        	<div class="col-lg-12">
                <section id="content">
               		<?php echo dyn::get('content'); ?> 
                </section>
            </div>
        </div>
    </div>
</body>
</html>
