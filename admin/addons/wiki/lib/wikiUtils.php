<?php

class wikiUtils {
	
	public static function getTreeStructure($parentId = 0, $lvl = 0, $spacer = ' &nbsp;', $active = 0) {
		
		$select = '';
		
		$sql = sql::factory();
		$sql->query('SELECT id, name FROM '.sql::table('wiki_cat').' WHERE pid = '.$parentId.' ORDER BY sort')->result();	
		while($sql->isNext()) {
			
			$name = $sql->get('name');
			
			if($lvl) {
				$name = '- '.$name;
			}
			
			if($spacer != '') {
				
				for($i = 1; $i <= $lvl; $i++) {
					$name = $spacer.$name;
				}
				
			}
			
			$selected = ($active == $sql->get('id')) ? 'selected="selected"' : '';
			
			$select .= '<option value="'.$sql->get('id').'" '.$selected.'>'.$name.'</option>'.PHP_EOL;
						
			$select .= self::getTreeStructure($sql->get('id'), $lvl+1, $spacer, $active);
			
			$sql->next();
		}
		
		return $select;
		
	}
	
}

?>