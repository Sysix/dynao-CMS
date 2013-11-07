<?php

class user {
	use traitFactory;
	
	protected static $userID;
	protected static $entrys;

	public static function getUser($id = false) {
	
		if(!$id)
			$id = userLogin::getUser();
			
		if(self::$userID != $id)
			self::getEntrys($id);
			
		self::$userID = $id;
			
		return user::factory();
		
	}
	
	protected static function getEntrys($id) {
	
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('user').' WHERE id='.$id)->result();
		
		self::$entrys = $sql->result;
		
		$perms = self::$entrys['perms'];
		self::$entrys['perms'] = explode('|', $perms);
		
	}
	
	public function get($name) {
		
		return self::$entrys[$name];
		
	}
	
	public function getID() {
	
		return $this->get('id');
		
	}
	
	public function isAdmin() {
	
		return isset(self::$entrys['perms']['admin[page]']);
		
	}
	
	public function hasPerm($perm) {
		
		if($this->isAdmin())
			return true;
			
		return isset(self::$entrys['perms'][$perm]);			
		
	}
	
	public static function getAll() {
	
		return self::$entrys;	
		
	}
	
}

?>