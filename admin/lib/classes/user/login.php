<?php

class userLogin {

	static protected $isLogin = false;
	static protected $userID;
	
	public function __construct() {
	
		if(!is_null(type::get('logout', 'string'))) {
	
			self::logout();
			   
		} elseif(self::checkLogin()) {
	
			self::loginSession();
	
		} elseif (!is_null(type::post('login', 'string'))) {
	
			self::loginPost();
	
		}
	
	}
	
	//wenn Session stimmt, status auf true
	protected static function loginSession() {
	
		self::$isLogin = true;
	
	}
	
	// Überprüfen ob Session richtig gesetzt 
	protected static function checkLogin() {
	
		$session = type::session('login', 'string', false);
		
		if(!$session)
			return false;
		
		// Session[0] = ID; session[1} PW in sha1	
		$session = explode('||', $session);	
		
		$sql = new sql();
		$sql->result('SELECT id FROM user WHERE `id` = '.$session[0].' AND `password` = "'.$session[1].'"');	
			
		if(!$sql->num()) {
			
			self::logout();
			return false;
		
		}
		
		self::loginSession();
		self::$userID = $session[0];
		
		user::getuser($session[0]);
		
		return true;		
		
	}
	
	//Einloggen
	protected static function loginPost() {
		
		$email = type::post('email', 'string');
		$password = type::post('password', 'string');
		
		// Formular ganz abgesendet?
		if(is_null($email) || is_null($password) || $email == '' || $password == '') {
			
			echo message::info('Formular nicht vollständig gesendet!', true);
			self::logout();
			return;
			
		}
		
		$sql = new sql();
		$sql->query('SELECT password, id FROM user WHERE `email` = "'.$email.'"');
		
		// Username mit E-Mail vorhanden?
		if(!$sql->num()) {
		
			echo message::danger('Kein Benutzer mit der E-Mail-Adresse "'.$email.'" gefunden', true);
			self::logout();
			return;
			
		}
				
		$sql->result();
		
		// Password nicht gleich?
		if(!self::checkPassword($password, $sql->get('password'))) {
			
			echo message::danger('Das angebene Passwort ist falsch', true);
			self::logout();
			return;
			
		}
		
		self::loginSession();
		self::$userID = $sql->get('id');
		user::getuser(self::$userID);
		
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
	public static function logout() {   
	
		unset($_SESSION['login']);
		self::$isLogin = false;
		
	}
	
	//status wiedergeben
	public static function isLogged() {
		
		return self::$isLogin;
		
	}
	
	public static function getUser() {
		
		return self::$userID;
		
	}
	

}

?>
