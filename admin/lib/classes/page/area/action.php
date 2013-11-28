<?php

class pageAreaAction {	
	
	public static function saveBlock() {
		
		$id = type::post('id', 'int');
		
		$sql = sql::factory();
		$sql->setTable('structure_area');
		
		$sql->getPosts([
			'online'=>'int'
		]);
		
		foreach(array_keys(pageArea::$types) as $types) {
			
			$array = type::post('DYN_'.$types, 'array', []);
			foreach($array as $key=>$value) {
				$sql->addPost(strtolower($types).$key, $value);			
			}
						
		}
		
		if($id) {
			$sql->setWhere('id='.$id);
			$sql->update();
		} else {
			$sql->save();
		}
		
		self::saveSortUp($sql->getPost('structure_id'), $sql->getPost('sort'));
		
	}	
	
	public static function delete($id) {
	
		$sql = sql::factory();		
		$sql->query('SELECT `structure_id`, `sort` FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure_area');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		self::saveSortUp($sql->get('structure_id'), $sql->get('sort'));
		
		return $sql->get('structure_id');
		
	}
	
	protected static function saveSortUp($id, $sort) {		
	
		sql::sortTable('structure_area', $sort, '`structure_id` = '.$id);
		
	}
		
}

?>