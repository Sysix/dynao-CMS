<?php

class addonConfig {

    const TYPE = 'addons';
	
	static $all = [];
	static $allConfig = [];
	
	public static function isSaved($addon, $plugin = null, $save = true) {

        $sql = sql::factory();

        $where = '';
        if($plugin) {
            $where = ' AND plugin = "'.$sql->escape($plugin).'"';
        }

		$num = $sql->num('SELECT 1 FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'" '.$where);
		if(!$num && $save) {
			$save = sql::factory();
			$save->setTable('addons');
			$save->addPost('name', $addon);

            if($plugin) {
                $save->addPost('plugin', $plugin);
            }

			$save->save();
		}
		
		return $num;
		
	}
	
	public static function getAll() {

		if(!count(static::$all)) {

			$sql = sql::factory();		
			$sql->query('SELECT name FROM '.sql::table('addons').' WHERE `install` = 1  AND `active` = 1 AND `plugin` = ""')->result();
            while($sql->isNext()) {
				static::$all[dir::addon($sql->get('name'))] = $sql->get('name');
				$sql->next();		
			}
			
		}

		return static::$all;

	}
	
	public static function includeAllLibs() {
		foreach(static::getAll() as $dir=>$name) {
			self::includeLibs($dir);
		}
	}

	public static function includeLibs($dir) {
		$vendor = $dir.'vendor'.DIRECTORY_SEPARATOR;
		if(file_exists($vendor)) {
			autoload::addDir($vendor);
		}

		$lib = $dir.'lib'.DIRECTORY_SEPARATOR;
		if(file_exists($lib)) {
			autoload::addDir($lib);
		}
	}
	
	public static function includeAllConfig() {
		
		$return = [];
		foreach(static::getAll() as $dir=>$name) {
			$return[] = $dir.'config.php';
		}
		return $return;
		
	}
	
	public static function includeAllLangFiles() {
		
		foreach(static::getAll() as $dir=>$name) {
			self::includeLangFiles($dir);
		}
	}

	public static function includeLangFiles($dir) {

		$file = $dir.'lang'.DIRECTORY_SEPARATOR.lang::getLang().'.json';
		if(file_exists($file)) {
			lang::loadLang($file);
		}

		$defaultFile = $dir.'lang'.DIRECTORY_SEPARATOR.lang::getDefaultLang().'.json';
		if(file_exists($defaultFile)) {
			lang::loadLang($defaultFile, true);
		}

	}

	
	public static function loadAllConfig($toName = self::TYPE) {

		foreach(static::getAll() as $dir=>$name) {
			self::loadConfig($name, $dir);
		}
		
		return true;
	}

	public static function loadConfig($name, $dir) {
		$addons = dyn::get(self::TYPE);

		if(file_exists($dir.'config.json')) {
			$addons[$name] = json_decode(file_get_contents($dir.'config.json'), true);
		}

		dyn::add(self::TYPE, $addons);
	}
	
	public static function isActive($name) {
		
		return in_array($name, static::$all);
	}
	
}

?>
