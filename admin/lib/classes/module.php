<?php

class module {
	
	public $sql;
	public $eval = true;
	
	public function __construct($id) {
	
		if(is_object($id) && is_a($id, 'sql')) {
			$this->sql = $id;
		} else {
			$this->sql = sql::factory();
			$this->sql->query('
			SELECT
			  a.*, m.output
			FROM
			  '.sql::table('structure_area').' AS a
			  LEFT JOIN
			    '.sql::table('module').' AS m
				ON
				  m.id = a.modul
			WHERE
			  a.id='.$id.'
			  AND 
			  a.status = 1
			ORDER BY
			  a.sort');
		}
		
	}
	
	public function setEval($bool) {
		
		$this->eval = $bool;
		
		return $this;
			
	}
	
	public function getContent() {
		$pageArea = new pageArea($this->sql);
		
		$pageArea->setEval($this->eval);
		
		return $pageArea->OutputFilter($this->sql->get('output'), $this->sql);
	}
	
	public static function getByStructureId($id, $block = false) {
		
		$return = [];
		$classname = __CLASS__;
		
		$where = ($block) ? 'AND block = 1' : 'AND block = 0';
		
		$sql = sql::factory();
		$sql->query('
		SELECT
		  a.*, m.output
		FROM
		  '.sql::table('structure_area').' AS a
		  LEFT JOIN
		    '.sql::table('module').' AS m
			ON
			  m.id = a.modul
		WHERE
		  a.structure_id='.$id.'
		  '.$where.'
		  AND
		  a.online = 1
		ORDER BY
		  a.sort')->result();
		while($sql->isNext()) {
			$sql2 = clone $sql;
			$return[] = new $classname($sql2);
			$sql->next();
			
		}
		
		return $return;
			
	}

}

?>