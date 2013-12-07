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
				$form->addFormAttribute('class', '');
				$form->delButton('save');
				$form->delButton('back');
				$form->addParam('subpage', backend::$getVars[1]);
				
				if($form->isSubmit()) {
					
					$url = 'http://'.str_replace('http://', '', $form->get('hp_url'));
					$endSlash = substr($url, -1, 1);
					
					if($endSlash != '/') {
						$url .= '/';	
					}
					
					dyn::add('hp_name', $form->get('hp_name'), true);
					dyn::add('hp_url', $url, true);
					dyn::add('lang', $form->get('lang'), true);
					dyn::add('template', $form->get('template'), true);
					dyn::add('start_page', $form->get('start_page_id'), true);
					dyn::add('error_page', $form->get('error_page_id'), true);
					dyn::save();
					
				}
				
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
				
				$field = $form->addSelectField('template', dyn::get('template'));
				$field->fieldName(lang::get('template'));
							
				$handle = opendir(dir::template());
				while($file = readdir($handle)) {
						
						if(in_array($file, ['.', '..']))
							continue;
						
						$field->add($file, $file);
				}
				
				$field = $form->addLinkField('start_page', dyn::get('start_page'));
				$field->fieldName(lang::get('start_page'));
				
				$field = $form->addLinkField('error_page', dyn::get('error_page'));
				$field->fieldName(lang::get('error_page'));
				
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
			->addCell(lang::get('type'))
			->addCell(lang::get('value'));
			
			$table->addSection('tbody');
			
			$table->addRow()
			->addCell(lang::get('dynao_version'))
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