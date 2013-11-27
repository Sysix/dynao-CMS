<?php
	backend::addSubnavi(lang::get('general'),	url::backend('settings'),	'eye');
?>

<div class="row">
	
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang::get('general'); ?></h3>
            </div>
            <div class="panel-body">
            	<?php
				
				$form = form::factory('user', 'id=1', 'index.php');
				$form->setSave(false);
				$form->delButton('save');
				
				if($form->isSubmit()) {
					
					$url = 'http://'.str_replace('http://', '', $form->get('url'));
					$endSlash = substr($url, -1, 1);
					
					if($endSlash != '/') {
						$url .= '/';	
					}
					
					dyn::add('hp_name', $form->get('name'), true);
					dyn::add('hp_url', $url, true);
					dyn::add('lang', $form->get('lang'), true);
					dyn::add('template', $form->get('template'), true);
					dyn::save();
					
				}
				
				$field = $form->addTextField('name', dyn::get('hp_name'));
				$field->fieldName(lang::get('settings_name_of_site'));
				
				$field = $form->addTextField('url', dyn::get('hp_url'));
				$field->fieldName(lang::get('settings_url_of_site'));
				
				$field = $form->addSelectField('lang', dyn::get('lang'));
				$field->fieldName(lang::get('settings_backend_lang'));
							
				$handle = opendir(dir::backend('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR));
				while($file = readdir($handle)) {
						
						if(in_array($file, ['.', '..']))
							continue;
						
						$field->add($file, $file);
				}
				
				$field = $form->addSelectField('template', dyn::get('template'));
				$field->fieldName(lang::get('template'));
							
				$handle = opendir(dir::template());
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
            	<h3 class="panel-title"><?php echo lang::get('system'); ?></h3>
            </div>
			<?php
			
			$sql = sql::factory();
			$sql->query('SELECT VERSION()')->result();
			
			$table = table::factory(['class'=> ['table', 'table-spriped', 'table-hover']]);
			
			$table->addRow()
			->addCell('Type')
			->addCell('Value');
			
			$table->addSection('tbody');
			
			$table->addRow()
			->addCell(lang::get('dyn_version'))
			->addCell(dyn::get('version'));
			
			$table->addRow()
			->addCell(lang::get('php_version'))
			->addCell(phpversion());
			
			$table->addRow()
			->addCell(lang::get('mysql_version'))
			->addCell($sql->get('VERSION()'));
			
			
			echo $table->show();
			
			?>
        </div>
    </div>
    
    <div class="clearfix"></div>

</div>