<?php

class pageAreaAction {	

	public static function saveBlock(form $form) {

		foreach(pageArea::$types as $class) {
			$class = new $class();
			$form->sql = $class->addSaveValues($form->getSql());
		}

        return $form;
	}	
	
	public static function delete($id, $block = false) {
	
		$sql = sql::factory();		
		$sql->query('SELECT `structure_id`, `sort`, `lang` FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure_area');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		self::saveSortUp($sql->get('structure_id'), $sql->get('lang'), $sql->get('sort'), $block);
		
		return $sql->get('structure_id');
		
	}
	
	public static function saveSortUp($id, $lang, $sort, $block) {
		
		$block = ($block) ? 1 : 0;
		sql::sortTable('structure_area', $sort, '`structure_id` = '.$id.' AND `block` = '.$block.' AND `lang` = '.$lang);
		
	}
		
}

?>