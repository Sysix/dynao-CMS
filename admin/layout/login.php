<!DOCTYPE html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Backend - <?php echo dyn::get('hp_name'); ?></title>
	<?php echo layout::getCSS(); ?>
</head>
<body>
	<div id="center"></div>
    <div id="login" class="panel panel-default">
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
                    <input type="checkbox" name="" /> <label><?php echo lang::get("login_remember"); ?></label>
                </div>
                
                <div class="clearfix"></div>   
                
            </div>
         
            
            <a href=""><?php echo lang::get("login_pwd_forget"); ?></a>
        </form>
    </div>
    
    <?php echo layout::getJS(); ?>
    
</body>
</html>