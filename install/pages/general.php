<?php
$error = false;

$table = table::factory();
                
$table->addRow()
->addCell(lang::get('type'))
->addCell(lang::get('value'))
->addCell(lang::get('status'));

$table->addSection('tbody');
				
if (version_compare(phpversion(), '5.4', '<')) {		
	$table->addRow()
	->addCell(lang::get('php_version'))
	->addCell('>5.4')
	->addCell('<span class="label label-danger">'.lang::get('php_version_54').'</span>');				
} else {
	$table->addRow()
	->addCell(lang::get('php_version'))
	->addCell('>5.4')
	->addCell('<span class="label label-success">'.lang::get('ok').'</span>');	
}
                
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
					
	$table->addRow()
	->addCell(stripPath($file))
	->addCell('0755');

	if(is_file($file)) {

		if(is_writeable($file)) {

			$table->addCell('<span class="label label-success">'.lang::get('ok').'</span>');

		} else {

			$table->addCell('<span class="label label-danger">'.lang::get('chmod_755').'</span>');	
			$error = true;

		}    
		                    
	} elseif(is_dir($file)) {

		if(is_writeable($file)) {

			$table->addCell('<span class="label label-success">'.lang::get('ok').'</span>');

		} else {

			$table->addCell('<span class="label label-danger">'.lang::get('chmod_755').'</span>');
			$error = true;
			
		}

	}
	
}

?>

<div class="row">
    <div class="col-lg-8">    
    	<?php			
			foreach(['json', 'session', 'curl', 'mysqli', 'pcre'] as $extension) {

            	if(!extension_loaded($extension)) {
            		echo message::danger(sprintf(lang::get('php_ext'), $extension));
					$error = true;
            	}
                    
            }
		?>    
        <div class="panel panel-default">        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
                </div>
                <div class="panel-body">                
                    <?php
						
						$form = form_install::factory('', '', 'index.php');
						$form->setSave(false);
						$form->addParam('page', $page);
						
						$form->delButton('back');
						
						$field = $form->addTextField('hp_name', dyn::get('hp_name'));
						$field->fieldName(lang::get('settings_name_of_site'));
						
						$field = $form->addTextField('hp_url', dyn::get('hp_url'));
						$field->fieldName(lang::get('settings_url_of_site'));
						
						$field = $form->addSelectField('lang', dyn::get('lang'));
						$field->fieldName(lang::get('settings_backend_lang'));
									
						$handle = opendir(dir::backend('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR));
						while($file = readdir($handle)) {
								
								if(in_array($file, ['.', '..']))
									continue;
								
								$field->add($file, $file);
						}
						
						if($form->isSubmit()) {
								
							$url = 'http://'.str_replace('http://', '', $form->get('hp_url'));
							$endSlash = substr($url, -1, 1);
							
							if($endSlash != '/') {
								$url .= '/';
							}
							
							dyn::add('hp_name', $form->get('hp_name'), true);
							dyn::add('hp_url', $url, true);
							dyn::add('lang', $form->get('lang'), true);
							dyn::save();
							
							if($error)
								echo message::danger('error');
							else
								$form->addParam('page', 'database');
								
							$form->delParam('success_msg');
						
						}
						
						echo $form->show();
					
					?>                
                </div>        
        </div>    
    </div>	
    <div class="col-lg-4">    
        <div class="panel panel-default">        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('settings'); ?></h3>
                </div>                
				<?php echo $table->show(); ?>        
        </div>    
    </div>    
</div>