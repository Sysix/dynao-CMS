<?php

// Klasse für die Verbindung zur SQL Datenbank
// Verwendung von MYSQLI
class db extends mysqli {

	static $DB_host;
	static $DB_user;
	static $DB_password;
	static $DB_datenbank;
	
	var $result;
	
	// Verbindung zur Datenbank
	static public function connect($host, $user, $pw, $db) {
	
		self::DB_host = $host;
		self::DB_user = $user;
		self::DB_password = $pw;
		self::DB_datenbank = $db;
		
		parent::__construct(self::DB_host, self::DB_user, self::DB_password, self::DB_datenbank);
		
		// Zukünftige Abfrage falls was falsch geloffen ist		
	
	}
	
	// Query durchführen
	public function sql($query) {
	
		$this->result = parent::query($query);
		
		// Falls ein Fehler ist, ausspucken
		
	}
	
	// Ruckgabe der Einträge als Array
	// Standart = Nur Spaltenname
	public function array($typ = MYSQL_ASSOC) { // $query = false, $typ = MYSQL_ASSOC
		
		/*
		if(!$query) {
			$query = $this->result;
		}
		*/
		
		// Abfrage ob $typ gültig ist
		
		return parent::fetch_array($typ);
		
	}
	
	// Abfrage Anzahl der Einträge
	public function num($query = false) {
	
		if(!$query) {
			return parent::num_rows;
		} else {
			return mysqli_num_rows(mysqli_query($query));
		}
	
	}

}

?>