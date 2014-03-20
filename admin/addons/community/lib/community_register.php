<?php

class community_register {

	public static function sendActivationMail() {
		
		
	}
	
	public static function sendLostPasswordMail($email) {
	
		$sql = sql::factory();
		$sql->query('SELECT id FROM '.sql::table('community_user').' WHERE `email` = '.$sql->escape($email));
		
		if(!$sql->num()) {
			return 'Keinen User mit der E-Mail-Adresse vorhanden';	
		}
		
		$content = file_get_contents(dir::addon('community', 'lostpasswordemail.txt'));
		
		$key = self::generateString(16);
		$url = dyn::get('hp_url').'index.php?lostpassword='.$key;
		
		$content = str_replace('{{url}}', $url, $content);
		
		// Mailsend
		
		$sql->setTable('community_user');
		$sql->setWhere('id='.$sql->get('id'));
		$sql->addPost('lostpw_key', $key);
		$sql->update();
		
	}
	
	private static function generateString($lenght) {
		
		$allowed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		$string_length = strlen($allowed)-1;
		$str = '';
		for($i = 0; $i < $length; $i++) {
				$str .= $chars[rand(0, $string_length)];
		}
		
		return $str;
		
	}
	
	public static function registerUser() {
	
		$sql = sql::factory();
		$sql->setTable('community_user');
		$sql->getPosts([
			'username'=>'string',
			'password'=>'string',
			'email'=>'string'
		]);
		
		$validator = new validator();
		
		$email = $sql->getPost('email');
		$username = $sql->getPost('username');
		$password = $sql->getPost('password');	
		
		$validUsername = $validator->costum($username, function() {
			preg_match('/\w{4,}/', $username, $match);
			return $match[0] == $username;
		});
		
		if(!$validUsername) {
			return 'Username darf nur aus Buchstaben Zahlen und Unterstrich bestehen und muss mindestens 4 Zeichen lang sein.';
		}
		
		if($sql->num('SELECT id FROM '.sql::table('community_user').' WHERE `username`= "'.$sql->escape($username).'"')) {
			return 'Benutzername schon vorhanden';
		}
		
		if(!$validator->email($email)) {
			return 'Bitte geben Sie eine E-Mail Adresse an';
		}
		
		$salt = userLogin::generateSalt();
		
		$sql->addDatePost('registerdate', 'now');		
		$sql->addPost('salt', $salt);
		
		extension::get('COMMUNITY_USER_REGISTER', $sql);
		
		$password = userLogin::hash($password, $salt);
		
		$sql->addPost('password', $password);		
		$sql->save();
		
		//Mail send
		
		return true;
		
	}
	


?>