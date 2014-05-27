<?php

class pageAreaAction {	
	
	public static function saveBlock($block) {
		
		$id = type::post('id', 'int');
		
		$sql = sql::factory();
		$sql->setTable('structure_area');
		
		$sql->getPosts([
			'online'=>'int',
			'modul'=>'int',
			'structure_id'=>'int',
			'sort'=>'int'
		]);
		
		if($block)
			$sql->addPost('block', 1);
		
		foreach(pageArea::$types as $class) {
			$class = new $class();
			$sql = $class->addSaveValues($sql);
		}
		
		if($id) {
			$sql->setWhere('id='.$id);
			$sql->update();
		} else {
			$sql->save();
		}
		
		self::saveSortUp($sql->getPost('structure_id'), $sql->getPost('sort'), $block);
		
	}	
	
	public static function delete($id, $block = false) {
	
		$sql = sql::factory();		
		$sql->query('SELECT `structure_id`, `sort` FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure_area');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		self::saveSortUp($sql->get('structure_id'), $sql->get('sort'), $block);
		
		return $sql->get('structure_id');
		
	}
	
	protected static function saveSortUp($id, $sort, $block) {		
		
		$block = ($block) ? 1 : 0;
		sql::sortTable('structure_area', $sort, '`structure_id` = '.$id.' AND `block` = '.$block);
		
	}
		
}

?>