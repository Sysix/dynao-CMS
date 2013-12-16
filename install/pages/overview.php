<div class="row">

    <div class="col-lg-8">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
                </div>
                <div class="panel-body">
                
                    <?php
						
						$form = form_install::factory('', '', 'index.php');
						
						$field = $form->addTextField('name', $form->get('name'));
						$field->fieldName('Seitenname');
						
						$field = $form->addTextField('url', $form->get('url'));
						$field->fieldName('URL der Seite');
						
						$field = $form->addSelectField('lang', dyn::get('lang'));
						$field->fieldName(lang::get('settings_backend_lang'));
									
						$handle = opendir(dir::backend('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR));
						while($file = readdir($handle)) {
								
								if(in_array($file, ['.', '..']))
									continue;
								
								$field->add($file, $file);
						}
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
	
    <div class="col-lg-4">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
                </div>
                <div class="panel-body">
                
                	<?php
					
					if (version_compare(phpversion(), '5.4', '<')) {
						echo 'PHP Version 5.4 wird mindestens benötigt<br />';						
					} else {
						echo 'PHP Version - OK<br />';	
					}
					
					echo '<br />';
					
					$writeable = [
						dir::cache(),
						dir::cache('page'.DIRECTORY_SEPARATOR),
						dir::backend('addons'.DIRECTORY_SEPARATOR),
						dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json')
					];
					
					function stripPath($file) {
						
						return str_replace(dir::base(), '', $file);
							
					}
					
					foreach($writeable as $file) {
						
						if(is_file($file)) {
							
							if(is_writeable($file)) {
								echo 'Datei '.stripPath($file).' - OK<br />';
							} else {
								echo 'Datei '.stripPath($file).' - Fehler<br />';
							}
							
							
						} elseif(is_dir($file)) {
							
							if(is_writeable($file)) {
								echo 'Ordner '.stripPath($file).' - OK<br />';
							} else {
								echo 'Ordner '.stripPath($file).' - Fehler<br />';
							}
							
						}
						
					}
					
					echo '<br />';
					
					foreach(['json', 'session', 'curl', 'mysqli', 'pcre'] as $extension) {
						
						if(!extension_loaded($extension)) {
							echo 'PHP Extension "'.$extension.'" konnte nicht geladen werden<br />';	
						}
						
					}
					?>
                
                </div>
        
        </div>
    
    </div>
    
</div>