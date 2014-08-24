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
	
	public static function getTreeStructurePage($parentId = 0, $lang, $lvl = 0) {
		
		$select = '';
		
		$id = (!$lvl) ? 'id="structure-tree"' : '';
		
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.' AND `lang` = '.$lang.' ORDER BY sort')->result();
		
		if($sql->num()) {
			
			$select .= '<ul '.$id.'>';
				
			while($sql->isNext()) {
					
				$edit = '';
				$online = '';
				$offline = '';
				$delete = '';					
				$name = $sql->get('name');
				
				if(dyn::get('user')->hasPerm('page[content]')) {
					$name = '<a href="'.url::backend('structure', ['subpage'=>'pages', 'structure_id'=>$sql->get('id')]).'">'.$sql->get('name').'</a>';
				}
				
				if(dyn::get('user')->hasPerm('page[edit]')) {
					$edit = '<a href="'.url::backend('structure', ['subpage'=>'pages', 'action'=>'edit', 'id'=>$sql->get('id')]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
					$online = '<a href="'.url::backend('structure', ['subpage'=>'pages', 'action'=>'online', 'id'=>$sql->get('id')]).'" class="btn btn-sm dyn-online fa fa-check" title="'.lang::get('online').'"></a>';	
					$offline = '<a href="'.url::backend('structure', ['subpage'=>'pages', 'action'=>'online', 'id'=>$sql->get('id')]).'" class="btn btn-sm dyn-offline fa fa-times" title="'.lang::get('offline').'"></a>';	
				}
				
				if(dyn::get('user')->hasPerm('page[delete]')) {
					$delete = '<a href="'.url::backend('structure', ['subpage'=>'pages', 'action'=>'delete', 'id'=>$sql->get('id')]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
				}

				$online = ($sql->get('online')) ? $online : $offline;
				
				$select .= '<li data-id="'.$sql->get('id').'">'.PHP_EOL.'
					<div class="handle"><i class="fa fa-sort"></i> '.$name.PHP_EOL.'
						<span class="btn-group">'.$online.$edit.$delete.'</span>'.PHP_EOL.'
					</div>'.PHP_EOL;
				
				$select .= self::getTreeStructurePage($sql->get('id'), $lvl+1);
				
				if($sql->counter+1 == $sql->num()) {
					$select .= '<div class="droppages"></div>';
				}
				$select .= '</li>'.PHP_EOL;
				
				$sql->next();
			}
			
			
			
			$select .= '</ul>';
			
		} elseif(!$select && $lvl == 0)
			$select = lang::get('no_entries');
			
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
	
	public static function sortStructure($sort, $pid = 0) {
			
	    $sql = sql::factory();
		$sql->setTable('structure');
		$i = 1;
		foreach($sort as $name=>$value) {

			$sql->addPost('sort', $i);
			$sql->addPost('parent_id', $pid);
			$sql->setWhere('id='.$value['id']);
			$sql->update();

			if(isset($value['children']) && count($value['children'])) {

				self::sortStructure($value['children'], $value['id']);

			}

			$i++;
		}
			
	}

    public static function updateTime($id, $created = false) {

        $sql = sql::factory();
        $sql->setTable('structure');
        $sql->setWhere('id='.$id);
        $sql->addDatePost('updatedAt');

        if($created) {
            $sql->addDatePost('createdAt');
        }

        $sql->update();

    }
	
}

?>