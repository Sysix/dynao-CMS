<?php

$catId = type::super('catId', 'int', 0);
$subaction = type::super('subaction', 'string');


if($action == 'delete' && dyn::get('user')->hasPerm('media[delete]')) {
	
	$values = [];
	for($i = 1; $i <= 10; $i++) {
		$values[] = '`media'.$i.'` = '.$id;
	}
	
	/*
	for($i = 1; $i <= 10; $i++) {
		$values[] = '`medialist'.$i.'` LIKE "%|'.$id.'|%"';
	}
	*/
	
	$sql = sql::factory();
	$sql->query('SELECT id FROM '.sql::table('structure_area').' WHERE '.implode(' OR ', $values))->result();
	if($sql->num()) {
		
		echo message::warning(lang::get('file_in_use'));
		
	} else {
		
		$sql = sql::factory();
		$sql->setTable('media');
		$sql->setWhere('id='.$id);
		$sql->select('filename');
		$sql->result();
	
		if(unlink(dir::media($sql->get('filename')))) {
			
			echo message::success(lang::get('file_deleted'));
			
		} else {
			
			echo message::warning(sprintf(lang::get('file_not_deleted'), dyn::get('hp_url'), $sql->get('filename')));
			
		}
		
		$sql->delete();
		
	}
		
}

if(in_array($action, ['add', 'edit']) && dyn::get('user')->hasPerm('media[edit]')) {
	
	$sql = sql::factory();
	$cats = $sql->num('SELECT id FROM '.sql::table('media_cat').' LIMIT 1');
	if(!$cats) {
		echo message::warning('Please add at first a Category');
		return;	
	}
	
	$form = form::factory('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName(lang::get('title'));
	$field->autofocus();
	
	$category = type::session('media_cat', 'int', $form->get('category'));
	type::addSession('media_cat', $category);
	
	$field = $form->addRawField('<select class="form-control" name="category">'.mediaUtils::getTreeStructure(0, 0,' &nbsp;', $category).'</select>');
	$field->fieldName(lang::get('category'));
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName(lang::get('select_file'));
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$file = type::files('file');
		if(!is_uploaded_file($file['tmp_name']) && !$form->isEditMode()) {
			
			$form->setErrorMessage(lang::get('please_load_file'));
			$form->setSave(false);
			
		} else {
			
			$form = mediaUtils::saveFile($file, $form);
			
		}
		
		$category = type::super('category', 'int');
		$form->addPost('category', $category);
		
	}
	
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang::get('media_edit'); ?></h3>
			</div>
			<div class="panel-body">
				<?php echo $form->show(); ?>
			</div>
		</div>
	</div>
</div>
<?php
	
}

if($action == '') {
	
	if(!$catId) {
		$catId = type::session('media_cat', 'int', $catId);		
	}
	
	type::addSession('media_cat', $catId);
	
	print_r($_SESSION);

	$table = table::factory();
	$table->setSql('SELECT * FROM '.sql::table('media').' WHERE `category` = '.$catId);
	$table->addRow()
	->addCell()
	->addCell(lang::get('title'))
	->addCell(lang::get('file_type'))
	->addCell(lang::get('action'));
	
	$table->addCollsLayout('50,*,100,250');
	
	$table->addSection('tbody');
	
	if($table->numSql()) {
		
		while($table->isNext()) {
				
			$media = new media($table->getSql());			
			
			if(dyn::get('user')->hasPerm('media[edit]')) {
				$edit = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';
			}
			
			if(dyn::get('user')->hasPerm('media[delete]')) {
				$delete = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
			}
			
			$table->addRow()
			->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
			->addCell($media->get('title'))
			->addCell($media->getExtension())
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
			
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>4]);
		
	}
	
	?>
	
	<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('media'); ?></h3>
                    <?php
					if(dyn::get('user')->hasPerm('media[edit]')) {
					?>
					<div class="btn-group pull-right">
						<a href="<?php echo url::backend('media', ['subpage'=>'files', 'action'=>'add', 'id'=>$id]); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
					</div>
                    <?php
					}
					?>
					<div class="clearfix"></div>
                </div>
				<div class="panel-body">
					<form action="index.php" method="get">
						<input type="hidden" name="page" value="media" />
						<input type="hidden" name="subpage" value="files" />
						<select class="form-control" id="media-select-category" name="catId">
							<option value="0"><?php echo lang::get('no_category'); ?></option>
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