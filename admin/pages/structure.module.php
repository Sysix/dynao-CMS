<?php
if(!dyn::get('user')->hasPerm('page[module]')) {
	echo message::danger(lang::get('access_denied'));
	return;	
}

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

if($action == 'delete') {
	
	$sql = sql::factory();
	$num = $sql->num('SELECT id FROM '.sql::table('structure_area').' WHERE modul = '.$id);
	$num2 = $sql->num('SELECT id FROM '.sql::table('blocks').' WHERE modul = '.$id);
	
	if($num || $num2) {
		
		echo message::danger(lang::get('module_in_use'));
		
	} else {
		
		$sql->setTable('module');
		$sql->setWhere('id='.$id);
		$sql->delete();
		
		echo message::success(lang::get('module_deleted'));
	
	}
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
		
		echo message::success('Modul '.$content[$id]['name'].' wurde erfolgreich installiert', true);
		$action = '';
		
	} else {
	
		$table = table::factory();
		
		$table->addRow()
		->addCell('Id')
		->addCell('')
		->addCell('Downloads')
		->addCell('Aktionen');

		foreach($content as $id=>$modul) {
			
			$code = '<a href="'.$modul['link'].'" class="btn btn-sm btn-success">Code anzeigen</a>';
			$import = '<a href="'.url::backend('structure', ['subpage'=>'module', 'action'=>'import', 'id'=>$id]).'">Importieren</a>';
			
			$table->addRow()
			->addCell($id)
			->addCell('<h3>'.$modul['name'].'</h3><br />'.$modul['info'])
			->addCell($modul['downloads'])
			->addCell('<span class="btn-group">'.$code.$import.'</span>');
				
		}

        if(!count($content)) {

            $table->addRow()->addCell('Keine Addon\'s verfügbar', ['colspan'=>4]);

        }
		
		echo $table->show();
	
	}
		
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
	}
	
	if($form->isSubmit()) {
		pageCache::clearAll();
	}
	
	?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
                <h3 class="panel-title pull-left"><?php echo ($action == 'add') ? lang::get('module_add') : '"'.$form->get('name').'" '.lang::get('edit'); ?></h3>
                <div class="btn-group pull-right">
					<a class="btn btn-sm btn-warning" href="<?= url::backend('structure', ['subpage'=>'module', 'action'=>'import']) ?>"><?= lang::get('import') ?></a>
                    <a class="btn btn-sm btn-default" href="<?= url::backend('structure', ['subpage'=>'module']) ?>"><?= lang::get('back'); ?></a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
			<?php echo $form->show();?>
            </div>
		</div>
	</div>
</div>

<?php
	
}

if($action == '') {

	$table = table::factory(['class'=>['js-sort']]);
	
	$table->addCollsLayout('20,*,110');
	
	$table->addRow()
	->addCell()
	->addCell('Name')
	->addCell('Aktion');
	
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
		->addCell(lang::get('no_entries'), ['colspan'=>2]);
		
	}

	?>
    
    <div class="row">
        <div class="col-lg-12">
        	<div id="ajax-content"></div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('modules'); ?></h3>
                    <?php
					if(dyn::get('user')->hasPerm('page[edit]')) { 
					?>
                    <div class="btn-group pull-right">
						<a class="btn btn-sm btn-warning" href="<?= url::backend('structure', ['subpage'=>'module', 'action'=>'import']) ?>"><?= lang::get('import') ?></a>
                        <a class="btn btn-sm btn-default" href="<?= url::backend('structure', ['subpage'=>'module', 'action'=>'add']) ?>"><?= lang::get('add'); ?></a>
                    </div>
                    <?php
					}
					?>
                    <div class="clearfix"></div>
                </div>
                <?php echo $table->show(); ?>
            </div>
        </div>
    </div>

	<?php

}

?>
