<?php

class addonNeed {
	use traitMeta;
	
	public static function check($name, $value) {
		
		$method = 'check'.ucfirst($name);
		
		if(!method_exists(get_called_class(), $method)) {
			throw new Exception(__CLASS__.'::check konnte nicht '.$name.' überprüfen');
		}
		
		return self::$method($value);	
		
	}
	
	public static function checkVersion($version) {
		
		if(dyn::get('version') >= $version) {
			return true;
		}
		
		return 'Die Aktuelle Version ist '.dyn::get('version').' das Addon braucht Version '.$version;
		
	}
	
	public static function checkPerm($perm) {
		
		if(dyn::get('user')->hasPerm($perm)) {
			return true;
		}
		
		return 'Sie haben kein Zugriff auf diesen Bereich';
		
	}
	
	public static function checkAddon($addons) {
		
		$return = '';
		
		foreach($addons as $name=>$version) {
			
			if(is_int($name)) {
				$name = $version;
				$version = false;
			}
		
			$config = addonConfig::getConfig($name);
			
			// Nicht installiert
			if(!is_array($config)) {
				$return .= 'Das Addon '.$name.' wurde nicht gefunden<br />';
				continue;
			}
			
			if(!addonConfig::isActive($name)) {				
				$return .= 'Das Addon '.$name.' muss installiert und aktiviert werden<br />';
				continue;			
			}
			
			if($version && $config['version'] < $version) {
				$return .=	'Das Addon '.$name.' muss mindestens in Version '.$version.' vorhanden sein<br />';
				continue;
			}
			
		}
		
		if($return == '') {
			return true;
		} else {
			return $return;	
		}
			
	}
	
}

?>