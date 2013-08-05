<?php

$navi = array(
	'dashboard' => array('Dashboard', 'desktop'),
	'structure' => array('Strucutre', 'list'),
	'content' => array('Content', 'edit'),
	'user' => array('User', 'user'),
	'addons' => array('Addons', 'code-fork'),
	'settings' => array('Settings', 'cogs')
	);

?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Backend - dynaoCMS</title>
	<?php
	echo layout::getCSS();
	?>
</head>

<body>
	<section id="navi">
		<ul>
			<?php
			foreach($navi as $href=>$options) {
			
				$class = ($href == $page) ? ' class="active"' : '';
				
				echo '<li'.$class.'><a class="icon-'.$options[1].'" href="'.url::backend($href).'"><span>'.$options[0].'</span></a></li>';
				
			}
			
			?>
		</ul>
	</section><!--end #navi-->
	
    <div id="wrap">
        <div id="subnavi">
            <div id="user">
            
                <img src="layout/img/user/defaultm.png" alt="Profilbild" />
                
                <div class="dropdown">
                    <a class="icon-cog settings dropdown-toggle" data-toggle="dropdown" href=""></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                        <li><a tabindex="-1" href="#">Action</a></li>
                        <li><a tabindex="-1" href="#">Another action</a></li>
                        <li><a tabindex="-1" href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a tabindex="-1" href="#">Separated link</a></li>
                    </ul>
                </div>
                
                <h3>Aaron Iker</h3>
                Administrator
                
                <a class="icon-envelope messages" href=""><span>2</span></a>
                
                <a href="index.php?logout=1" class="icon-lock logout"> <span>Logout</span></a>
            
            </div><!--end #user-->
            
            <h1>Dashboard</h1>
            
            <ul class="subnav">
                <li class="active"><a href="" class="icon-home"><span>Overview</span></a></li>
                <li><a href="" class="icon-bar-chart"><span>Reports</span></a></li>
                <li><a href="" class="icon-terminal"><span>Logs</span></a></li>
            </ul>
            
        </div><!--end #subnavi-->
        
        <div id="content">
            <?php 		
                echo $CONTENT;
            ?>		
        </div><!--end #content-->
        
        <div class="clearfix"></div>
    </div><!--end #wrap-->
	
	<section id="tools">
	
		<a id="trash" href=""></a>
		
		<div id="trash-text">
			<h4>Papierkorb</h4>
			Elemente hier rein ziehen, um sie in den Papierkorb zu verschieben, draufklicken um den Inhalt anzeigen zu lassen.
		</div>
		
	</section><!--end #tools-->

<?php echo layout::getJS(); ?>
</body>
</html>