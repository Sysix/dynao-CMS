<?php

class pageMisc {
	
	public static function getTreeStructure($offlinePages = true, $selected, $spacer = '&nbsp; &nbsp;', $parentId = 0, $lvl = 0, $select = false) {
		
		$extraWhere = '';
		
		
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
		
		if($select === false) {
			$select = formSelect::factory('structure', $selected);
		}
		
		$sql = sql::factory();
		$sql->query('SELECT id, name, online FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();	
		while($sql->isNext()) {
			
			if($offlinePages) {
				$style = ($sql->get('online') == 1) ? 'page-online' : 'page-offline';
			} else {
				$style = '';	
			}
			
			$name = $sql->get('name');
				
			for($i = 1; $i <= $lvl; $i++) {
				$name = $spacer.$name;
			}
			
			$select->add($sql->get('id'), $name, ['class'=>$style]);
			
			if($sql->num('SELECT id FROM '.sql::table('structure').' WHERE parent_id = '.$sql->get('id').$extraWhere)) {
				self::getTreeStructure($offlinePages, $selected, $spacer, $sql->get('id'), $lvl+1, $select);
			}
			
			$sql->next();
		}
		
		return $select;
		
	}
	
	public static function getTreeStructurePage($parentId = 0, $lvl = 0) {
		
		$id = type::super('id', 'int', 0);
		$action = type::super('action', 'string');
		
		if($action == 'add' || $action == 'edit' && dyn::get('user')->hasPerm('page[edit]')) {
			
			$buttonSubmit = formButton::factory('save', lang::get('article_save'));
			$buttonSubmit->addAttribute('type', 'submit');
			$buttonSubmit->addClass('btn-sm');
			$buttonSubmit->addClass('btn-default');	
			
		}
		
		if(!$lvl && $action == 'add' && dyn::get('user')->hasPerm('page[edit]')) {
			
			$inputName = formInput::factory('name', '');
			$inputName->addAttribute('type', 'text');
			$inputName->addClass('input-sm');
			$inputName->autofocus();
			
			$sql = sql::factory();
			$inputSort = formInput::factory('sort', $sql->num('SELECT 1 FROM '.sql::table('structure').' WHERE `parent_id`= '.$parentId)+1);
			$inputSort->addAttribute('type', 'text');
			$inputSort->addClass('input-sm');
			
			echo '<ul class="list"><li>'.$inputSort->get().$inputName->get().$buttonSubmit->get().'</li></ul>';
			
		}
		
		$select = '';
		
		$id = (!$lvl) ? 'id="structure-tree"' : '';
		
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.' ORDER BY sort')->result();
		if($sql->num()) {
			
			$select .= '<ul '.$id.'>';
				
			while($sql->isNext()) {
				
				if($action == 'edit' && $sql->get('id') == $id && dyn::get('user')->hasPerm('page[edit]')) {
					
					$inputName = formInput::factory('name', $sql->get('name'));
					$inputName->addAttribute('type', 'text');
					$inputName->addClass('input-sm');
					$inputName->addClass('structure-name');
					$inputName->autofocus();
					
					
					$inputSort = formInput::factory('sort', $sql->get('sort'));
					$inputSort->addAttribute('type', 'hidden');
					echo $inputSort->get();
					
					$inputHidden = formInput::factory('id', $sql->get('id'));
					$inputHidden->addAttribute('type', 'hidden');
					echo $inputHidden->get();
					
					$select .= '<li class="input-group structure-edit">
						'.$inputName->get().'
						<span class="input-group-addon">'.$buttonSubmit->get().'<span>
					</li>';
					
				} else {
					
					$module = '';
					$edit = '';
					$online = '';
					$offline = '';
					$delete = '';
						
					if(dyn::get('user')->hasPerm('page[content]')) {
						$module = '<a href="'.url::backend('structure', ['structure_id'=>$sql->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('modules').'</a>';
					}
					
					if(dyn::get('user')->hasPerm('page[edit]')) {
						$edit = '<a href="'.url::backend('structure', ['action'=>'edit', 'id'=>$sql->get('id')]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
						$online = '<a href="'.url::backend('structure', ['action'=>'online', 'id'=>$sql->get('id')]).'" class="btn btn-sm dyn-online fa fa-check" title="'.lang::get('online').'"></a>';	
						$offline = '<a href="'.url::backend('structure', ['action'=>'online', 'id'=>$sql->get('id')]).'" class="btn btn-sm dyn-offline fa fa-times" title="'.lang::get('offline').'"></a>';	
					}
					
					if(dyn::get('user')->hasPerm('page[delete]')) {
						$delete = '<a href="'.url::backend('structure', ['action'=>'delete', 'id'=>$sql->get('id')]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
					}

					$online = ($sql->get('online')) ? $online : $offline;
				
					$select .= '<li data-id="'.$sql->get('id').'">'.PHP_EOL.'
						<div class="handle"><i class="fa fa-sort"></i> '.$sql->get('name').PHP_EOL.'
							<span class="btn-group">'.$module.$online.$edit.$delete.'</span>'.PHP_EOL.'
						</div>'.PHP_EOL;
				
				}
				
				$select .= self::getTreeStructurePage($sql->get('id'), $lvl+1);			
				
				$select .= '</li>'.PHP_EOL;
				
				$sql->next();
			}
			
			$select .= '</ul>';
		
		}
				
		return $select;
		
	}
	
	public static function getTreeStructurePagePopup($parentId = 0, $lvl = 0) {
		
		$select = '';
		
		$id = (!$lvl) ? 'id="structure-tree"' : '';
		
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.' ORDER BY sort')->result();
		if($sql->num()) {
			
			$select .= '<ul '.$id.'>';
				
			while($sql->isNext()) {
				
					
				$button = '<button data-id="'.$sql->get('id').'" data-name="'.$sql->get('name').'" data-loading-text="'.lang::get('selected').'" class="btn btn-sm btn-warning dyn-link-select">'.lang::get('select').'</button>';
				
				$select .= '<li>'.PHP_EOL.'
					<div class="handle">'.$sql->get('name').PHP_EOL.'
						<span class="btn-group">'.$button.'</span>'.PHP_EOL.'
					</div>'.PHP_EOL;
					
				
				$select .= self::getTreeStructurePagePopup($sql->get('id'), $lvl+1);			
				
				$select .= '</li>'.PHP_EOL;
				
				$sql->next();
			}
			
			$select .= '</ul>';
		
		}
				
		return $select;
		
	}
	
}

?>