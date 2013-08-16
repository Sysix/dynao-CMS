<?php

class module {
	
	const values = 15;
	const dyn_rex = 'DYN_(\w*)\[(\d)\]';
	static $types = array('VALUE', 'LINK', 'MEDIA', 'PHP'); #typen
	
	var $values = array();

	public static function getModuleList() {
	
		$return = array();
	
		$sql = new sql();
		
		$sql->result('SELECT id, name FROM module ORDER BY `sort`');
		while($sql->isNext()) {
		
			$return[] = array('id'=>$sql->get('id'), 'name' => $sql->get('name'));
		
			$sql->next();			
		}
		
		return $return;
		
	}
	
	public function convertValues($content) {
		
		preg_match_all('/'.self::dyn_rex.'/', $content, $values);
		$this->values = $values;
		
	}
	
	
	public function saveBlock($id) {
		
		$sql = new sql();
		$sql->setTable('structure_block');
		
		$sql->getPosts(array(
			'structure_id'=>'int',
			'sort'=>'int',
			'modul'=>'int'
		));
		
		foreach(self::$types as $types) {
			
			$array = type::post('DYN_'.$types, 'array', array());
			foreach($array as $key=>$value) {				
				$sql->addPost(strtolower($types).$key, $array[$key]);				
			}
						
		}
		
		if($sql->num('SELECT 1 FROM structure_block WHERE id='.$id)) {
			$sql->setWhere('id='.$id);
			$sql->update();	
		} else {
			$sql->save();	
		}
		
	}
	
	
	
}

?>