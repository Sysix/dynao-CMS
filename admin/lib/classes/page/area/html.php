<?php

class pageAreaHtml {
	
	public static $modulList = [];
	public static $modulListAll = [];

	public static function selectBlock($structureID, $sort = false, $block = false)  {
		
		$value = ($block) ? 'blocks' : 'pages';
		
		$return  = '<div class="structure-addmodul-box">';
		$return .= '	<form action="index.php" method="get">';
		$return .= '		<input type="hidden" name="page" value="structure" />';
		$return .= '		<input type="hidden" name="subpage" value="'.$value.'" />';	
		$return .= '		<input type="hidden" name="structure_id" value="'.$structureID.'" />';
		$return .= '		<input type="hidden" name="action" value="add" />';			
		
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
	
	public static function formBlock($module) {
		
		$form = form::factory('module', 'id='.$module->getModulId(), 'index.php');
		$form->setSave(false);
		$form->addFormAttribute('class', '');
		$form->setSuccessMessage(null);
		
		$input = $module->OutputFilter($form->get('input'), $module->getSql());
		
		$form->addRawField($input);
		$form->addHiddenField('structure_id', $module->getStructureId());
		
		
		
		if($module->getId()) {
			
			$form->setMode('edit');
			$online = $module->get('online');
			
			
		} else {
			
			$form->setMode('add');	
			$online = 1;
			
			$form->delButton('save-back');
						
		}
		
		$form->addHiddenField('modul', $module->getModulId());
		
		$form->addHiddenField('sort', $module->getSort());
		
		$field = $form->addRadioField('online', $online);
		$field->fieldName(lang::get('block_status'));
		$field->add(1, lang::get('online'));
		$field->add(0, lang::get('offline'));
		
		
		$form->addHiddenField('id', $module->getId());
		$form->addParam('structure_id', type::super('structure_id', 'int'));

		return $form;
		
	}
	
	public static function formOut($form) {
		
		$return = '<div class="panel panel-default">';
		$return .= '	<div class="panel-heading">';
		$return .= '		<h3 class="panel-title pull-left">'.$form->get('name').'</h3>';
		$return .= '		<div class="clearfix"></div>';
		$return .= '	</div>';
		$return .= '	<div class="panel-body">';			
		$return .= 		$form->show();
		$return .= '	</div>';
		$return .= '</div>';
		
		return $return;
		
		
	}
	
}

?>