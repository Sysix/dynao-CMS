<?php

if($structure_id) {

	$sort = type::super('sort', 'int');
	
	// Bugfix, das neu erstelle Blöcke nicht einzgezeigt werden
	if(!is_null(type::post('save-back')) || !is_null(type::post('save'))) {
		pageAreaAction::saveBlock();
		echo message::success(lang::get('structure_content_save'));
	}
	
	if($action == 'online') {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure_area');
		$sql->setWhere('id='.$id);
		$sql->addPost('online', $online);
		$sql->update();
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if(ajax::is()) {
		
		$sort = type::post('array', 'array');
		$sql = sql::factory();
		$sql->setTable('structure_area');
		foreach($sort as $s=>$s_id) {
			$sql->setWhere('id='.$s_id);
			$sql->addPost('sort', $s+1);
			$sql->update();
		}
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		
	}
	
	if($action == 'delete') {
	
		$structure_id = pageAreaAction::delete($id);
		echo message::success(lang::get('structure_content_delete'));
		
	}
	
	
	?>
	<ul id="structure-content">
	<?php
	
	$sql = sql::factory();
	$sql->result('SELECT s.*, m.name, m.output, m.input
	FROM '.sql::table('structure_area').' as s
	LEFT JOIN 
		'.sql::table('module').' as m
			ON m.id = s.modul
	WHERE structure_id = '.$structure_id.'
	ORDER BY `sort`');
	$i = 1;
	while($sql->isNext()) {
		
		$sqlId = ($action == 'add') ? 0 : $sql->get('id');
		$module = new pageArea($sql);
		if(in_array($action, ['add', 'edit'])) {
			
			if($action == 'add') {
				$module->setNew(true);	
			}
			
			$form = pageAreaHtml::formBlock($module);
		
		}
	?>
			<li data-id="<?php echo $sql->get('id'); ?>">
	<?php
		// Wenn Aktion == add
		// UND Wenn Sortierung von SQL gleich der $_GET['sort']
		// UND Wenn Formular noch nicht abgeschickt worden
		if($action == 'add' && $sort == $i && !$form->isSubmit()) {
			
			echo pageAreaHtml::formOut($form);
	
		} else {
			
			echo pageAreaHtml::selectBlock($module->getStructureId());
			
		}
		
		// Wenn Aktion == edit
		// UND Wenn ID von SQL gleich der $_GET['id']
		// UND
		// Wenn Formular noch nicht abgeschickt worden
		// ODER Abgeschickt worden ist und ein Übernehmen geklickt worden ist
		if($action == 'edit' && $id == $sql->get('id') && 
		(!$form->isSubmit() || ($form->isSubmit() && !is_null(type::post('save-back'))))
		) {
	
			echo pageAreaHtml::formOut($form);
	
		} else {
			
			if($sql->get('online')) {
				$statusText = 'ON';
				$status = lang::get('online');
			} else {
				$statusText = 'OFF';
				$status =  lang::get('offline');
			}
			
			?>
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo $sql->get('name'); ?></h3>
				<div class="pull-right btn-group">
					<a href="<?php echo url::backend('structure', ['action'=>'online', 'id'=>$sql->get('id')]); ?>" class="btn btn-sm structure-<?php echo $status; ?>"><?php echo $statusText; ?></a>
					<a href="<?php echo url::backend('structure', ['action'=>'edit', 'id'=>$sql->get('id')]); ?>" class="btn btn-default btn-sm fa fa-edit"></a>
					<a href="<?php echo url::backend('structure', ['action'=>'delete', 'id'=>$sql->get('id')]); ?>" class="btn btn-danger btn-sm fa fa-trash-o"></a>
				</div>
				<div class="clearfix"></div>
			  </div>
			  <div class="panel-body">
				<?php echo $module->OutputFilter($sql->get('output'), $sql); ?>
			  </div>
			</div>
			<?php
		}
		?>
		</li>
		<?php
		$sql->next();
		$i++;
		
	}
	?>	
	</ul>
	<?php
	$module = new module();
	if((!$sql->num() || ($i == $sort)) && $action == 'add') {
		
		$form_id = type::super('modul', 'int');
		
		$form = pageAreaHtml::formBlock(new pageArea(0));
		echo pageAreaHtml::formOut($form);
		
	} else {
		
		echo pageAreaHtml::selectBlock($structure_id,  $sql->num()+1);
		
	}

//Wenn structure_id leer
} else {

	#echo '<select class="form-control">';
	#echo page::getTreeStructure();
	#echo '</select>';
	
	if(ajax::is()) {
		
		$sort = type::post('array', 'array');
		
		$sql = sql::factory();
		$sql->setTable('structure');
		foreach($sort as $s=>$id) {
			$sql->setWhere('id='.$id);
			$sql->addPost('sort', $s+1);
			$sql->update();	
		}
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
	}
	
	if($action == 'delete') {
		
		$orginal_id = $id;
		
		while($id) {
		
			$sql = sql::factory();
			$sql->query('SELECT id FROM '.sql::table('structure').' WHERE `parent_id` = '.$id)->result();
			if($sql->num()) {
				
				$id = $sql->get('id');
				
				$delete = sql::factory();
				$delete->setTable('structure');
				$delete->setWhere('id='.$id);
				$delete->delete();			
				
			} else {
				$id = false;	
			}
			
		}
		
		
		$sql = sql::factory();		
		$sql->query('SELECT `sort`, `parent_id` FROM '.sql::table('structure').' WHERE id='.$orginal_id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure');
		$delete->setWhere('id='.$orginal_id);
		$delete->delete();
		
		sql::sortTable('structure', 0, '`parent_id` = '.$sql->get('parent_id'));
		
		echo message::success(lang::get('structure_delete'));
			
	}
	
	if($action == 'online') {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure').' WHERE id='.$id)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure');
		$sql->setWhere('id='.$id);
		$sql->addPost('online', $online);
		$sql->update();
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if(in_array($action, ['save-add', 'save-edit'])) {
		
		$sql = sql::factory();
		$sql->setTable('structure');
		$sql->setWhere('id='.$id);
		$sql->getPosts([
			'name'=>'string',
			'sort'=>'int',
		]);
		
		if($action == 'save-edit') {
			$sql->update();	
		} else {
			$sql->save();
		}
		
		$sort = type::post('sort', 'int');
		$parent_id = type::post('parent_id', 'int');
		
		//sql::sortTable('structure', $sort, '`parent_id` = '.$parent_id.' AND id != '.$id);
		
	}
		
	if(in_array($action, ['edit', 'add'])) {
			
		echo '<form method="post" action="index.php">';
			
		$inputHidden = formInput::factory('action', 'save-'.$action);
		$inputHidden->addAttribute('type', 'hidden');
		echo $inputHidden->get();
		
		$inputHidden = formInput::factory('page', 'structure');
		$inputHidden->addAttribute('type', 'hidden');
		echo $inputHidden->get();
		
	}
	
	//echo $table->show();
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo lang::get('pages'); ?></h3>
					<div class="btn-group pull-right">
						<a class="btn btn-sm btn-default" href="<?php echo url::backend('structure', ['action'=>'add', 'parent_id'=>$parent_id]); ?>"><?php echo lang::get('add'); ?></a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
				<?php
					echo page::getTreeStructurePage();
				?>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	
	if(in_array($action, ['edit', 'add'])) {
		echo '</form>';
	}
	
	layout::addJsCode("
	$('#structure-tree li').prepend('<div class=\"dropzone\"></div>');

    $('#structure-tree .handle, #structure-tree .dropzone').droppable({
        accept: '#structure-tree li',
        tolerance: 'pointer',
        drop: function(e, ui) {
            var li = $(this).parent();
            var child = !$(this).hasClass('dropzone');
            if (child && li.children('ul').length == 0) {
                li.append('<ul/>');
            }
            if (child) {
                li.addClass('sm2_liOpen').removeClass('sm2_liClosed').children('ul').append(ui.draggable);
            }
            else {
                li.before(ui.draggable);
            }
			$('#structure-tree li.sm2_liOpen').not(':has(li:not(.ui-draggable-dragging))').removeClass('sm2_liOpen');
            li.find('.handle,.dropzone').css({ backgroundColor: '', borderColor: '' });
        },
        over: function() {
            $(this).filter('.handle, .dropzone').css({ backgroundColor: '#ccc' });
        },
        out: function() {
            $(this).filter('.handle, .dropzone').css({ backgroundColor: '' });
        }
    });
	
    $('#structure-tree li').draggable({
        handle: ' > .handle',
        opacity: .8,
        addClasses: false,
        helper: 'clone',
        zIndex: 100,
    });
	");
}
?>