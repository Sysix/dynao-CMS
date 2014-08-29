<?php
if(!dyn::get('user')->hasPerm('page[module]')) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

if($action == 'delete') {
	
	$sql = sql::factory();
	$num = $sql->num('SELECT id FROM '.sql::table('structure_area').' WHERE modul = '.$id);
	
	if($num) {
		
		echo message::danger(lang::get('module_in_use'));
		
	} else {
		
		$sql->setTable('module');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		echo message::success(lang::get('module_deleted'));
	
	}
	$action = '';
	
}
	
if($action == 'export') {

	module::sendExport($id);
	echo message::success(lang::get('module_export'));
	$action = '';
	
}

if($action == 'import') {
	
	$content = (array)apiserver::getModuleFile();
	
	if($id && isset($content[$id])) {
		
		$sql = sql::factory();
        $sql->setTable('module');
		$sql->addPost('name', $content[$id]['name']);
		$sql->addPost('input', $content[$id]['install']['input']);
		$sql->addPost('output', $content[$id]['install']['output']);
		$sql->addPost('blocks', $content[$id]['install']['blocks']);
		$sql->save();
		
		echo message::success(sprintf(lang::get('module_install_success'), $content[$id]['name']), true);
		
	}
	
	if(ajax::is()) {
	
		$file = type::files('file');
		$fileDir = dir::generated('modul_temp.json');
		
		move_uploaded_file($file['tmp_name'], $fileDir);
		
		$string = file_get_contents($fileDir);
		$content = json_decode($string, true);
		
		$sql = sql::factory();
        $sql->setTable('module');
		$sql->addPost('name', $content['name']);
		$sql->addPost('input', $content['install']['input']);
		$sql->addPost('output', $content['install']['output']);
		$sql->addPost('blocks', $content['install']['blocks']);
		$sql->save();
		
		unlink($fileDir);
		
		ajax::addReturn(message::success(sprintf(lang::get('module_install_success'), $content['name']), true));

	}
	
	$table = table::factory();
	$table->addCollsLayout('30, 200, *,110');
	
	$table->addRow()
	->addCell('ID')
	->addCell(lang::get('name'))
	->addCell(lang::get('description'))
	->addCell();

	$table->addSection('tbody');
	
	foreach($content as $id=>$modul) {
		
		$import = '<a class="btn btn-sm btn-default" href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'import', 'id'=>$id]).'">'.lang::get('import').'</a>';
		
		$table->addRow()
		->addCell($id)
		->addCell($modul['name'])
		->addCell($modul['info'])
		->addCell('<span class="btn-group">'.$import.'</span>');
			
	}

	if(!count($content)) {

		$table->addRow()->addCell('Keine Module verfügbar', ['colspan'=>4]);

	}
	
	?>
	<div class="row">
		<div class="col-lg-12">
        	<div id="ajax-content"></div>
        	<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo lang::get('import'); ?></h3>
					<div class="btn-group pull-right">
						<a class="btn btn-sm btn-warning" href="<?= url::backend('structure', ['subpage'=>'module']) ?>"><?php echo lang::get('back'); ?></a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
                	<div id="dropzone" class="dropzone"></div>
				</div>
			</div>
        
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo lang::get('module_more'); ?></h3>
				</div>
				<div class="panel-body">
				<?php echo $table->show(); ?>
				</div>
			</div>
            
		</div>
	</div>
	<?php
}

if($action == 'add' || $action == 'edit') {

	$form = form::factory('module', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName(lang::get('name'));
	$field->autofocus();
	
	$field = $form->addTextareaField('input', $form->get('input'));
	$field->fieldName(lang::get('input'));
	
	$field = $form->addTextareaField('output', $form->get('output'));
	$field->fieldName(lang::get('output'));
	
	$field = $form->addCheckboxField('blocks', $form->get('blocks'));
	$field->fieldName(lang::get('only_blocks'));
	$field->add(1, '');
	
	if($action == 'edit') {

		$form->addHiddenField('id', $id);
        $button = '<a class="btn btn-sm btn-warning" href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'export', 'id'=>$id]).'">'.lang::get('export').'</a>';
        $title = '"'.$form->get('name').'" '.lang::get('edit');

	} else {

        $button = '<a class="btn btn-sm btn-warning" href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'import']).'">'.lang::get('import').'</a>';
        $title = lang::get('module_add');

    }
	
	if($form->isSubmit()) {
		pageCache::clearAll();
	}

    $back = '<a class="btn btn-sm btn-default" href="'.url::backend('structure', ['subpage'=>'module']).'">'.lang::get('back').'</a>';
	
	?>
    <div class="row"><?= bootstrap::panel($title, [$button, $back], $form->show()) ?></div>
<?php
	
}

if($action == '') {
	
	if(ajax::is()) {
	
		$sort = type::post('array', 'array');
		
		$sql = sql::factory();
		$sql->setTable('module');
		foreach($sort as $s=>$id) {
			$sql->setWhere('id='.$id);
			$sql->addPost('sort', $s+1);
			$sql->update();	
		}
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
	}

	$table = table::factory(['class'=>['js-sort']]);
	
	$table->addCollsLayout('20,*,110');
	
	$table->addRow()
	->addCell()
	->addCell(lang::get('name'))
	->addCell(lang::get('action'));
	
	$table->addSection('tbody');

	$table->setSql('SELECT * FROM '.sql::table('module').' ORDER BY sort ');
	
	if($table->numSql()) {
	
		while($table->isNext()) {
		
			$id = $table->get('id');
			
			$edit = '<a href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'edit', 'id'=>$id]).'" class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
			$delete = '<a href="'.url::backend('structure', ['subpage'=>'module','action'=>'delete', 'id'=>$id]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';

			$table->addRow(['data-id'=>$id])
			->addCell('<i class="fa fa-sort"></i>')
			->addCell($table->get('name'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>3]);
		
	}

    $button = [];

    if(dyn::get('user')->hasPerm('page[edit]')) {
        $button = [
            '<a class="btn btn-sm btn-warning" href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'import']).'">'.lang::get('import').'</a>',
            '<a class="btn btn-sm btn-warning" href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'add']).'">'.lang::get('add').'</a>'
        ];
    }

	?>
    <div class="row"><?= bootstrap::panel(lang::get('modules'), $button, $table->show(), ['table'=>true]) ?></div>
	<?php

}

?>