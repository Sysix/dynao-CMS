<?php

class module {

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
	
}

?>