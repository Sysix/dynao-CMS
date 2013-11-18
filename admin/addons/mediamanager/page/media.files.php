<?php

$catId = type::super('catId', 'int', 0);
$subaction = type::super('subaction', 'string');


if($action == 'delete') {
	
	$sql = sql::factory();
	$sql->setTable('media');
	$sql->setWhere('id='.$id);
	$sql->select('filename');
	$sql->result();
	
	if(unlink(dir::media($sql->get('filename')))) {
		
		echo message::success('Datei erfolgreich gelöscht');
		
	} else {
		
		echo message::warning('Die Datei '.dyn::get('hp_url').'media/'.$sql->get('filename').' konnte nicht gelöscht werden');
		
	}
	
	$sql->delete();
		
}

if($action == 'add' || $action == 'edit') {
	
	$form = form::factory('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('Titel');	
	
	$field = $form->addRawField('<select class="form-control" name="category">'.mediaUtils::getTreeStructure(0, 0,' &nbsp;', $form->get('category')).'</select>');
	$field->fieldName('Kategorie');
	
	if(addonConfig::isActive('metainfos')) {
		$form = metainfos::getMetaInfos($form, 'media');
	}
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName('Datei auswählen');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$file = type::files('file');
		if(!is_uploaded_file($file['tmp_name']) && !$form->isEditMode()) {
			
			$form->setErrorMessage('Bitte Laden Sie eine Datei hoch');
			$form->setSave(false);
			
		} else {
			
			$form = mediaUtils::saveFile($file, $form);
			
		}
		
		$category = type::super('category', 'int');
		$form->addPost('category', $category);
		
	}
	
	echo $form->show();	
	
}

if($action == '') {
	
	echo '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'add', 'id'=>$id]).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';

	$table = table::factory();
	$table->setSql('SELECT * FROM '.sql::table('media').' WHERE `category` = '.$catId);
	$table->addRow()
	->addCell()
	->addCell('Titel')
	->addCell('Endung')
	->addCell('Aktion');
	$table->addSection('tbody');
	while($table->isNext()) {
			
		$media = new media($table->getSql());
		
		if($subaction == 'popup') {
			
			$edit = '<button data-id="'.$table->get('id').'" data-name="'.$table->get('filename').'" class="btn btn-sm btn-warning dyn-media-select">Auswählen</button>';
			$delete = '';
			
		} else {
		
			$edit = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
			$delete = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
		
		}
		
		$table->addRow()
		->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
		->addCell($media->get('title'))
		->addCell($media->getExtension())
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	?>
	
	<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Media</h3>
                </div>
				<div class="panel-body">
					<form action="index.php" method="get">
						<input type="hidden" name="page" value="media" />
						<input type="hidden" name="subpage" value="files" />
						<select class="form-control" id="media-select-category" name="catId">
							<option value="0">Keine Kategorie</option>
							<?php echo mediaUtils::getTreeStructure(0, 0,' &nbsp;', $catId); ?>
						</select>
					</form>
				</div>
                <?php echo $table->show(); ?>
            </div>
        </div>
    </div>
    <?php
	
}

?>