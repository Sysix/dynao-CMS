<?php

if(!is_null($structure_id) && dyn::get('user')->hasPerm('page[content]')) {
	
	$sort = type::super('sort', 'int');
	
	// Bugfix, das neu erstelle Blöcke nicht einzgezeigt werden
	if(!is_null(type::post('save-back')) || !is_null(type::post('save'))) {
		pageAreaAction::saveBlock();
		page::generateArticle($structure_id);
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
		
		page::generateArticle($structure_id);
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if(ajax::is()) {
		
		$sort = type::post('array', 'array');
		$sql = sql::factory();
		$sql->setTable('structure_area');
		foreach($sort as $s=>$s_id) {
			$sql->setWhere('id='.$s_id.' AND slot = 1');
			$sql->addPost('sort', $s+1);
			$sql->update();
		}
		
<<<<<<< HEAD
		page::generateArticle($structure_id);
=======
		$field = $form->addRawField('<select name="modul" class="form-control">'.pageAreaHtml::moduleList($form->get('modul')).'</select>');
		$field->fieldName(lang::get('module'));
>>>>>>> sortStructure css+js update, slots bugfix
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		
	}
	
	if($action == 'delete') {
	
		$id = pageAreaAction::delete($id);
		echo message::success(lang::get('structure_content_delete'));
		
	}
	
	$slotSql = sql::factory();
	$slotSql->result('SELECT name FROM '.sql::table('slots').' WHERE id = '.$structure_id);
	
	?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo $slotSql->get('name'); ?></h3>
                    <div class="pull-right">
                    	<a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'action'=>'edit', 'id'=>$structure_id]); ?>" class="btn btn-sm btn-warning"><?php echo lang::get('edit'); ?></a>
						<a href="<?php echo url::backend('structure', ['subpage'=>'slots']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('back'); ?></a>
					</div>
					<div class="clearfix"></div>
                </div>
            	<div class="panel-body">
    
                    <div id="ajax-content"></div>
                    <ul id="structure-content">
                    <?php
                    
                    $sql = sql::factory();
                    $sql->result('SELECT s.*, m.name, m.output, m.input
                    FROM '.sql::table('structure_area').' as s
                    LEFT JOIN 
                        '.sql::table('module').' as m
                            ON m.id = s.modul
                    WHERE structure_id = '.$structure_id.' AND slot = 1
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
                                $class = 'online fa fa-check';
                            } else {
                                $class = 'offline fa fa-times';
                            }
                            
                            ?>
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h3 class="panel-title pull-left"><?php echo $sql->get('name'); ?></h3>
                                <div class="pull-right btn-group">
                                    <a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'structure_id'=>$structure_id, 'action'=>'online', 'id'=>$sql->get('id')]); ?>" class="btn btn-sm dyn-<?php echo $class; ?>"></a>
                                    <a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'structure_id'=>$structure_id, 'action'=>'edit', 'id'=>$sql->get('id')]); ?>" class="btn btn-default btn-sm fa fa-edit"></a>
                                    <a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'structure_id'=>$structure_id, 'action'=>'delete', 'id'=>$sql->get('id')]); ?>" class="btn btn-danger btn-sm fa fa-trash-o delete"></a>
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
					?>
                    
    			</div>
            </div>
        </div>
    </div>

<?php
//Wenn action
} else {

	$secondpage = type::super('secondpage', 'string');
	
	if(!is_null($secondpage) && dyn::get('user')->hasPerm('page[content]')) {
		
		if(!is_null(type::post('save-back')) || !is_null(type::post('save'))) {
			slot::saveBlock();
			echo message::success(lang::get('structure_content_save'), true);
		}
		
		if($secondpage == 'show') {
		
			$sql = sql::factory();
			$sql->query('
			SELECT 
			  s.*, m.output 
			FROM 
			  '.sql::table('slots').' AS s
			  LEFT JOIN
				'.sql::table('module').' AS m
					ON m.id = s.modul
			WHERE s.id='.$id)->result();
			$pageArea = new pageArea($sql);
			
			$form = form::factory('module', 'id='.$sql->get('modul'), 'index.php');
			$form->setSave(false);
			$form->addFormAttribute('class', '');
			$form->setSuccessMessage(null);
			
			$input = $pageArea->OutputFilter($form->get('input'), $sql);
			$form->addRawField($input);
			
			$form->addHiddenField('secondpage', $secondpage);
			$form->addHiddenField('id', $id);
			
		?>
		<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">"<?php echo $sql->get('name') ?>" <?php echo lang::get('edit'); ?></h3>
						</div>
						<div class="panel-body">
							<?php echo $form->show(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php
			
		}
		
	} else {
		
		if($action == 'delete' && dyn::get('user')->hasPerm('page[delete]')) {
			
			$sql = sql::factory();
			$sql->setTable('slots');
			$sql->setWhere('id='.$id);
			$sql->delete();
			
			echo message::success(lang::get('slot_deleted'), true);
			
			$action = '';	
		}
		
		if($action == 'add' || $action == 'edit' && dyn::get('user')->hasPerm('page[edit]')) {
			
			layout::addJsCode("
		var button = $('#allcat-button');
		var content = $('#allcat-content');
		
		button.change(function() {
				if(button.is(':checked')) {
					content.stop().slideUp(300);
				} else {
					content.stop().slideDown(300);
				}
		});");
		
			$form = form::factory('slots', 'id='.$id, 'index.php');
			
			$field = $form->addTextField('name', $form->get('name'));
			$field->fieldName(lang::get('name'));
			$field->autofocus();
			
			$field = $form->addTextField('description', $form->get('description'));
			$field->fieldName(lang::get('description'));
			
			$field = $form->addCheckboxField('is-structure', $form->get('is-structure'));
			$field->fieldName(lang::get('slots_show'));
			$field->add('1', lang::get('all_categories'), ['id'=>'allcat-button']);
			
			$select = pageMisc::getTreeStructure(true, $form->get('structure'));
			
			if($form->get('is-structure') == 1)
				$select->addAttribute('style', 'display:none;');
			
			$select->setMultiple();
			$select->setSize(10);
			$select->setId('allcat-content');
			$form->addElement('pages', $select);
			
			if($action == 'edit') {
				$form->addHiddenField('id', $id);	
			}
			
			if($form->isSubmit()) {
				
				$form->addPost('template', dyn::get('template'));
					
			}
			
		?>
			<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?php echo ($form->get('name')) ? $form->get('name') : lang::get('add'); ?></h3>
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
		
			$table = table::factory();
			
			$table->addCollsLayout('200,*,110');
			
			$table->addRow()
			->addCell(lang::get('name'))
			->addCell(lang::get('description'))
			->addCell(lang::get('action'));
			
			$table->addSection('tbody');
			$table->setSql("SELECT * FROM ".sql::table('slots')." WHERE template = '".dyn::get('template')."'");
			if($table->numSql()) {
				while($table->isNext()) {
					
					$edit = '';
					$deleted = '';
					
					if(dyn::get('user')->hasPerm('page[content]')) {
						$name = '<a href="'.url::backend('structure', ['subpage'=>'slots', 'structure_id'=>$table->get('id')]).'">'.$table->get('name').'</a>';
					} else {
						$name = $table->get('name');	
					}
					
					if(dyn::get('user')->hasPerm('page[edit]')) {
						$edit = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'edit', 'id'=>$table->get('id')]).' class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
					}
					
					if(dyn::get('user')->hasPerm('page[delete]')) {
						$delete = '<a href='.url::backend('structure', ['subpage'=>'slots', 'action'=>'delete', 'id'=>$table->get('id')]).' class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
					}
					
					$table->addRow()
					->addCell($name)
					->addCell($table->get('description'))
					->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
					
					$table->next();
				}
			} else {
			
				$table->addRow()
				->addCell(lang::get('no_entries'), ['colspan'=>3]);
			
			}
		?>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title pull-left"><?php echo lang::get('slots_current_page'); ?></h3>
						<?php
						if(dyn::get('user')->hasPerm('page[edit]')) { 
						?>
						<span class="btn-group pull-right">
							<a href="<?php echo url::backend('structure', ['subpage'=>'slots', 'action'=>'add']); ?>" class="btn btn-sm btn-default"><?php echo lang::get('add'); ?></a>
						</span>
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
		
	}

}
?>