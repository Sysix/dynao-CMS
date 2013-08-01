<?php

class userLogin {

	protected $email;
	protected $password;
	protected $isLogin;
	protected $userID;
	
	public function __construct() {
	
		if(!is_null(type::get('logout', 'string'))) {
	
			$this->logout();
			   
		} elseif($this->checkLogin()) {
	
			$this->loginSession();
	
		} elseif (!is_null(type::get('login', 'string'))) {
	
			$this->loginPost();
	
		}
	
	}
	
	//wenn Session stimmt, status auf true
	protected function loginSession() {
	
		$this->isLogin = true;
	
	}
	
	// Überprüfen ob Session richtig gesetzt 
	protected function checkLogin() {
	
		$session = type::session('login', 'string', false);
		
		if(!$session)
			return false;
		
		// Session[0] = ID; session[1} PW in sha1	
		$session = explode('||', $session);
		
		$sql = new sql();
		$sql->result('SELECT id FROM user WHERE `id` = '.$session[0].' AND `password` = "'.self::hash($session[1]).'"');	
			
		if(!$sql->num()) {
			
			$this->logout();
			return false;
		
		}
		
		return true;		
		
	}
	
	//Einloggen
	protected function loginPost() {
		
		$email = type::post('email', 'string');
		$password = type::post('password', 'string');
		
		// Formular ganz abgesendet?
		if(is_null($email) || is_null($password)) {
			
			echo message::info('Formular nicht vollständig gesendet!');
			$this->logout();
			return;
			
		}
		
		$sql = new sql();
		$sql->query('SELECT password, id FROM user WHERE `email` = '.$email);
		
		// Username mit E-Mail vorhanden?
		if(!$sql->num()) {
		
			echo message::danger('Kein Benutzer mit der E-Mail-Adresse "'.$email.'" gefunden');
			$this->logout();
			return;
			
		}
				
		$sql->result();
		
		// Password nicht gleich?
		if(!self::checkPassword($password, $sql->get('password'))) {
			
			echo message::danger('Das angebene Passwort ist falsch');
			$this->logout();
			return;
			
		}
		
		$this->loginSession();
		$this->userID = $sql->get('id');
		$_SESSION['login'] = $sql->get('id').'||'.self::hash($password);
	
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
		$this->isLogin = false;
		
	}
	
	//status wiedergeben
	public function isLogged() {
		
		return $this->isLogin;
		
	}

}

?>
