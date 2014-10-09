<?php

$langId = type::super('lang', 'int', lang::getLangId());
type::addSession('backend-lang', $langId);

if(!is_null($structure_id) && dyn::get('user')->hasPerm('page[content]')) {
	
	$sort = type::super('sort', 'int');

	if($action == 'online') {

        $sql = sql::factory();
        $sql->query('SELECT online FROM '.sql::table('structure_area').' WHERE id='.$id.' AND `block` = 1 AND `lang` = '.$langId)->result();

        $online = ($sql->get('online')) ? 0 : 1;

        $sql->setTable('structure_area');
        $sql->setWhere('`id`='.$id.' AND `block` = 1 AND `lang` = '.$langId);
        $sql->addPost('online', $online);
        $sql->update();
		
		pageCache::generateArticle($structure_id, $langId, 'block');
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if(ajax::is()) {
		
		$sort = type::post('array', 'array');
		$sql = sql::factory();
		$sql->setTable('structure_area');
		foreach($sort as $s=>$s_id) {
			$sql->setWhere('id='.$s_id.' AND block = 1 `lang` = '.$langId);
			$sql->addPost('sort', $s+1);
			$sql->update();
		}
		
		pageCache::generateArticle($structure_id, $langId, 'block');
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		
	}
	
	if($action == 'delete') {
	
		$id = pageAreaAction::delete($id, $langId, 1);
		pageCache::deleteFile($id, $langId, 'block');
		echo message::success(lang::get('structure_content_delete'));

        $action = '';
		
	}
	
	$blockSql = sql::factory();
	$blockSql->result('SELECT name FROM '.sql::table('blocks').' WHERE `id` = '.$structure_id.' AND `lang` = '.$langId);

    $sql = sql::factory();
    $sql->result('SELECT s.*, m.name, m.output, m.input
                    FROM '.sql::table('blocks').' as s
                    LEFT JOIN
                        '.sql::table('module').' as m
                            ON m.id = s.modul
                    WHERE `block` = 1
                     AND `lang` = '.$langId.'
                    ORDER BY `sort`');

    $i = 1;

    $back = url::be('structure', ['subpage' => 'blocks', 'lang' => $langId]);
    $url = clone $back;
    $url->addParam('structure_id', $structure_id);
	
	?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><?php echo $blockSql->get('name'); ?></h3>
                    <div class="pull-right">
                        <a href="<?= $url->get(['action'=>'edit', 'id' => $structure_id]) ?>" class="btn btn-sm btn-warning"><?php echo lang::get('edit'); ?></a>
                        <a href="<?= $back ?>" class="btn btn-sm btn-default"><?php echo lang::get('back'); ?></a>
					</div>
					<div class="clearfix"></div>
                </div>
            	<div class="panel-body">
    
                    <div id="ajax-content"></div>
                    <ul id="structure-content">
                    <?php

                    while($sql->isNext()) {

                        echo '<li data-id="'.$sql->get('id').'">
                                <div class="row">';

                        $module = new pageArea(clone $sql);
                        $module->setLang($langId);
                        $module->setNew(false);
                        $module->setBlock(true);

                        if(($action == 'edit' || $action == 'add') && ($module->getId() == $id || ($sort == $i && $sort != 1))) {

                            if ($action == 'add') {

                                $sql2 = new sql();
                                $sql2->result('SELECT s.*, m.name, m.output, m.input
                                    FROM ' . sql::table('module') . ' as m
                    LEFT JOIN
                    ' . sql::table('structure_area') . ' as s
                            ON s.`id`= -1
                        WHERE m.id = ' . type::super('modul', 'int'));


                                $module2 = new pageArea($sql2);
                                $module2->setLang($langId);
                                $module2->setNew(true);
                                $module->setBlock(true);

                                $form2 = pageAreaHtml::formBlock($module2);

                                echo pageAreaHtml::formOut($form2);

                            } else {

                                $form = pageAreaHtml::formBlock($module);

                                echo pageAreaHtml::selectBlock($module->getStructureId(), $langId, false, true);

                                echo pageAreaHtml::formOut($form);

                            }

                        }

                        if($action != 'edit' || $id != $module->getId()) {

                            echo pageAreaHtml::selectBlock($module->getStructureId(), $langId, false, true);

                            if($sql->get('online')) {
                                $class = 'online fa fa-check';
                            } else {
                                $class = 'offline fa fa-times';
                            }

                            $buttonUrl = clone $url;
                            $button->addParam('id', $sql->get('id'));

                            $button = [
                                '<a href="'.$buttonUrl->get(['action' => 'online']).'" class="btn btn-sm dyn-'.$class.'"></a>',
                                '<a href="'.$buttonUrl->get(['action' => 'edit']).'" class="btn btn-default btn-sm fa fa-edit"></a>',
                                '<a href="'.$buttonUrl->get(['action' => 'delete']).'" class="btn btn-danger btn-sm fa fa-trash-o delete"></a>',
                            ];

                            echo bootstrap::panel($sql->get('name'), $button, $module->OutputFilter($sql->get('output'), $sql));

                        }

                        echo '</div>
                        </li>';

                        $sql->next();
                        $i++;

                    }
                    ?>
                    </ul>
                    <?php
                    if($i == $sort && $action == 'add') {

                        $sql = new sql();
                        $sql->result('SELECT s.*, m.name, m.output, m.input
                                    FROM '.sql::table('module').' as m
                    LEFT JOIN
                    '.sql::table('structure_area').' as s
                            ON s.`id`= -1
                        WHERE m.id = '.type::super('modul', 'int'));

                        $module = new pageArea($sql);
                        $module->setLang($langId);
                        $module->setNew(true);
                        $module->setBlock(true);

                        $form = pageAreaHtml::formBlock($module);

                        if($form->isSubmit()) {
                            $form->saveForm();
                            $form->setSave(false);
                        }

                        echo pageAreaHtml::formOut($form);

                    } else {

                        echo pageAreaHtml::selectBlock($structure_id, $langId, $sql->num()+1, false, true);

                    }
                    ?>
    			</div>
            </div>
        </div>
    </div>

<?php
//Wenn action
} else {
		
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

		$form = form::factory('blocks', '`id` = '.$id.' AND `lang` = '.$langId, 'index.php');

		$field = $form->addTextField('name', $form->get('name'));
		$field->fieldName(lang::get('name'));
		$field->autofocus();

		$field = $form->addTextField('description', $form->get('description'));
		$field->fieldName(lang::get('description'));

		$field = $form->addCheckboxField('is-structure', $form->get('is-structure'));
		$field->fieldName(lang::get('blocks_show'));
		$field->add('1', lang::get('all_pages'), ['id'=>'allcat-button']);

		$select = pageMisc::getTreeStructure(true, $form->get('structure'));

		if($form->get('is-structure') == 1)
			$select->addAttribute('style', 'display:none;');

		$select->setMultiple();
		$select->setSize(10);
		$select->setId('allcat-content');
		$form->addElement('pages', $select);

		if($action == 'edit') {

			$form->addHiddenField('id', $id);
            $title = '"'.$form->get('name').'" '.lang::get('edit');

		} else {

            $title = lang::get('add');

        }

		if($form->isSubmit()) {

			$form->addPost('template', dyn::get('template'));

		}

	?>
	<div class="row"><?= bootstrap::panel($title, [], $form->show()) ?></div>
	<?php
	}

    if($action == 'delete'  && dyn::get('user')->hasPerm('page[delete]')) {

        $delete = sql::factory();
        $delete->setTable('blocks');
        $delete->setWhere('`id`='.$id.' AND `lang`='.$langId);
        $delete->delete();

        $content = sql::factory();
        $content->setTable('block');
        $content->setWhere('block = 1 AND structure_id='.$id);
        $content->delete();

        echo message::success(lang::get('block_deleted'));

        $action = '';

    }

	if($action == '') {

		$table = table::factory();

		$table->addCollsLayout('200,*,110');

		$table->addRow()
		->addCell(lang::get('name'))
		->addCell(lang::get('description'))
		->addCell(lang::get('action'));

		$table->addSection('tbody');
		$table->setSql("SELECT * FROM ".sql::table('blocks')." WHERE `template` = '".dyn::get('template')."'");
		if($table->numSql()) {
			while($table->isNext()) {

				$edit = '';
				$deleted = '';

				if(dyn::get('user')->hasPerm('page[content]')) {
					$name = '<a href="'.url::be('structure', ['subpage'=>'blocks', 'structure_id'=>$table->get('id')]).'">'.$table->get('name').'</a>';
				} else {
					$name = $table->get('name');
				}

				if(dyn::get('user')->hasPerm('page[edit]')) {
					$edit = '<a href='.url::be('structure', ['subpage'=>'blocks', 'action'=>'edit', 'id'=>$table->get('id')]).' class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
				}

				if(dyn::get('user')->hasPerm('page[delete]')) {
					$delete = '<a href='.url::be('structure', ['subpage'=>'blocks', 'action'=>'delete', 'id'=>$table->get('id')]).' class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
				}

				$table->addRow()
				->addCell($name)
				->addCell($table->get('description'))
				->addBtnCell($edit . $delete);

				$table->next();
			}
		} else {

			$table->addRow()
			->addCell(lang::get('no_entries'), ['colspan'=>3]);

		}

        $button = '';

        if(dyn::get('user')->hasPerm('page[edit]')) {
            $button = '<a href="'.url::be('structure', ['subpage'=>'blocks', 'action'=>'add']).'" class="btn btn-sm btn-default">'.lang::get('add').'</a>';
        }

	?>
	<div class="row"><?= bootstrap::panel(lang::get('blocks_current_page'), [$button], $table->show(), ['table'=>'false']) ?></div>
	<?php
	}

}
?>