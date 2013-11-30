<?php

if(!dyn::get('user')->hasPerm('admin[addon]')) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

backend::addSubnavi(lang::get('overview'),	url::backend('addons'), 	'eye', 0);

if($action == 'delete') {
	
	$addonClass = new addon($addon, false);
	echo $addonClass->delete();
}

if($action == 'install') {

	$addonClass= new addon($addon, false);	
	$install = ($addonClass->isInstall()) ? 0 : 1;
	
	$sql = sql::factory();
	$sql->setTable('addons');
	$sql->setWhere('`name` = "'.$addon.'"');
	$sql->addPost('install', $install);
	
	if(!$install)
		$sql->addPost('active', 0);
	
	$sql->update();
	
	
	if(!$addonClass->isInstall()) {
		$addonClass->install();
	} else {
		$addonClass->uninstall();	
	}
	
	echo message::success(lang::get('addon_save_success'));
	
}

if($action == 'active') {
	
	$addonClass = new addon($addon, false);	
	$active = ($addonClass->isActive()) ? 0 : 1;
	
	if(!$addonClass->isInstall()) {
		
		echo message::danger(sprintf(lang::get('addon_install_first'), $addon));
			
	} else {
	
		$sql = sql::factory();
		$sql->setTable('addons');
		$sql->setWhere('`name` = "'.$addon.'"');
		$sql->addPost('active', $active);
		$sql->update();
		
		echo message::success(lang::get('addon_save_success'));
		
	}
	
}

if($action == 'help') {
	$curAddon = new addon($addon);
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $curAddon->get('name'); ?></h3>
				</div>
                <div class="panel-body">              
					<?php
                        $file = dir::addon($addon, 'README.md');
                        if(file_exists($file)) {
                            echo markdown::parse(file_get_contents($file));
                        } else {
                            echo lang::get('addon_no_readme');	
                        }
                    ?>
                </div>
			</div>
		</div>
	</div>
<?php	
} else {
	
	$table = table::factory();
	$table->addCollsLayout('20,*,215');
	
	$table->addRow()
	->addCell('')
	->addCell(lang::get('name'))
	->addCell(lang::get('actions'));
	
	$table->addSection('tbody');
	
	$addons = scandir(dir::backend('addons/'));
	
	foreach($addons as $dir) {
		
		if(in_array($dir, ['.', '..', '.htaccess'])) 
			continue;
		
		$curAddon = new addon($dir);
		
		$install_url = url::backend('addons', ['addon'=>$dir, 'action'=>'install']);
		$active_url = url::backend('addons', ['addon'=>$dir, 'action'=>'active']);
		$delete_url = url::backend('addons', ['addon'=>$dir, 'action'=>'delete']);
		$help_url = url::backend('addons', ['addon'=>$dir, 'action'=>'help']);
		
		if($curAddon->isInstall()) {
			$install = '<a href="'.$install_url.'" class="btn btn-sm dyn-online">'.lang::get('addon_installed').'</a>';
		} else {
			$install = '<a href="'.$install_url.'" class="btn btn-sm dyn-offline">'.lang::get('addon_not_installed').'</a>';
		}
		
		if($curAddon->isActive()) {
			$active = '<a href="'.$active_url.'" class="btn btn-sm dyn-online fa fa-check" title="'.lang::get('addon_actived').'"></a>';
		} else {
			$active = '<a href="'.$active_url.'" class="btn btn-sm dyn-offline fa fa-times" title="'.lang::get('addon_not_actived').'"></a>';
		}
				
		$delete = '<a href="'.$delete_url.'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
		
		$table->addRow()
		->addCell('<a class="fa fa-question" href="'.$help_url.'"></a>')
		->addCell($curAddon->get('name').' <small>'.$curAddon->get('version').'</small>')
		->addCell('<span class="btn-group">'.$install.$active.$delete.'</span>');
			
	}
	
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo lang::get('addons'); ?></h3>
				</div>
				<?php echo $table->show(); ?>
			</div>
		</div>
	</div>
<?php
}
?>