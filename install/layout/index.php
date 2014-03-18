<?php

$navi = array(
	'licence' => array(lang::get('licence'), ''),
	'general' => array(lang::get('general')),
	'database' => array(lang::get('db')),
	'finish' => array(lang::get('finish'))
	);

?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo lang::get('installation'); ?> - dynaoCMS</title>
	
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Open+Sans+Condensed:300,700" media="screen">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.min.css" media="screen">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="../admin/layout/css/style.css" media="screen">
    <link rel="stylesheet" href="../admin/layout/css/mobile.css" media="screen">
    <link rel="stylesheet" href="layout/css/style.css" media="screen">
</head>

<body>
	<div class="container">
    	<div class="col-12">
        	<div id="navi">
                <ul>
                    <?php
					
						foreach($navi as $href=>$options) {
						
							$class = ($href == $page) ? 'active ' : '';
							
							echo '<li class="'.$class.'"><a>'.$options[0].'</a></li>';
							
						}
					
					?>
                </ul>
            </div><!--end #navi-->
            <div id="wrap">
                <div id="subnavi">
                    
                    <h1><?php echo lang::get('installation'); ?></h1>
                    
                    <ul class="subnav">
                        <?php
						
							foreach($navi as $href=>$options) {
							
								$class = ($href == $page) ? ' class="active"' : '';
								
								echo '<li'.$class.'>'.$options[0].'</li>';
								
							}
						
						?>
                    </ul>
                    
                </div><!--end #subnavi-->
                
                <div id="content">
                	<?php
                		include('pages/'.$page.'.php');
					?>
                </div><!--end #content-->
                
                <div class="clearfix"></div>
            </div><!--end #wrap-->
    	</div>
	</div>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
</body>
</html>