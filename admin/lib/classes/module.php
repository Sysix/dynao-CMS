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

    public static function getExport($id)
	{
		$sql = sql :: factory();
		$sql->query('SELECT * FROM '.sql :: table('module').' WHERE id = '.(int) $id)->result();
		return "{
    \"".$id."\": {
    \"name=\": ".json_encode($sql->get("name")).",
    \"install\": {
        \"input\": ".json_encode($sql->get("input")).",
        \"output\": ".json_encode($sql->get("output")).",
        \"blocks\": ".$sql->get("blocks")."
    }
}";
	}
	public static function sendExport($id)
	{
		$json = self :: getExport($id);
		$length = strlen($json);
		header('Content-Description: File Transfer');
		header('Content-Type: application/json');
		header('Content-Disposition: attachment; filename=module.json');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.$length);
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		header('Pragma: public');
		echo $json;
		exit;
	}

	public static function getByStructureId($id) {

		$return = [];
		$classname = __CLASS__;
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