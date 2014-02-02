<?php

// Klasse für die Verbindung zur SQL Datenbank
// Verwendung von MYSQLI
class sql {
	use traitFactory;

	static $DB_host;
	static $DB_user;
	static $DB_password;
	static $DB_datenbank;
	
	static $QUERY_TYPE = [MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH];
	
	public $query;
	public $result;
	
	var $counter = 0;
	
	// SQL Functionen
	static $sql;
	
	// Zur Speicherung der Einträge
	public $values = [];
	public $table;
	public $where;
	
	// Verbindung zur Datenbank
	static public function connect($host, $user, $pw, $db) {
	
		self::$DB_host = $host;
		self::$DB_user = $user;
		self::$DB_password = $pw;
		self::$DB_datenbank = $db;
		
		self::$sql = new MySQLi(self::$DB_host, self::$DB_user, self::$DB_password, self::$DB_datenbank);
		
		$sql = sql::factory();
		$sql->query('SET SQL_MODE=""');
		
		self::$sql->set_charset('utf8');
		
		return self::$sql->connect_error;
		
		// Zukünftige Abfrage falls was falsch geloffen ist		
	
	}
	
	static public function table($table) {
		
		$DB = dyn::get('DB');
		
		return $DB['prefix'].$table;
		
	}
	
	// Query durchführen
	public function query($query) {
		
		$this->query = self::$sql->query($query);
			
		try {
			
			if(!$this->query) {
				throw new Exception($query.'<br />'.self::$sql->error);
			}
			
		} catch(Exception $e) {
			
			echo message::danger($e->getMessage());
			
		}
		
		return $this;
		
	}
	
	// Ruckgabe der Einträge als Array
	// Standart = Nur Spaltenname
	public function result($query = false, $type = MYSQL_ASSOC) {
		
		
		try {
			
			if($query) {
				$this->query($query);
			}
			
			if(!in_array($type, self::$QUERY_TYPE)) {
				
				throw new Exception(sprintf(lang::get('sql_result_invalid_type'), __CLASS__));
				
			}
			
			if(!$this->query) {
				
				throw new Exception(lang::get('sql_result_error'));
				
			} else {		
			
				$this->result = $this->query->fetch_array($type);
			}
				
			return $this;
			
			
			
		} catch(Exception $e) {
			
			echo message::danger($e->getMessage());
			
		}		
		
		
	}
	
	// Abfrage Anzahl der Einträge
	public function num($query = false) {
		
		if(!$query) {
			return ($this->query) ? $this->query->num_rows : 0;			
		}
			
		$sql = sql::factory();
		$sql->result($query);
		return $sql->num();
	
	}
	
	public function insertId() {
		
		return self::$sql->insert_id;
		
	}
	
	// Ausgabe der Spalte 
	public function get($row, $default = null) {
		
		if(isset($this->values[$row])) {
		
			return $this->values[$row];
			
		}
		
		return $this->getValue($row, $default);
		
	}
	
	public function getArray($row, $delimiter= '|') {
	
		return explode($delimiter, $this->get($row));
		
	}
	
	public function getJson($row) {
	
		return json_decode($this->get($row), true);
		
	}
	
	public function getSerialize($row) {
	
		return unserialize($row);
		
	}
	
	public function getValue($row, $default = null) {
		
		if(isset($this->result[$row])) {
			
			return 	$this->result[$row];
			
		}
		
		return $default;
		
	}
	
	public function getRow() {
		
		return $this->result;
			
	}
	
	public static function showColums($table, $prefix = '', $like = true) {
		
		$suffix = '';
		if($like) {
			$suffix = '%';	
		}
		
		if($prefix) {
			$prefix	= ' LIKE "'.$prefix.$suffix.'"';
		}	
			
		$class = __CLASS__;
		$sql = new $class();
		$sql->result('SHOW COLUMNS FROM '.sql::table($table).$prefix);
		
		return $sql;
	}
	 
	// Ausgabe der Einträge, für die Whileschleife
	public function next() {		
		
		$this->counter++;
		
		if($this->isNext()) {
			
			// Nächsten Datensatz laden
			$this->result();
			
		}
		
		return $this;
		
	}
	
	// Abfrage ob noch der Counter benutzt werden kann
	public function isNext() {
		
		return $this->counter < $this->num();
		
	}
	
	//
	// Methoden zur Speicherung der Einträge
	//	
	public function getPosts($post) {
	
		if(!is_array($post)) {
			//throw new Exception();	
		}
		
		foreach($post as $val=>$cast) {
			$this->values[$val] = $this->escape(type::post($val, $cast, '')); 	
		}
		
		return $this;
			
	}
	
	public function addPost($name, $val) {
		
		$this->values[$name] = $this->escape($val);
		
		return $this;
		
	}
	
	public function delPost($name) {
	
		unset($this->values[$name]);
		
		return $this;
		
	}
	
	public function getPost($name, $default = null) {
	
		if(isset($this->values[$name])) {
		
			return $this->values[$name];	
			
		}
		
		return $default;
		
	}
	
	public function escape($name) {
	
		return self::$sql->escape_string($name);
		
	}
	
	public function setWhere($where) {
	
		$this->where = $where;
		
		return $this;
		
	}
	
	public function setTable($table) {
		
		$this->table = self::table($table);
		
		return $this;
		
	}
	
	public function select($select = '*') {
		
		$this->query('SELECT '.$select.' FROM `'.$this->table.'` WHERE '.$this->where);
		
		return $this;
		
	}
	
	public function save() {
		
		$keys = '`'.implode('`,`', array_keys($this->values)).'`';
		$entrys = '"'.implode('","', $this->values).'"';
		
		$this->query('INSERT INTO `'.$this->table.'` ('.$keys.') VALUES ('.$entrys.')');
		
		return $this;
		
	}
	
	public function update() {
		
		$entrys = '';
		
		foreach($this->values as $key=>$val) {
			$entrys .= ' `'.$key.'` = "'.$val.'",';
		}
		
		$entrys = substr($entrys , 0, -1);		
		
		$this->query('UPDATE `'.$this->table.'` SET'.$entrys.' WHERE '.$this->where);
		
		return $this;
		
	}
	
	public function delete() {
	
		$this->query('DELETE FROM `'.$this->table.'` WHERE '.$this->where);
		
		return $this;
		
	}
	
	static public function sortTable($table, $sort, $where = '', $select = ['id', 'sort']) {
		
		if($where)
			$where = ' WHERE '.$where;
		
		$update = sql::factory();
		$update->setTable($table);
		
		$i = 1;
		
		$sql = sql::factory();
		$sql->query('SELECT `'.$select[0].'`, `'.$select[1].'` FROM '.self::table($table).$where.' ORDER BY `'.$select[1].'` ASC')->result();
		
		while($sql->isNext()) {
			
			if($sort == $i) {
				$i++;	
			}
			
			$update->addPost($select[1], $i);
			
			$update->setWhere($select[0].'='.$sql->get($select[0]));
			$update->update();
			
			$sql->next();
			$i++;
			
		}
		
	}

}

?>