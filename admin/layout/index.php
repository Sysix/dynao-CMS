<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include_once('../lib/classes/autoload.php');
autoload::register();

lang::setDefault();
lang::setLang('de_de');

mb_internal_encoding('UTF-8');

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

cache::setCache(false);
?>
<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Backend - dynaoCMS</title>
	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
	
	<!-- CSS Dateien einbinden-->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link href="css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/mobile.css">

</head>

<body>
	<section id="navi">
		<ul>
			<li class="active">
				<a class="icon-desktop" href="index.php?page=dashboard">
			   		<span>Dashboard</span>
				</a>
			</li>
			<li>
				<a class="icon-list" href="index.php?page=structure">
			   		<span>Structure</span>
				</a>
			</li>
			<li>
				<a class="icon-edit" href="">
			   		<span>Content</span>
				</a>
			</li>
			<li>
				<a class="icon-user" href="">
			   		<span>User</span>
				</a>
			</li>
			<li>
				<a class="icon-code-fork" href="">
			   		<span>Addons</span>
				</a>
			</li>
			<li>
				<a class="icon-cogs" href="">
			   		<span>Settings</span>
				</a>
			</li>
		</ul>
	</section><!--end #navi-->
	
	<section id="subnavi">
		<div id="user">
		
			<img src="img/user/defaultm.png" alt="Profilbild" />
			
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
			
			<a href="" class="icon-lock logout"> <span>Logout</span></a>
		
		</div><!--end #user-->
		
		<h1>Dashboard</h1>
		
		<ul class="subnav">
			<li class="active"><a href="" class="icon-home"><span>Overview</span></a></li>
			<li><a href="" class="icon-bar-chart"><span>Reports</span></a></li>
			<li><a href="" class="icon-terminal"><span>Logs</span></a></li>
		</ul>
		
	</section><!--end #subnavi-->
	
	<section id="content">
<?php 		
$page = (isset($_GET['page'])) ? $_GET['page'] : 'dashboard';		
$file = '../pages/'.$page.'.php';
	
if(file_exists($file)) {			
include($file);
}

?>		
	</section><!--end #content-->
	
	<section id="tools">
	
		<a id="trash" href=""></a>
		
		<div id="trash-text">
			<h4>Papierkorb</h4>
			Elemente hier rein ziehen, um sie in den Papierkorb zu verschieben, draufklicken um den Inhalt anzeigen zu lassen.
		</div>
		
	</section><!--end #tools-->

	<!-- Javascript Dateien einbinden-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/scripts.js"></script>
</body>
</html>