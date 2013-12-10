<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Default Template</title>
    
    <?php echo template::getCSS(); ?>
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
    
<?php echo template::getJS(); ?>
</body>
</html>
