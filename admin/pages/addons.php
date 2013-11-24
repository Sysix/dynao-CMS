<?php
$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

backend::addSubnavi(lang::get('overview'),	url::backend('addons'), 	'eye', 0);

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

$table = table::factory();
$table->addCollsLayout('*,215');

$table->addRow()
->addCell('Name')
->addCell(lang::get('actions'));

$table->addSection('tbody');

$addons = scandir(dir::backend('addons/'));

foreach($addons as $dir) {
	
	if(in_array($dir, ['.', '..', '.htaccess'])) 
		continue;
	
	$curAddon = new addon($dir);
	
	$online_url = url::backend('addons', ['addon'=>$dir, 'action'=>'install']);
	
	if($curAddon->isInstall()) {
		$online = '<a href="'.$online_url.'" class="btn btn-sm structure-online">'.lang::get('addon_installed').'</a>';
	} else {
		$online = '<a href="'.$online_url.'" class="btn btn-sm structure-offline">'.lang::get('addon_not_installed').'</a>';
	}
	$active_url = url::backend('addons', ['addon'=>$dir, 'action'=>'active']);
	
	if($curAddon->isActive()) {
		$active = '<a href="'.$active_url.'" class="btn btn-sm structure-online">'.lang::get('addon_actived').'</a>';
	} else {
		$active = '<a href="'.$active_url.'" class="btn btn-sm structure-offline">'.lang::get('addon_not_actived').'</a>';
	}
	
	$delete = '<a href="#" class="btn btn-sm btn-danger fa fa-trash-o"></a>';
	
	$table->addRow()
	->addCell($curAddon->get('name').' <small>'.$curAddon->get('version').'</small>')
	->addCell('<span class="btn-group">'.$online.$active.$delete.'</span>');
		
}

?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Addons</h3>
			</div>
			<?php echo $table->show(); ?>
		</div>
	</div>
</div>

<?php
?>