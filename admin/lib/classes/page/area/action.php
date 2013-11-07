<?php

class pageAreaAction {	
	
	static $types = array(
		'VALUE' => 15, 
		'LINK' => 10,
		'MEDIA' => 10,
		'PHP' => 2); #typen
	
	public static function saveBlock() {
		
		$id = type::post('id', 'int');
		
		$sql = sql::factory();
		$sql->setTable('structure_area');
		
		$sql->getPosts(array(
			'structure_id'=>'int',
			'sort'=>'int',
			'modul'=>'int',
			'online'=>'int'
		));
		
		foreach(array_keys(self::$types) as $types) {
			
			$array = type::post('DYN_'.$types, 'array', array());
			foreach($array as $key=>$value) {
				$sql->addPost(strtolower($types).$key, $value);			
			}
						
		}
		
		self::saveSortUp($sql->getPost('structure_id'), $sql->getPost('sort'));
		
		if($id) {
			$sql->setWhere('id='.$id);
			$sql->update();
		} else {
			$sql->save();
		}
		
	}	
	
	public static function delete($id) {
	
		$sql = sql::factory();		
		$sql->query('SELECT `structure_id`, `sort` FROM '.sql::table('structure_area').' WHERE id='.$id)->result();
		
		$delete = sql::factory();
		$delete->setTable('structure_area');
		$delete->setWhere('id='.$id);
		$delete->delete();
		
		self::saveSortUp($sql->get('structure_id'), $sql->get('sort'), false);
		
		return $sql->get('structure_id');
		
	}
	
	protected static function saveSortUp($id, $sort, $up = true) {		
	
		sql::sortTable('structure_area', $sort, $up, '`structure_id` = '.$id);
		
	}
		
}

?>