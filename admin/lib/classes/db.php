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
	
	// Verbindung zur Datenbank
	static public function connect($host, $user, $pw, $db) {
	
		self::$DB_host = $host;
		self::$DB_user = $user;
		self::$DB_password = $pw;
		self::$DB_datenbank = $db;
		
		self::$sql = new MySQLi(self::$DB_host, self::$DB_user, self::$DB_password, self::$DB_datenbank);
		
		if($sql->connect_error) {
				// new Exception();
		}
		
		// Zukünftige Abfrage falls was falsch geloffen ist		
	
	}
	
	// Query durchführen
	public function query($query) {
		
		$this->query = self::$sql->query($query);
		
		return $this;
		
	}
	
	// Ruckgabe der Einträge als Array
	// Standart = Nur Spaltenname
	public function result($query = false, $type = MYSQL_ASSOC) {
		
		if($query) {
			$this->query($query);
		}
		
		if(!in_array($type, array(MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH))) { #[MYSQLI_NUM, MYSQLI_ASSOC, MYSQLI_BOTH]
				// new Exception();
		}

		$this->result = $this->query->fetch_array($type);
		
		return $this;
		
	}
	
	// Abfrage Anzahl der Einträge
	public function num($query = false) {
	
		if(!$query) {
			return $this->query->num_rows;
			
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
		
	}
	
	// Ausgabe der Einträge, für die Whileschleife
	public function next() {		
		
		$this->counter++;
		
		if($this->isNext()) {
			
			// Nächsten Datensatz laden
			$this->result();
			
		}
		
	}
	
	// ABfrage ob noch der Counter benutzt werden kann
	public function isNext() {
		
		return $this->counter < $this->num();
		
	}

}

?>