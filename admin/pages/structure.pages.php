<?php

if(!is_null($structure_id) && dyn::get('user')->hasPerm('page[content]')) {
	
	$sort = type::super('sort', 'int');
	
	// Bugfix, das neu erstelle Blöcke nicht einzgezeigt werden
	if(!is_null(type::post('save-back')) || !is_null(type::post('save'))) {
		pageAreaAction::saveBlock();
		pageCache::generateArticle($structure_id);
		echo message::success(lang::get('structure_content_save'), true);
	}
	
	if($action == 'online') {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure_area');
		$sql->setWhere('id='.$id);
		$sql->addPost('online', $online);
		$sql->update();
		
		pageCache::generateArticle($structure_id);
		
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
		
		pageCache::generateArticle($structure_id);
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		
	}
	
	if($action == 'delete') {
	
		$id = pageAreaAction::delete($id);
		pageCache::deleteFile($id);
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
				$class = 'online';
			} else {
				$statusText = 'OFF';
				$status =  lang::get('offline');
				$class = 'offline';
			}
			
			?>
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title pull-left"><?php echo $sql->get('name'); ?></h3>
				<div class="pull-right btn-group">
					<a href="<?php echo url::backend('structure', ['subpage'=>'pages', 'structure_id'=>$structure_id, 'action'=>'online', 'id'=>$sql->get('id')]); ?>" class="btn btn-sm dyn-<?php echo $class; ?>"><?php echo $statusText; ?></a>
					<a href="<?php echo url::backend('structure', ['subpage'=>'pages', 'structure_id'=>$structure_id, 'action'=>'edit', 'id'=>$sql->get('id')]); ?>" class="btn btn-default btn-sm fa fa-edit"></a>
					<a href="<?php echo url::backend('structure', ['subpage'=>'pages', 'structure_id'=>$structure_id, 'action'=>'delete', 'id'=>$sql->get('id')]); ?>" class="btn btn-danger btn-sm fa fa-trash-o delete"></a>
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
	if((!$sql->num() || ($i == $sort)) && $action == 'add') {
		
		$form_id = type::super('modul', 'int');
		
		$form = pageAreaHtml::formBlock(new pageArea(0));
		echo pageAreaHtml::formOut($form);
		
	} else {
		
		echo pageAreaHtml::selectBlock($structure_id,  $sql->num()+1);
		
	}

//Wenn action
} else {
	
	if(ajax::is() && dyn::get('user')->hasPerm('page[edit]')) {
		$post = type::super('array');
		$sort = json_decode($post, true);
		function sortStructure($sort, $pid = 0) {
			
			$sql = sql::factory();
			$sql->setTable('structure');
			$return = '';
			$i = 1;
			foreach($sort as $name=>$value) {
				
				$sql->addPost('sort', $i);
				$sql->addPost('parent_id', $pid);
				$sql->setWhere('id='.$value['id']);
				$sql->update();				
				
				if(isset($value['children'])) {
					
					$return .= sortStructure($value['children'], $value['id']);	
				}
				
				$i++;
			}
			
			return $return;
		}
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		//sortStructure($sort);
		
	}
	
	if(in_array($action, ['edit', 'add']) && dyn::get('user')->hasPerm('page[edit]')) {
	
		$form = form::factory('structure', 'id='.$id, 'index.php');
		
		$field = $form->addTextField('name', $form->get('name'));
		$field->fieldName(lang::get('name'));
		$field->autofocus();
		
		$template = template::factory(dyn::get('template'));
		
		$field = $form->addElement('template', $template->getTemplates('template', $form->get('templates')));
		$field->fieldName('Template');
		
		$field = $form->addRadioField('online', $form->get('online'));
		$field->fieldName(lang::get('status'));
		$field->add(1, lang::get('online'));
		$field->add(0, lang::get('offline'));
		
		if($action == 'edit') {
			$form->addHiddenField('id', $id);
		}
		
?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo $form->get('name'); ?></h3>
                    <div class="btn-group pull-right">
						<a class="btn btn-sm btn-default" href="<?php echo url::backend('structure'); ?>"><?php echo lang::get('back'); ?></a>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
				<?php
					echo $form->show();
				?>
				</div>
			</div>
		</div>
	</div>
<?php

	} 
	
	if($action == 'delete'  && dyn::get('user')->hasPerm('page[delete]')) {
		
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
	
	if($action == 'online' && dyn::get('user')->hasPerm('page[edit]')) {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure').' WHERE id='.$id)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure');
		$sql->setWhere('id='.$id);
		$sql->addPost('online', $online);
		$sql->update();
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if($action == '') {
	
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title pull-left"><?php echo lang::get('pages'); ?></h3>
                    <?php
					if(dyn::get('user')->hasPerm('page[edit]')) {
					?>
					<div class="btn-group pull-right">
						<a class="btn btn-sm btn-default" href="<?php echo url::backend('structure', ['subpage'=>'pages', 'action'=>'add']); ?>"><?php echo lang::get('add'); ?></a>
					</div>
                    <?php
					}
					?>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
				<?php
					echo pageMisc::getTreeStructurePage();
				?>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	
		if(dyn::get('user')->hasPerm('page[edit]')) {
			
			layout::addJs("layout/js/structureSort.js");
		}
	
	}

}
?>