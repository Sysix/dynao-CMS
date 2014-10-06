<?php

class pageAreaHtml {
	
	public static $modulList = [];
	public static $modulListAll = [];

	public static function selectBlock($structureID, $lang, $sort = false, $block = false)  {
		
		$value = ($block) ? 'blocks' : 'pages';
		
		$return  = '<div class="structure-addmodul-box">';
		$return .= '	<form action="index.php" method="get">';
		$return .= '		<input type="hidden" name="page" value="structure" />';
		$return .= '		<input type="hidden" name="subpage" value="'.$value.'" />';	
		$return .= '		<input type="hidden" name="structure_id" value="'.$structureID.'" />';
		$return .= '		<input type="hidden" name="action" value="add" />';
        $return .= '        <input type="hidden" name="lang" value="'.$lang.'" />';
		
		if($sort)
			$return .= '		<input type="hidden" name="sort" value="'.$sort.'" />';
		
		$return .= '		<select name="modul" class="form-control">';
		$return .= '		<option>'.lang::get('module_add').'</option>';
		$return .= self::moduleList(false, $block);
		$return .= '		</select>';
		$return .= '	</form>';
		$return .= '</div>';
		
		return $return;
		
	}
	
	public static function moduleList($active = false, $blocks = false) {
		
		if(!$blocks) {
			$where = ' WHERE `blocks` != 1';
			$mlist = &self::$modulList;
		} else {
			$where = '';
			$mlist = &self::$modulListAll;
		}
	
		if(empty($mlist)) {
	
			$sql = sql::factory();				
			$sql->result('SELECT id, name FROM '.sql::table('module').$where.' ORDER BY `sort`');
			while($sql->isNext()) {
				
				$selected = ($active && $active == $sql->get('id')) ? 'selected="selected"' : '' ;
			
				$mlist[] = '<option value="'.$sql->get('id').'" '.$selected.'>'.$sql->get('name').'</option>';
			
				$sql->next();
			}
			
		}
		
		return implode(PHP_EOL, $mlist);
		
	}

    /*
     * return form
     */
	public static function formBlock(pageArea $module) {


		$form = form::factory('structure_area', 'id='.$module->getId(), 'index.php');
        /** @var form $form */


        $form->setSql($module->getSql());
        $form->setWhere('id='.$module->getId());

        $mode = ($module->isNew()) ? 'add' : 'edit';

        $form->setMode($mode);
        $form->setTable('structure_area');


		$form->addFormAttribute('class', '');

        $form = pageAreaAction::saveBlock($form);

		$input = $module->OutputFilter($module->getSql()->get('input'), $form);

		$form->addRawField($input);

        $field = $form->addRadioField('online', $form->get('online', 1));
        $field->fieldName(lang::get('block_status'));
        $field->add(1, lang::get('online'));
        $field->add(0, lang::get('offline'));

        $form->addHiddenField('structure_id', $form->get('structure_id', $module->getStructureId()));
		$form->addHiddenField('modul', $form->get('modul', $module->getModulId()));
		$form->addHiddenField('sort', $form->get('sort', $module->getSort()));
        $form->addHiddenField('lang', $form->get('lang', $module->getLang()));
        $form->addHiddenField('block', $form->get('block', $module->getBlock()));

		$form->addParam('structure_id', $module->getStructureId());
        $form->addParam('lang', $module->getLang());

        if($form->isEditMode()) {
            $form->addHiddenField('id', $module->getId());
        }

        if($form->isSubmit()) {
            pageMisc::updateTime($form->get('structure_id'));
            pageAreaAction::saveSortUp($form->get('structure_id'), $module->getLang(), $form->get('sort'), false);
        }

		return $form;
		
	}
	
	public static function formOut($form) {

        return bootstrap::panel($form->get('name'), [], $form->show());
		
	}
	
}

?>