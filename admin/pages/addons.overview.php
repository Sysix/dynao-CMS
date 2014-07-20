<?php

if(!dyn::get('user')->hasPerm('admin[addon]')) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

$plugin = type::super('plugin', 'string');

if($action != '') {

    if($plugin) {
        $addonClass = new plugin($addon, $plugin);
    } else {
        $addonClass= new addon($addon);
    }

}

if($action == 'delete') {
	$addonClass->delete();
}

if($action == 'install') {

	$success = true;
	
	if(!$addonClass->isInstall()) {
		if(!$addonClass->install()) {
			$success = false;
		}
	} else {
		$addonClass->uninstall();	
	}
	
	if($success) {
		
		$install = ($addonClass->isInstall()) ? 0 : 1;

		$sql = $addonClass->getSqlObj()->addPost('install', $install);
		
		if(!$install)
			$sql->addPost('active', 0);
		
		$sql->update();
		
		echo message::success(lang::get('addon_save_success'));
	}
	
}

if($action == 'active') {

	$active = ($addonClass->isActive()) ? 0 : 1;
	
	if(!$addonClass->isInstall()) {
		
		echo message::danger(sprintf(lang::get('addon_install_first'), $addon));
			
	} else {
	
		$addonClass->getSqlObj()->addPost('active', $active)->update();
		
		echo message::success(lang::get('addon_save_success'));
		
	}
	
}

if($action == 'help') {
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo $curAddon->get('name'); ?></h3>
                    <div class="pull-right">
                    	<?php if($curAddon->get('supportlink')) { ?>
						<a href="<?php echo $curAddon->get('supportlink'); ?>" class="btn btn-sm btn-warning" target="_blank"><?php echo lang::get('visit_site'); ?></a>
                        <?php } ?>
						<a href="<?php echo url::backend('addons', ['subpage'=>'overview']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('back'); ?></a>
					</div>
					<div class="clearfix"></div>
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
	
	$addons = scandir(dir::backend('addons').DIRECTORY_SEPARATOR);
	
	if(count($addons)) {
	
	foreach($addons as $dir) {
		
		if(in_array($dir, ['.', '..', '.htaccess'])) 
			continue;
		
		$curAddon = new addon($dir);
		
		$install_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'action'=>'install']);
		$active_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'action'=>'active']);
		$delete_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'action'=>'delete']);
		$help_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'action'=>'help']);

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

        if($curAddon->isInstall()) {
            $plugins = $curAddon->showPluginFolder();

            foreach($plugins as $pluginName => $plugin) {

                $install_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'plugin'=>$pluginName, 'action'=>'install']);
                $active_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'plugin'=>$pluginName, 'action'=>'active']);
                $delete_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'plugin'=>$pluginName, 'action'=>'delete']);
                $help_url = url::backend('addons', ['subpage'=>'overview', 'addon'=>$dir, 'plugin'=>$pluginName, 'action'=>'help']);

                if($plugin->isInstall()) {
                    $install = '<a href="'.$install_url.'" class="btn btn-xs dyn-online">'.lang::get('addon_installed').'</a>';
                } else {
                    $install = '<a href="'.$install_url.'" class="btn btn-xs dyn-offline">'.lang::get('addon_not_installed').'</a>';
                }

                if($plugin->isActive()) {
                    $active = '<a href="'.$active_url.'" class="btn btn-xs dyn-online fa fa-check" title="'.lang::get('addon_actived').'"></a>';
                } else {
                    $active = '<a href="'.$active_url.'" class="btn btn-xs dyn-offline fa fa-times" title="'.lang::get('addon_not_actived').'"></a>';
                }

                $delete = '<a href="'.$delete_url.'" class="btn btn-xs btn-danger fa fa-trash-o delete"></a>';

                $table->addRow(['class'=>'addon-plugin'])
                    ->addCell('')
                    ->addCell('<a class="fa fa-question" href="'.$help_url.'"></a> '.$pluginName.' <small>'.$plugin->get('version').'</small>')
                    ->addCell('<span class="btn-group">'.$install.$active.$delete.'</span>');
            }

        }
			
	}
	
	} else {
	
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>3]);
		
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