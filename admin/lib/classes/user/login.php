<?php

class userLogin {

	protected $email;
	protected $password;
	
	public function __construct() {
	
		if(isset($_GET["logout"])) {
	
			$this->logout();
			   
		} elseif(!empty($_SESSION["userID"]) && ($_SESSION["logged_in"] == 1)) {
	
			$this->loginSession();
	
		} elseif (isset($_GET["login"])) {
	
			$this->loginPost();
	
		}
	
	}
	
	//wenn Session stimmt, status auf true
	protected function loginSession() {
	
		$this->logged_in = true;
	
	}
	
	//Einloggen
	protected function loginPost() {
	
		if (!empty($_POST["email"]) && !empty($_POST["password"])) {
		
			$sql = new sql();
			$sql->result('SELECT * FROM user');
			echo $sql->num();
		
		}
	
	}
	
	//hashen mit sha1
	public static function hash($password) {
		
		return sha1($password);
		
	}
	
	//password mit hast vergleichen
	public static function checkPassword($password, $hash) {
	
		return self::hash($password) == $hash;
	
	}
	
	//session löschen und status auf false
	public function logout() {   
	
		session_destroy();
		$this->logged_in = false;
		
	}
	
	//status wiedergeben
	public function getStatus() {
		
		return $this->logged_in;
		
	}

}

?>
