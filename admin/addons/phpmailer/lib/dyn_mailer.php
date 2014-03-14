<?php

class dyn_mailer extends PHPMailer {
	
	public function __construct($exceptions = false) {
	
		$config = self::loadConfig();
		
		$this->From = $config['from'];
		$this->FromName = $config['fromName'];
		$this->ConfirmReadingTo = $config['confirmReading'];
		$this->AdminBcc = $config['bbc'];
		$this->CharSet = 'utf-8';
		
		parent::__construct($exceptions);
		
	}
	
	public static function loadConfig() {
		
		$config = json_decode(file_get_contents(dir::addon('phpmailer', 'config.json')), true);
		
		return $config['settings'];		
		
	}
	
	public static function saveConfig($from, $fromName, $confirmReading, $bbc) {
		
		$config = json_decode(file_get_contents(dir::addon('phpmailer', 'config.json')), true);
		$config['settings']['from'] = $from;
		$config['settings']['fromName'] = $fromName;
		$config['settings']['confirmReading'] = $confirmReading;
		$config['settings']['bbc'] = $bbc;
		
		return file_put_contents(dir::addon('phpmailer', 'config.json'),  json_encode($config, JSON_PRETTY_PRINT));
		
	}
	
}

?>