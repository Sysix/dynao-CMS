<?php

$langId = type::super('lang', 'int', lang::getLangId());
type::addSession('backend-lang', $langId);

if(!is_null($structure_id) && dyn::get('user')->hasPerm('page[content]')) {
	
	$sort = type::super('sort', 'int');
	
	if($action == 'online') {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure_area').' WHERE id='.$id.' AND `block` = 0 AND `lang` = '.$langId)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure_area');
		$sql->setWhere('`id`='.$id.' AND `block` = 0 AND `lang` = '.$langId);
		$sql->addPost('online', $online);
		$sql->update();
		
		pageCache::generateArticle($structure_id, $langId);
		
		echo message::success(lang::get('save_status'));
		
	}
	
	if(ajax::is()) {
		
		$sort = type::post('array', 'array');
		$sql = sql::factory();
		$sql->setTable('structure_area');
		foreach($sort as $s=>$s_id) {
			$sql->setWhere('`id`='.$s_id.' AND `block` = 0 AND `lang` = '.$langId);
			$sql->addPost('sort', $s+1);
			$sql->update();
		}
		
		pageCache::generateArticle($structure_id, $langId);
		
		ajax::addReturn(message::success(lang::get('save_sorting'), true));
		
		
	}
	
	if($action == 'delete' && dyn::get('user')->hasPerm('page[delete]')) {
	
		$id = pageAreaAction::delete($id, $langId);
		pageCache::deleteFile($id, $langId);
		echo message::success(lang::get('structure_content_delete'));

        $action = '';
		
	}
	
	$pageSql = sql::factory();
	$pageSql->result('SELECT name FROM '.sql::table('structure').' WHERE `id` = '.$structure_id.' AND `lang` = '.$langId);


    $sql = sql::factory();
    $sql->result('SELECT s.*, m.name, m.output, m.input
                    FROM '.sql::table('structure_area').' as s
                    LEFT JOIN
                        '.sql::table('module').' as m
                            ON m.id = s.modul
                    WHERE structure_id = '.$structure_id.'
                     AND `block` = 0
                     AND `lang` = '.$langId.'
                    ORDER BY `sort`');

    $i = 1;

    $back = url::be('structure', ['subpage' => 'pages', 'lang' => $langId]);
    $url = clone $back;
    $url->addParam('structure_id', $structure_id);

	?>
    <div class="row">
        <?= lang::getStructureSelection('structure', ['structure_id' => $structure_id]); ?>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"><a href="<?= $url ?>"><?php echo $pageSql->get('name'); ?></a></h3>
                    <div class="pull-right btn-group">
						<a href="<?php echo dyn::get('hp_url').url::fe($structure_id); ?>" target="_blank" class="btn btn-sm btn-warning"><?php echo lang::get('visit_page'); ?></a>
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

                        if(($action == 'edit' || $action == 'add') && ($module->getId() == $id || ($sort == $i && $sort != 1))) {

                            if($action == 'add') {

                                $sql2 = new sql();
                                $sql2->result('SELECT s.*, m.name, m.output, m.input
                                    FROM '.sql::table('module').' as m
                    LEFT JOIN
                    '.sql::table('structure_area').' as s
                            ON s.`id`= -1
                        WHERE m.id = '.type::super('modul', 'int'));


                                $module2 = new pageArea($sql2);
                                $module2->setLang($langId);
                                $module2->setNew(true);

                                $form2 = pageAreaHtml::formBlock($module2);

                                echo pageAreaHtml::formOut($form2);


                            } else {

                                $form = pageAreaHtml::formBlock($module);

                                echo pageAreaHtml::selectBlock($module->getStructureId(), $langId);

                                echo pageAreaHtml::formOut($form);

                            }

                        }

                        if($action != 'edit' || $id != $module->getId()) {

                            echo pageAreaHtml::selectBlock($module->getStructureId(), $langId);

                            if($sql->get('online')) {
                                $class = 'online fa fa-check';
                            } else {
                                $class = 'offline fa fa-times';
                            }

                            $buttonUrl = clone $url->addParam('id', $sql->get('id'));

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

                        $form = pageAreaHtml::formBlock($module);

                        if($form->isSubmit()) {
                            $form->saveForm();
                            $form->setSave(false);
                        }

						echo pageAreaHtml::formOut($form);
						
					} else {
						
						echo pageAreaHtml::selectBlock($structure_id, $langId, $sql->num()+1);
						
					}
					?>
    			</div>
            </div>
        </div>
    </div>

<?php
//Wenn action
} else {
	
	if(ajax::is() && dyn::get('user')->hasPerm('page[edit]')) {
		$post = type::super('array');
		$sort = json_decode($post, true);
		
		pageMisc::sortStructure($sort, $langId);
		
		echo message::success(lang::get('save_sorting'), true);
		
	}
	
	if(in_array($action, ['edit', 'add']) && dyn::get('user')->hasPerm('page[edit]')) {
	
		$form = form::factory('structure', '`id` = '.$id.' AND `lang` = '.$langId, 'index.php');
		
		$form->addTextField('name', $form->get('name'))
            ->fieldName(lang::get('name'))
            ->autofocus();
		
		$template = template::factory(dyn::get('template'));
		
		$form->addElement('template', $template->getTemplates('template', $form->get('template')))
             ->fieldName(lang::get('template'));
		
		$form->addRadioField('online', $form->get('online'))
            ->fieldName(lang::get('status'))
            ->add(1, lang::get('online'))
            ->add(0, lang::get('offline'));

        $form->addHiddenField('lang', $langId);

		if($action == 'edit') {
			$form->addHiddenField('id', $id);
            $title = '"'.$form->get('name').'" '.lang::get('edit');
		} else {
            $form->addHiddenField('id', pageMisc::getNewSaveId());
            $title = lang::get('add');
        }

        extension::add('FORM_AFTER_SAVE', function($sql) use($action, $id) {

            if($action == 'add') {
                pageMisc::saveLangPages($sql);
                $id = $sql->insertId();
                pageMisc::updateTime($id, true);
            } else {
                pageMisc::updateTime($id);
            }

            return $sql;

        });

        $buttons = [
            '<a class="btn btn-sm btn-warning" href="'.url::backend('structure', ['subpage'=>'pages', 'lang'=>$langId, 'structure_id'=>$form->get('id')]).'">'.lang::get('modules').'</a>',
            '<a class="btn btn-sm btn-default" href="'.url::backend('structure', ['subpage'=>'pages', 'lang'=>$langId]).'">'.lang::get('back').'</a>',
        ]

		
?>
	<div class="row"><?= bootstrap::panel($title, $buttons, $form->show()); ?></div>
<?php

	} 
	
	if($action == 'delete'  && dyn::get('user')->hasPerm('page[delete]')) {
		
		$orginal_id = $id;
		
		while($id) {
		
			$sql = sql::factory();
			$sql->query('SELECT id FROM '.sql::table('structure').' WHERE `parent_id` = '.$id.' AND `lang` = '.$langId)->result();
			if($sql->num()) {
				
				$id = $sql->get('id');
				
				$delete = sql::factory();
				$delete->setTable('structure');
				$delete->setWhere('`id` = '.$id.' AND `lang` = '.$langId);
				$delete->delete();			
				
			} else {
				$id = false;	
			}
			
		}
		
		
		$sql = sql::factory();		
		$sql->query('SELECT `sort`, `parent_id` FROM '.sql::table('structure').' WHERE id='.$orginal_id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure');
		$delete->setWhere('`id` = '.$orginal_id.' AND `lang` = '.$langId);
		$delete->delete();
		
		sql::sortTable('structure', 0, '`parent_id` = '.$sql->get('parent_id'));
		
		echo message::success(lang::get('structure_delete'));
		
		$action = '';
			
	}
	
	if($action == 'online' && dyn::get('user')->hasPerm('page[edit]')) {
	
		$sql = sql::factory();
		$sql->query('SELECT online FROM '.sql::table('structure').' WHERE id='.$id.' AND `lang` = '.$langId)->result();
		
		$online = ($sql->get('online')) ? 0 : 1;
		
		$sql->setTable('structure');
		$sql->setWhere('`id` = '.$id.' AND `lang` = '.$langId);
		$sql->addPost('online', $online);
		$sql->update();
		
		echo message::success(lang::get('save_status'));
		
		$action = '';
		
	}
	
	if($action == '') {
		
		if(ajax::is()) {
			echo pageMisc::getTreeStructurePage(0, $langId);
			exit();
		}

        $button = [];

        if(dyn::get('user')->hasPerm('page[edit]')) {
            $button = [
                '<a class="btn btn-sm btn-default" href="'.url::backend('structure', ['subpage'=>'pages', 'lang'=>$langId, 'action'=>'add']).'">'.lang::get('add').'</a>'
            ];
        }

        echo '<div class="row" id="structure-body">';
        echo lang::getStructureSelection();
        echo bootstrap::panel(lang::get('pages'), $button, pageMisc::getTreeStructurePage(0, $langId));
        echo '</div>';
	
		if(dyn::get('user')->hasPerm('page[edit]')) {
			
			layout::addJs("layout/js/structureSort.js");
		}
	
	}

}
?>
