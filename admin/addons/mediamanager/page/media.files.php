<?php

$catId = type::super('catId', 'int', 0);
$subaction = type::super('subaction', 'string');


if($action == 'delete' && dyn::get('user')->hasPerm('media[delete]')) {
	
	echo mediaUtils::deleteFile($id);
	
	$action = '';
	
}

if($action == 'deleteFiles') {
	
	$files = type::post('file', '', []);
	
	foreach($files as $id) {
		echo mediaUtils::deleteFile($id);
	}
	
	$action = '';
	
}

if(in_array($action, ['add', 'edit']) && dyn::get('user')->hasPerm('media[edit]')) {
	
	$form = form::factory('media', 'id='.$id, 'index.php');
	$form->addFormAttribute('enctype', 'multipart/form-data');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName(lang::get('title'));
	$field->autofocus();
	
	$category = type::session('media_cat', 'int', $form->get('category'));	
	
	$field = $form->addRawField('<select class="form-control" name="category">'.mediaUtils::getTreeStructure(0, 0,' &nbsp;', $category).'</select>');
	$field->fieldName(lang::get('category'));
	
	$field = $form->addRawField('<input type="file" name="file" />');
	$field->fieldName(lang::get('select_file'));
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		type::addSession('media_cat', $form->get('category'));
		
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
	
	$sql = sql::factory();
	$cats = $sql->num('SELECT id FROM '.sql::table('media_cat').' LIMIT 1');
	if(!$cats) {
		echo message::warning('Please add at first a Category');
		return;	
	}
	
	if(!$catId) {
		$catId = type::session('media_cat', 'int', $catId);		
	}
	
	if(!$catId) {
		$sql = sql::factory();
		$sql->query('SELECT id FROM '.sql::table('media_cat').' ORDER BY id LIMIT 1')->result();
		$catId = $sql->get('id');
	}
	
	type::addSession('media_cat', $catId);

	$table = table::factory();
	$table->setSql('SELECT * FROM '.sql::table('media').' WHERE `category` = '.$catId);
	$table->addRow()
	->addCell()
	->addCell()
	->addCell(lang::get('title'))
	->addCell(lang::get('file_type'))
	->addCell(lang::get('action'));
	
	$table->addCollsLayout('20, 50,*,100,110');
	
	$table->addSection('tbody');
	
	if($table->numSql()) {
		
		while($table->isNext()) {
				
			$media = new media($table->getSql());			
			
			if(dyn::get('user')->hasPerm('media[edit]')) {
				$edit = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
			}
			
			if(dyn::get('user')->hasPerm('media[delete]')) {
				$delete = '<a href="'.url::backend('media', ['subpage'=>'files', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
			}
			
			$checkbox = formCheckbox::factory('file[]', 0);
			$checkbox->add($media->get('id'), '');
			
			$table->addRow()
			->addCell($checkbox->get())
			->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
			->addCell($media->get('title'))
			->addCell($media->getExtension())
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
			
		}
		
		$button = formButton::factory('submit', lang::get('delete'));
		$button->addClass('btn');
		$button->addClass('btn-sm');
		$button->addClass('btn-default');

		$table->addRow(['class'=>'active'])
		->addCell('', ['colspan'=>4])
		->addCell($button->get());
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>5]);
		
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
							<?php echo mediaUtils::getTreeStructure(0, 0,' &nbsp;', $catId); ?>
						</select>
					</form>
				</div>
                <form action="index.php" method="post">
                	<input type="hidden" name="page" value="media" />
                    <input type="hidden" name="subpage" value="files" />
                    <input type="hidden" name="action" value="deleteFiles" />
                	<?php echo $table->show(); ?>
                </form>
            </div>
        </div>
    </div>
    <?php
	
}

?>