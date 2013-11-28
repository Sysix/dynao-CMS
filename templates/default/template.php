<!DOCTYPE html>
<html>
<head>
    <meta content="charset=UTF-8" />
    <title>Default Template</title>
    
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato:300,400,700">
    <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/templates/default/css/style.css" />
</head>

<body>
	
    <header>
    	<div class="container">
    		<div class="row">
            	<div class="col-sm-2">
                	Logo
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
    
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>
