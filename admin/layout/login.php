<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Backend - dynaoCMS</title>
	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
	
	<!-- CSS Dateien einbinden-->
	<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link href="layout/css/bootstrap.css" rel="stylesheet">
	<link href="layout/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="layout/css/style.css" rel="stylesheet">
	<link href="layout/css/mobile.css" rel="stylesheet">

</head>
<body>
	<div id="center"></div>
    <div id="login" class="panel">
        <form action="index.php" method="post">
        	<?php echo $CONTENT; ?>
            <div class="input-group">
          		<span class="input-group-addon icon-envelope"></span>
        		<input class="form-control" type="text" placeholder="E-Mail" name="email" />
        	</div>
            
            <div class="clearfix"></div>
            
            <div class="input-group">
          		<span class="input-group-addon icon-key"></span>
        		 <input class="form-control" type="password" placeholder="Passwort" name="password" />
        	</div>
            
            <div class="panel-footer">
            
                <button type="submit" class="btn btn-default pull-right" name="login">Login</button>
                
                <div id="remember">
                    <input type="checkbox" name="" /> <label>Login merken</label>
                </div>
                
                <div class="clearfix"></div>   
                
            </div>
         
            
            <a href="">Passwort vergessen ?</a>
        </form>
    </div>
    
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="layout/js/bootstrap.js"></script>
	<script src="layout/js/scripts.js"></script>
    
</body>
</html>