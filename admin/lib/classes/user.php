<?php

class user {
	use traitFactory;
	
	protected $userId;
	protected $entrys;

	public function __construct($id = false) {
	
		if(!$id)
			$id = userLogin::getUser();
		
		$this->getEntrys($id);
			
		$this->userId = $id;
		
	}
	
	protected function getEntrys($id) {
	
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('user').' WHERE id='.$id)->result();
		
		$this->entrys = $sql->result;
		
		$perms = $this->entrys['perms'];
		$this->entrys['perms'] = explode('|', $perms);
		
	}
	
	public function get($name) {
		
		return $this->entrys[$name];
		
	}
	
	public function getId() {
	
		return $this->get('id');
		
	}
	
	public function isAdmin() {
	
		return $this->entrys['admin'] == 1;
		
	}
	
	public function hasPerm($perm) {
		
		if($this->isAdmin())
			return true;
		
		return in_array($perm, $this->entrys['perms']);
		
	}
	
	public function getAll() {
	
		return $this->entrys;	
		
	}
	
}

?>