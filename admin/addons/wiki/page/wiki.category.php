<?php

$pid = type::super('pid', 'int', 0);
$id = type::super('id', 'int', 0);

$while_id = $pid;

if(ajax::is()) {
	
	$sort = type::post('array', 'array');
	
	$sql = sql::factory();
	$sql->setTable('wiki_cat');
	foreach($sort as $s=>$id) {
		$sql->setWhere('id='.$id);
		$sql->addPost('sort', $s+1);
		$sql->update();	
	}
	
	ajax::addReturn(message::success(lang::get('save_sorting'), true));
	
}

while($while_id) {
		
	$sql = sql::factory();
	$sql->query('SELECT name, pid FROM '.sql::table('wiki_cat').' WHERE id='.$while_id)->result();
	
	if($pid != $while_id) {
		
		$breadcrumb[] = '<li><a href="'.url::backend('wiki', ['pid'=>$while_id, 'subpage'=>'category']).'">'.$sql->get('name').'</a></li>';
		
	} else {
		
		$breadcrumb[] = '<li class="active">'.$sql->get('name').'</li>';
		
	}
	
	$while_id = $sql->get('pid');
	
}

$breadcrumb[] = '<li><a href="'.url::backend('wiki', ['subpage'=>'category']).'">'.lang::get('wiki_all_cat').'</a></li>';

echo '<ul class="breadcrumb">'.implode('', array_reverse($breadcrumb)).'</ul>';

if($action == 'delete' && dyn::get('user')->hasPerm('wiki[delete]')) {
	
	$error = [];
	
	$sql = sql::factory();
	$sql->query('SELECT id FROM '.sql::table('wiki_cat').' WHERE `pid` = '.$id)->result();
	if($sql->num()) {
			$error[] = lang::get('wiki_article_exist');
	}
	
	$sql->query('SELECT id FROM '.sql::table('wiki').' WHERE `category` = '.$id)->result();
	if($sql->num()) {
			$error[] = lang::get('wiki_article_exist2');
	}
	
	if(count($error)) {
		
		echo message::danger(implode('<br />', $error));
		
	} else {
	
		$sql = sql::factory();		
		$sql->query('SELECT `sort`, `pid` FROM '.sql::table('wiki_cat').' WHERE id='.$id)->result();
		
		$delete = sql::factory();
		$delete->setTable('wiki_cat');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		sql::sortTable('wiki_cat', 0, '`pid` = '.$sql->get('pid'));
		
		echo message::success(lang::get('file_deleted'));
		
	}
		
}

if(in_array($action, ['save-add', 'save-edit']) && dyn::get('user')->hasPerm('wiki[edit]')) {
	
	$sql = sql::factory();
	$sql->setTable('wiki_cat');
	$sql->setWhere('id='.$id);
	$sql->getPosts([
		'name'=>'string',
		'sort'=>'int',
		'pid'=>'int'
	]);
	
	if($action == 'save-edit') {
		$sql->update();	
	} else {			
		$sql->save();
	}
	
	$sort = type::post('sort', 'int');
	$parent_id = type::post('pid', 'int');
	
	sql::sortTable('wiki_cat', $sort, '`pid` = '.$parent_id.' AND id != '.$id);
	
	
}

$table = table::factory(['class'=>['js-sort']]);

$colFirstWidth = ($action == 'edit' || $action == 'add') ? 50 : 25; 

$table->addCollsLayout($colFirstWidth.',*, 110');
	
$table->addRow()
->addCell()
->addCell(lang::get('category'))
->addCell(lang::get('action'));

$table->addSection('tbody');
	
$table->setSql('SELECT * FROM '.sql::table('wiki_cat').' WHERE pid = '.$pid.' ORDER BY sort ASC');
	
if(in_array($action, ['edit', 'add']) && dyn::get('user')->hasPerm('wiki[edit]')) {
		
	echo '<form method="post" action="index.php">';
		
	$inputHidden = formInput::factory('action', 'save-'.$action);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('page', 'wiki');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('subpage', 'category');
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$inputHidden = formInput::factory('pid', $pid);
	$inputHidden->addAttribute('type', 'hidden');
	echo $inputHidden->get();
	
	$buttonSubmit = formButton::factory('save', lang::get('category_save'));
	$buttonSubmit->addAttribute('type', 'submit');
	$buttonSubmit->addClass('btn-sm');
	$buttonSubmit->addClass('btn-default');
	
}

if($action == 'add' && dyn::get('user')->hasPerm('wiki[edit]')) {
	
	$inputName = formInput::factory('name', '');
	$inputName->addAttribute('type', 'text');
	$inputName->addClass('input-sm');
	$inputName->autofocus();
	
	$inputSort = formInput::factory('sort', $table->getSql()->num()+1);
	$inputSort->addAttribute('type', 'text');
	$inputSort->addClass('input-sm');

	$table->addRow()
	->addCell($inputSort->get())
	->addCell($inputName->get())
	->addCell($buttonSubmit->get());	
	
}

if($table->numSql()) {

	while($table->isNext()) {
		
		if($action == 'edit' && $table->get('id') == $id && dyn::get('user')->hasPerm('wiki[edit]')) {
				
			$inputName = formInput::factory('name', $table->get('name'));
			$inputName->addAttribute('type', 'text');
			$inputName->addClass('input-sm');
			$inputName->autofocus();
			
			$inputSort = formInput::factory('sort', $table->get('sort'));
			$inputSort->addAttribute('type', 'text');
			$inputSort->addClass('input-sm');
			
			$inputHidden = formInput::factory('id', $table->get('id'));
			$inputHidden->addAttribute('type', 'hidden');
			
			$table->addRow()
			->addCell($inputSort->get())
			->addCell($inputName->get())
			->addCell($inputHidden->get().$buttonSubmit->get());
			
		} else {
			
			$edit = '';
			$delete = '';
			
			if(dyn::get('user')->hasPerm('wiki[edit]')) {
				$edit = '<a href="'.url::backend('wiki', ['subpage'=>'category', 'action'=>'edit', 'id'=>$table->get('id'),'pid'=>$pid]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
			}
			
			if(dyn::get('user')->hasPerm('wiki[delete]')) {
				$delete = '<a href="'.url::backend('wiki', ['subpage'=>'category', 'action'=>'delete', 'id'=>$table->get('id'),'pid'=>$pid]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
			}
		
			$table->addRow(array('data-id'=>$table->get('id')))
			->addCell('<i class="fa fa-sort"></i>')
			->addCell('<a href="'.url::backend('wiki', ['subpage'=>'category', 'pid'=>$table->get('id')]).'">'.$table->get('name').'</a>')
			->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
			
		}
		
		$table->next();	
	}

} else {
	
	$table->addRow()
	->addCell(lang::get('no_categories'), ['colspan'=>3]);
	
}

?>
<div class="clearfix"></div>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo lang::get('wiki'); ?></h3>
                <?php
				if(dyn::get('user')->hasPerm('wiki[edit]')) {
				?>
				<div class="btn-group pull-right">
					<a href="<?php echo url::backend('wiki', ['subpage'=>'category', 'action'=>'add', 'pid'=>$pid]); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
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


if(in_array($action, ['edit', 'add']) && dyn::get('user')->hasPerm('wiki[edit]')) {
	echo '</form>';
}

?>