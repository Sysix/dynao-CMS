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
	var $counter;
	
	
	// SQL Functionen
	var $sql;
	
	// Verbindung zur Datenbank
	static public function connect($host, $user, $pw, $db) {
	
		self::$DB_host = $host;
		self::$DB_user = $user;
		self::$DB_password = $pw;
		self::$DB_datenbank = $db;
		
		$this->sql = new MySQLi(self::$DB_host, self::$DB_user, self::$DB_password, self::$DB_datenbank);
		
		if($this->sql->connect_error) {
				// new Exception();
		}
		
		// Zukünftige Abfrage falls was falsch geloffen ist		
	
	}
	
	// Query durchführen
	public function sql($query) {
		
		$this->query = $query;
	
		$this->sql->query($query);
		
		return $this;
		
	}
	
	// Ruckgabe der Einträge als Array
	// Standart = Nur Spaltenname
	public function result($query = false, $typ = MYSQL_ASSOC) {
		
		if($query) {
			$this->sql($query);
		}
		
		if(!in_array($typ,[MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH])) {
				// new Exception();
		}

		$this->result = $this->sql->fetch_array($typ);
		
		return $this;
		
	}
	
	// Abfrage Anzahl der Einträge
	public function num($query = false) {
	
		if(!$query) {
			
			return $this->sql->num_rows;
			
		} else {
			
			$sql = new sql();
			$sql->get($query);
			return $sql->num();
			
		}
	
	}
	
	// Ausgabe der Spalte 
	public function get($row, $default = false) {
	
		if(isset($this->result[$this->counter][$row])) {
			return 	$this->result[$this->counter][$row];
		}
		
		return $default;
		
	}

}

?>