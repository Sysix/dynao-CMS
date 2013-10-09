<?php

if($action == 'add' || $action == 'edit') {
	
	$form = new form('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('Titel');
	
	$field = $form->addTextareaField('description', $form->get('description'));
	$field->fieldName('Beschreibung');
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName('Datei auswählen');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$file = type::files('file', '');
		if(is_uploaded_file($file['tmp_name'])) {
			
			$fileName = mediaUtils::fixFileName($file['name']);
			$fileDir = dir::media($fileName);
			$extension = substr(strrchr($fileName, '.'), 1); // z.B. jpg
			
			// Wenn die Datei eine "verbotene" Datei ist
			if(!in_array($extension, dyn::get('badExtensions'))) {
			
				if($form->isEditMode()) {
					$media = new media($id);
				}
				
				// Wenn Datei nicht Existiert
				// Oder man möchte sie überspeichern
				if(!file_exists($fileDir) || ($form->isEditMode() && $media->get('filename') == $fileName)) {
				
					if(move_uploaded_file($file['tmp_name'], $fileDir)) {
						
						$form->addPost('filename', $fileName);
						$form->addPost('size', filesize($fileDir));
						
					} else {
						
						$form->setSave(false);
						echo message::warning($file['name'].' konnte nicht gespeichert werden.<br />Die Datei konnte nicht ins Verzeichnis verschoben werden.');
						
					}
				
				} else {
					
					$form->setSave(false);
					echo message::warning($file['name'].' konnte nicht gespeichert werden.<br />Der Datei existiert bereits');
					
				}
				
			} else {
				
				$form->setSave(false);
				echo message::warning($file['name'].' konnte nicht gespeichert werden.<br />Die Dateiendung ist nicht erlaubt');
				
			}
			
		}
		
		
	}
	
	echo $form->show();	
	
}

if($action == '') {
	
	$table = new table();
	$table->setSql('SELECT id FROM '.sql::table('media'));
	$table->addRow()
	->addCell()
	->addCell('Titel')
	->addCell('Endung')
	->addCell('Aktion');
	$table->addSection('tbody');
	while($table->isNext()) {
			
		$media = new media($table->get('id'));
		
		$edit = '<a href="'.url::backend('media', array('action'=>'edit', 'id'=>$table->get('id'))).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
		$delete = '<a href="'.url::backend('media', array('action'=>'delete', 'id'=>$table->get('id'))).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
			
		$table->addRow()
		->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
		->addCell($media->get('title'))
		->addCell($media->getExtension())
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}
	
	echo $table->show();
	
}

?>