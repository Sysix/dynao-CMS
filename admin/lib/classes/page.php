<?php

class page {
	use traitFactory;
	use traitMeta;
	
	protected $sql;
	
	public function __construct($id, $offlinePages = true) {
		
		if(is_object($id)) {
			
			$this->sql = $id;
			
		} else {
			
			$extraWhere = '';
			
			if(!$offlinePages) {				
				$extraWhere =  'AND WHERE s.online = 1';				
			}
	
			$this->sql = sql::factory();
			$this->sql->query('SELECT * 
								FROM '.sql::table('structure').' as s
								LEFT JOIN 
									'.sql::table('structure_area').' as a
										ON s.id = a.structure_id
								WHERE s.id = '.$id.$extraWhere.'
								ORDER BY `sort`')->result();
		
		}
		
	}
	
	public function get($value, $default = null) {
		
		return $this->sql->get($value, $default);
		
	}
	
	public function isOnline() {
	
		return $this->get('online', 0) == 1;
		
	}
	
	public static function getChildPages($parentId, $offlinePages = true) {
		
		$return = [];
		$classname = __CLASS__;
		
		$extraWhere = '';
			
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
	
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();							
		while($sql->isNext()) {
		
			$return[] = new $classname($sql);
			
			$sql->next();	
		}
		
		return $return;
	
	}
	
	public static function getRootPages($offlinePage = true) {
		
		return self::getChildPages(0, $offlinePage);
		
	}
	
	public static function getTreeStructure($offlinePages = true, $spacer = '&nbsp; &nbsp;', $parentId = 0, $lvl = 0) {
		
		$extraWhere = '';
		
		
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
		
		$select = '';
		
		$sql = sql::factory();
		$sql->query('SELECT id, name, online FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();	
		while($sql->isNext()) {
			
			if($offlinePages) {
				$style = ($sql->get('online') == 1) ? ' class="page-online"' : ' class="page-offline"';
			} else {
				$style = '';	
			}
			
			$name = $sql->get('name');
			
			if($spacer != '') {
				
				for($i = 1; $i <= $lvl; $i++) {
					$name = $spacer.$name;
				}
				
			}
			
			$select .= '<option value="'.$sql->get('id').'"'.$style.'>'.$name.'</option>'.PHP_EOL;
			
			if($sql->num('SELECT id FROM '.sql::table('structure').' WHERE parent_id = '.$sql->get('id').$extraWhere)) {
				$select .= self::getTreeStructure($offlinePages, $spacer, $sql->get('id'), $lvl+1);
			}
			
			$sql->next();
		}
		
		return $select;
		
	}
	
	public static function getTreeStructurePage($parentId = 0, $lvl = 0) {
		
		$id = type::super('id', 'int', 0);
		$action = type::super('action', 'string');
		
		if($action == 'add' || $action == 'edit') {
			
			$buttonSubmit = formButton::factory('save', lang::get('article_save'));
			$buttonSubmit->addAttribute('type', 'submit');
			$buttonSubmit->addClass('btn-sm');
			$buttonSubmit->addClass('btn-default');	
			
		}
		
		if(!$lvl && $action == 'add') {
			
			$inputName = formInput::factory('name', '');
			$inputName->addAttribute('type', 'text');
			$inputName->addClass('input-sm');
			
			$sql = sql::factory();
			$inputSort = formInput::factory('sort', $sql->num('SELECT 1 FROM '.sql::table('structure').' WHERE `parent_id`= '.$parentId)+1);
			$inputSort->addAttribute('type', 'text');
			$inputSort->addClass('input-sm');
			
			echo '<ul class="list"><li>'.$inputSort->get().$inputName->get().$buttonSubmit->get().'</li></ul>';
			
		}
		
		$select = '';
		
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.' ORDER BY sort')->result();
		if($sql->num()) {
			
			$select .= '<ul class="list">';
				
			while($sql->isNext()) {
				
				if($action == 'edit' && $sql->get('id') == $id) {
					
					$inputName = formInput::factory('name', $sql->get('name'));
					$inputName->addAttribute('type', 'text');
					$inputName->addClass('input-sm');
					$inputName->addClass('structure-name');
					
					
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
				
					$online = ($sql->get('online')) ? lang::get('online') : lang::get('offline');
					
					$sqlArea = sql::factory();
					$sqlArea->result('SELECT *
					FROM '.sql::table('structure_area').'
					WHERE structure_id = '.$sql->get('id'));
					
					$modulName = ($sqlArea->num() == 1) ? lang::get('module') : lang::get('modules');
					
					$module = '<a href="'.url::backend('structure', ['structure_id'=>$sql->get('id')]).'" class="btn btn-sm  btn-default">'.$sqlArea->num().' '.$modulName.'</a>';	
					$edit = '<a href="'.url::backend('structure', ['action'=>'edit', 'id'=>$sql->get('id')]).'" class="btn btn-sm  btn-default">'.lang::get('edit').'</a>';	
					$delete = '<a href="'.url::backend('structure', ['action'=>'delete', 'id'=>$sql->get('id')]).'" class="btn btn-sm btn-danger">'.lang::get('delete').'</a>';
					$online = '<a href="'.url::backend('structure', ['action'=>'online', 'id'=>$sql->get('id')]).'" class="btn btn-sm structure-'.$online.'">'.$online.'</a>';				
				
					$select .= '<li data-id="'.$sql->get('id').'" class="item">'.PHP_EOL.'
						<div class="handle"><i class="fa fa-sort"></i> '.$sql->get('name').PHP_EOL.'
							<span class="btn-group">'.PHP_EOL.$module.PHP_EOL.$online.PHP_EOL.$edit.PHP_EOL.$delete.PHP_EOL.'</span>'.PHP_EOL.'
						</div>'.PHP_EOL;
					
					if($sql->num('SELECT id FROM '.sql::table('structure').' WHERE parent_id = '.$sql->get('id'))) {
						
					}
				
				}
				
				$select .= self::getTreeStructurePage($sql->get('id'), $lvl+1);			
				
				$select .= '</li>'.PHP_EOL;
				
				$sql->next();
			}
			
			$select .= '</ul>';
		
		}
				
		return $select;
		
	}
	
}

?>