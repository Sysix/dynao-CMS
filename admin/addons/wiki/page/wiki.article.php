<?php

$catId = type::super('catId', 'int', 0);
$subaction = type::super('subaction', 'string');
$id = type::super('id', 'int', 0);

if(ajax::is()) {
	
	$sort = type::post('array', 'array');
	
	$sql = sql::factory();
	$sql->setTable('wiki');
	foreach($sort as $s=>$id) {
		$sql->setWhere('id='.$id);
		$sql->addPost('sort', $s+1);
		$sql->update();	
	}
	
	ajax::addReturn(message::success(lang::get('save_sorting'), true));
	
}

if($action == 'delete' && dyn::get('user')->hasPerm('wiki[delete]')) {
	
	$sql = sql::factory();
	$sql->setTable('wiki');
	$sql->setWhere('id='.$id);
	$sql->delete();
		
	echo message::success(lang::get('article_deleted'));
		
}

if(in_array($action, ['add', 'edit']) && dyn::get('user')->hasPerm('wiki[edit]')) {
	
	$form = form::factory('wiki', 'id='.$id, 'index.php');
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName(lang::get('name'));
	$field->autofocus();
	
	$field = $form->addRawField('<select class="form-control" name="category">'.wikiUtils::getTreeStructure(0, 0,' &nbsp;', $form->get('category')).'</select>');
	$field->fieldName(lang::get('category'));
	
	$field = $form->addTextareaField('text', $form->get('text'));
	$field->fieldName(lang::get('text'));
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	if($form->isSubmit()) {
		
		$category = type::super('category', 'int');
		$form->addPost('category', $category);
		
	}
	
?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang::get('wiki_edit'); ?></h3>
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
	

	$table = table::factory(['class'=>['js-sort']]);
	$table->setSql('SELECT * FROM '.sql::table('wiki').' WHERE `category` = '.$catId.' ORDER BY sort ASC');
	$table->addRow()
	->addCell()
	->addCell(lang::get('name'))
	->addCell(lang::get('action'));
	
	$table->addCollsLayout('25,*, 110');
	
	$table->addSection('tbody');
	
	if($table->numSql()) {
		
		while($table->isNext()) {
				
			$media = new media($table->getSql());			
			
			if(dyn::get('user')->hasPerm('wiki[edit]')) {
				$edit = '<a href="'.url::backend('wiki', ['subpage'=>'article', 'action'=>'edit', 'id'=>$table->get('id')]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
			}
			
			if(dyn::get('user')->hasPerm('wiki[delete]')) {
				$delete = '<a href="'.url::backend('wiki', ['subpage'=>'article', 'action'=>'delete', 'id'=>$table->get('id')]).'" class="btn btn-sm btn-danger fa fa-trash-o"></a>';
			}
			
			$table->addRow(array('data-id'=>$table->get('id')))
			->addCell('<i class="fa fa-sort"></i>')
			->addCell($media->get('name'))
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
			$table->next();	
			
		}
	
	} else {
		
		$table->addRow()
		->addCell(lang::get('no_entries'), ['colspan'=>4]);
		
	}
	
	layout::addJsCode("$('#wiki-select-category').change(function() {
							$(this).closest('form').submit();  
						});");
	
	?>
	
	<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo lang::get('wiki'); ?></h3>
                    <?php
					if(dyn::get('user')->hasPerm('wiki[edit]')) {
					?>
					<div class="btn-group pull-right">
						<a href="<?php echo url::backend('wiki', ['subpage'=>'article', 'action'=>'add', 'id'=>$id]); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
					</div>
                    <?php
					}
					?>
					<div class="clearfix"></div>
                </div>
				<div class="panel-body">
					<form action="index.php" method="get">
						<input type="hidden" name="page" value="wiki" />
						<input type="hidden" name="subpage" value="article" />
						<select class="form-control" id="wiki-select-category" name="catId">
							<option value="0"><?php echo lang::get('no_category'); ?></option>
							<?php echo wikiUtils::getTreeStructure(0, 0,' &nbsp;', $catId); ?>
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