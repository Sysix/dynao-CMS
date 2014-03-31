<?php

class userLogin {

	static protected $isLogin = false;
	static protected $userID = null;
	
	const SALT_LENGTH = 6;
	const HASH_TYPE = 'sha256';
	
	public function __construct() {
	
		if(!is_null(type::get('logout', 'string'))) {
	
			self::logout();
			   
		} elseif(self::checkLogin()) {
	
			self::loginSession();
	
		} elseif (!is_null(type::post('login', 'string'))) {
	
			self::loginPost();
	
		}
	
	}
	
	/*
	 * Login auf true setzten
	 */
	protected static function loginSession() {
	
		self::$isLogin = true;
	
	}
	
	/*
	 * Überprüfen ob eine Session vorhanden
	 *
	 * @return bool;
	 */
	protected static function checkLogin() {
	
		$session = type::session('login', 'int', 0);
		
		if(!$session)
			return false;
		
		self::loginSession();
		self::$userID = $session;
		
		return true;		
		
	}
	
	/*
	 * Login-Formular überprüfen ob in der Datenbank was vorhanden ist
	 */
	protected static function loginPost() {
		
		$email = type::post('email', 'string');
		$password = type::post('password', 'string');
		
		// Formular ganz abgesendet?
		if(is_null($email) || is_null($password) || $email == '' || $password == '') {
			
			echo message::info(lang::get('login_form_notfull'), true);
			return;
			
		}
		
		$sql = sql::factory();
		$sql->query('SELECT password, salt, id FROM '.sql::table('user').' WHERE `email` = "'.$sql->escape($email).'"');
		
		// Username mit E-Mail vorhanden?
		if(!$sql->num()) {
		
			echo message::danger(sprintf(lang::get('login_no_user'), htmlspecialchars($email)), true);
			return;
			
		}
				
		$sql->result();
		
		// Password nicht gleich?
		if(!self::checkPassword($password, $sql->get('salt'), $sql->get('password'))) {
			
			echo message::danger(lang::get('login_pwd_false'), true);
			return;
			
		}
		
		self::loginSession();
		self::$userID = $sql->get('id');
		
		$_SESSION['login'] = $sql->get('id');
		
		// Falls alte Methode (sha1) neuen Salt generieren und salt updaten
		// sha1 deprecated 0.2 Beta
		$salt = $sql->get('salt');
		if(empty($salt)) {			
			
			$salt = self::generateSalt();
			
			$sql->setTable('user');
			$sql->setWhere('`email` = "'.$email.'"');
			$sql->addPost('salt', $salt);
			$sql->addPost('password', self::hash($password, $salt));
			$sql->update();
			
		}
	
	}
	
	/*
	 * Passworten hashen mit sha256, falls vorhanden, ansonsten sha1
	 *
	 * @param string $password Das Passwort
	 * @param string $salt Der Salt
	 * @return string
	 */
	public static function hash($password, $salt) {
		
		if(empty($salt)) {
			return sha1($password);	
		}
		
		return hash(self::HASH_TYPE, $salt.$password.$salt);
		
	}
	
	/*
	 * Überprüfen ob Password gleich den Hash entspricht
	 *
	 * @param string $password Das Passwort
	 * @param string $salt Der Salt
	 * @param string $hash Das bereits gehashte Passwort
	 * @return bool
	 */
	public static function checkPassword($password, $salt, $hash) {
	
		return self::hash($password, $salt) == $hash;
	
	}
	
	/*
	 * einen Salt generieren
	 *
	 * @return string
	 */
	public static function generateSalt() {
		
		$allowed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$lenght = strlen($allowed)-1;
		$str = '';
		for($i = 0; $i < self::SALT_LENGTH; $i++) {
				$str .= $allowed[rand(0, $lenght)];
		}
		
		return $str;
		
	}
	
	/*
	 * Ausloggen
	 */
	public static function logout() {   
	
		unset($_SESSION['login']);
		self::$isLogin = false;
		echo message::info(lang::get('login_logout_success'), true);
		
	}
	
	/*
	 * Abfragen ob man eingeloggt ist
	 *
	 * @return bool
	 */
	public static function isLogged() {
		
		return self::$isLogin;
		
	}
	
	/*
	 * Rückgabe der userID
	 *
	 * @return int/null
	 */
	public static function getUser() {
		
		return self::$userID;
		
	}
	

}

?>
