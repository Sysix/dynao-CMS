<?php
if($action == 'deleteCache') {
	cache::clear();
	pageCache::clearAll();
	extension::get('SETTINGS_DELETE_CACHE');
	echo message::success(lang::get('delete_cache_success'), true);	
}
if($action == 'loadTemplate') {
	$template = new template(dyn::get('template'));
	if($template->install(true) !== true) {
		echo message::danger(lang::get('load_template_failed'), true);
	} else {
		echo message::success(lang::get('load_template_success'), true);
	}	
}
?>
<div class="row">	
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title pull-left"><?php echo lang::get('general'); ?></h3>
                <div class="btn-group pull-right">
                	<a href="<?php echo url::backend('settings', ['subpage'=>'main', 'action'=>'deleteCache']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('delete_cache'); ?></a>
                    <a href="<?php echo url::backend('settings', ['subpage'=>'main', 'action'=>'loadTemplate']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('load_template'); ?></a>
                </div>
                <div class="clearfix"></div>
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
					
					if(dyn::get('template') != $form->get('template')) {
						$template = new template($form->get('template'));
						if($template->install() !== true) {
							$form->setSuccessMessage(null);
						} else {
							dyn::add('template', $form->get('template'), true);
						}
					}
					
					dyn::add('hp_name', $form->get('hp_name'), true);
					dyn::add('hp_url', $url, true);
					dyn::add('lang', $form->get('lang'), true);
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
    
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
            	<h3 class="panel-title"><?php echo lang::get('template'); ?></h3>
            </div>
			<?php
			
			$template = new template(dyn::get('template'));
			
			$table = table::factory(['class'=> ['table', 'table-spriped', 'table-hover']]);			
			$table->addSection('tbody');
			
			$table->addRow()
			->addCell(lang::get('name'))
			->addCell($template->get('name'));
			
			$table->addRow()
			->addCell(lang::get('author'))
			->addCell($template->get('author'));
			
			$table->addRow()
			->addCell(lang::get('version'))
			->addCell($template->get('version'));
			
			$table->addRow()
			->addCell('<a href="'.$template->get('supportlink').'" class="btn btn-sm btn-default btn-block" target="_blank">Support besuchen</a>', ['colspan'=>2]);	
			
			echo $table->show();
			
			?>
        </div>
    </div>
        
    <div class="clearfix"></div>

</div>