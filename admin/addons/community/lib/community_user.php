<?php

class community_user {
	
	public $sql;
	
	public function __construct($id) {
		
		$this->sql = sql::factory();
		$this->sql->query('SELECT * FROM '.sql::table('community_user').' WHERE id = '.$id)->result();
		
	}
	
	public function get($name, $default = null) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function isAdmin() {
		
		return $this->get('admin', 0) == 1;
		
	}
	
	public static function checkSession() {
		
		$session = type::session('community-login', 'int', 0);
		
		if(!$session) {
			return false;
		}
		
		dyn::add('community_user', new community_user($session));
		
		return true;
		
	}
	
	public static function checkLogin() {
		
		$username = type::post('username', 'string', '');
		$password = type::post('password', 'string', '');
		
		if($username == '' ||$password == '') {
			
			echo message::info(lang::get('login_form_notfull'), true);
			return;
				
		}
		
		$sql = sql::factory();
		$sql->query('SELECT password, salt, id FROM '.sql::table('community_user').' WHERE `username` = "'.$sql->escape($username).'"');
		if(!$sql->num()) {
			
			echo message::danger(sprintf(lang::get('login_no_user'), $email), true);
			return;
			
		}
		
		$sql->result();
		
		if(!userLogin::checkPassword($password, $sql->get('salt'), $sql->get('password'))) {
			
			echo message::danger(lang::get('login_pwd_false'), true);
			return;	
			
		}
		
		$_SESSION['community-login'] = $sql->get('id');
		
		self::checkSession();
		
		// Für spätere Foren-Bridges
		extension::get('COMMUNITY_USER_LOGIN', $password);
		
	}
	
}

?>