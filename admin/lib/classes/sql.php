<?php

// Klasse für die Verbindung zur SQL Datenbank
// Verwendung von MYSQLI
class sql {

	static $DB_host;
	static $DB_user;
	static $DB_password;
	static $DB_datenbank;
	
	var $query;
	var $result;
	
	var $counter = 0;
	
	// SQL Functionen
	static $sql;
	
	// Zur Speicherung der Einträge
	var $values = array();
	var $table;
	var $where;
	
	// Verbindung zur Datenbank
	static public function connect($host, $user, $pw, $db) {
	
		self::$DB_host = $host;
		self::$DB_user = $user;
		self::$DB_password = $pw;
		self::$DB_datenbank = $db;
		
		self::$sql = new MySQLi(self::$DB_host, self::$DB_user, self::$DB_password, self::$DB_datenbank);
		
		$sql = new sql();
		$sql->query('SET SQL_MODE=""');
        $sql->query('SET NAMES utf8');
        $sql->query('SET CHARACTER SET utf8');
		
		if(self::$sql->connect_error) {
				// new Exception();
		}
		
		// Zukünftige Abfrage falls was falsch geloffen ist		
	
	}
	
	// Query durchführen
	public function query($query) {
		
		$this->query = self::$sql->query($query);
			
		try {
			
			if(!$this->query) {
				throw new Exception('Query konnte nicht ausgeführt werden<pre>'.$query.'</pre>Error: '.self::$sql->error);
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
			
			if(!in_array($type, array(MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH))) { #[MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH]
				
				throw new Exception(__CLASS__.'::result erwartet als $type MYSQLI_NUM, MYSQLI_ASSOC oder MYSQLI_BOTH');
				
			}
			
			if(!$this->query) {
				
				throw new Exception('Resultat vom Query konnte nicht erzeugt werdem');
				
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
			return ($this->query && is_object($this->query)) ? $this->query->num_rows : 0;			
		}
			
		$sql = new sql();
		$sql->result($query);
		return $sql->num();
	
	}
	
	// Ausgabe der Spalte 
	public function get($row) {
		
		if(isset($this->result[$row])) {
			
			return 	$this->result[$row];
			
		} else {
			// new Exception('Feld "'.$row.'" exestiert nicht');
		}
		
		return false;
		
	}
	
	// Ausgabe der Einträge, für die Whileschleife
	public function next() {		
		
		$this->counter++;
		
		if($this->isNext()) {
			
			// Nächsten Datensatz laden
			$this->result();
			
		}
		
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
		
	}
	
	public function delPost($name) {
	
		unset($this->values[$name]);
		
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
		
		$this->table = $table;
		
		return $this;
		
	}
	
	public function save() {
		
		$keys = '`'.implode('`,`', array_keys($this->values)).'`';
		$entrys = '"'.implode('","', $this->values).'"';
		
		$this->query('INSERT INTO `'.$this->table.'` ('.$keys.') VALUES ('.$entrys.')');
		
	}
	
	public function update() {
		
		$entrys = '';
		
		foreach($this->values as $key=>$val) {
			$entrys .= ' `'.$key.'` = "'.$val.'",';
		}
		
		$entrys = substr($entrys , 0, -1);		
		
		$this->query('UPDATE `'.$this->table.'` SET'.$entrys.' WHERE '.$this->where);
	}
	
	public function delete() {
	
		$this->query('DELETE FROM `'.$this->table.'` WHERE '.$this->where);
	}

}

?>