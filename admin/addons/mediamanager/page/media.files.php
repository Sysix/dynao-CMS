<?php

$catId = type::super('catId', 'int', 0);
$subaction = type::super('subaction', 'string');

if($action == 'add' || $action == 'edit') {
	
	$form = form::factory('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('Titel');	
	
	$field = $form->addRawField('<select class="form-control" name="category">'.mediaUtils::getTreeStructure(0, 0,' &nbsp;', $form->get('category')).'</select>');
	$field->fieldName('Kategorie');
		
	$form = metainfos::getMetaInfos($form, 'media');
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName('Datei auswählen');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$file = type::files('file');
		$form = mediaUtils::saveFile($file, $form);
		
		$category = type::super('category', 'int');
		$form->addPost('category', $category);
		
	}
	
	echo $form->show();	
	
}

if($action == '') {
	
	echo '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'add', 'id'=>$id]).'" class="btn btn-sm btn-primary pull-right">'.lang::get('add').'</a>';
?>
<div class="clearfix"></div>
<form action="index.php" method="get">
	<input type="hidden" name="page" value="media" />
	<input type="hidden" name="subpage" value="files" />
	<select class="form-control" id="media-select-category" name="catId">
	<?php echo mediaUtils::getTreeStructure(0, 0,' &nbsp;', $catId); ?>
	</select>
</form>
<div class="clearfix"></div>
<?php
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
			
			$edit = '<button data-id="'.$table->get('id').'" class="btn btn-sm btn-warning dyn-media-select>Auswählen</button>';
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
	
	echo $table->show();
	
}

?>