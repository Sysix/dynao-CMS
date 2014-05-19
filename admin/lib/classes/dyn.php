<?php

class dyn {
	
	static $params = [];
	static $isChange = false;
	
	static $newEntrys = [];

	public function __construct() {
		
		self::$params = json_decode(file_get_contents(dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json')), true);
		
		self::setDebug(self::get('debug'));
		
	}
	
	public static function has($name) {
		
		return isset(self::$params[$name]) || array_key_exists($name, self::$params);
		
	}
	
	public static function get($name, $default = null) {
		
		if(self::has($name)) {
	
			return self::$params[$name];
			
		}
		
		return $default;
			
	}
	
	public static function add($name, $value, $toSave = false) {
	
		self::$params[$name] = $value;
		
		if($toSave) {
			self::$isChange = true;
			self::$newEntrys[$name] = $value;
		}
		
	}
	
	public static function save() {
		
		if(!self::$isChange)
			return true;
			
		$newEntrys = array_merge(self::$params, self::$newEntrys);
			
		return file_put_contents(dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));
		
	}
	
	// Allgemeine Einstellungen	
	static public function setDebug($debug) {
	
		if($debug) {
			
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 1);
			
		} else {
			
			error_reporting(0);
			ini_set('display_errors', 0);
			
		}
		
	}
	
	static public function checkVersion($version1, $version2) {
		
		$version1 = str_replace(' ', '.', $version1);
		$version2 = str_replace(' ', '.', $version2);
		
		if(version_compare($version1, $version2)  > 0)
			return lang::get('version_fail_version');
		else
			return '';
		
	}
	
	static public function checkDynVersion() {
		
		$cacheFile = cache::getFileName(0, 'dynaoVersion');
		
		// jeden Tag
		if(cache::exist($cacheFile, 86400))
			
			$content = json_decode(cache::read($cacheFile), true);
				
		else {
		
			$content = apiserver::getVersionFile();
			
			cache::write($content, $cacheFile);
			
		}
		
		if(is_null($content)) {
			return lang::get('version_fail_connect');
		}
		
		return self::checkVersion($content['current']['version'], dyn::get('version'));
		
	}
	
	static public function getNews() {
		
		$cacheFile = cache::getFileName(0, 'dynaoNews');
		
		// jeden halben Tag
		if(cache::exist($cacheFile, 43200)) {
			
			$content = json_decode(cache::read($cacheFile), true);
				
		} else {
		
			$content = apiserver::getNewsFile();
			
			cache::write($content, $cacheFile);
			
		}
			
		$return = [];
		foreach($content as $news) {
			
			$return[] = '
					<li>
        				<h5>
							<a data-toggle="tooltip" data-placement="right" data-original-title="'.date(lang::get('dateformat'), strtotime($news['time'])).'" href="'.$news['link'].'">'.$news['title'].'</a>
						</h5>						
        				<p>'.$news['content'].'</p>						
        			</li>';			
		}
		
		return implode(PHP_EOL, $return);
		
	}
	
	static public function getAddons() {
		
		$cacheFile = cache::getFileName(0, 'dynaoAddons');
		
		// jeden halben Tag
		if(cache::exist($cacheFile, 43200)) {
			
			$content = json_decode(cache::read($cacheFile), true);
				
		} else {
		
			$content = apiserver::getAddonFile();
			
			cache::write($content, $cacheFile);
			
		}
		
		$table = table::factory(['class'=> ['table', 'table-spriped', 'table-hover']]);
				
		$table->addCollsLayout('60, 140, *, 110');
		
		$table->addRow()
		->addCell(lang::get('vote'))
		->addCell(lang::get('name'))
		->addCell(lang::get('description'))
		->addCell();
		
		$table->addSection('tbody');
		
		if(is_null($content)) {
			
			$table->addRow()
			->addCell(lang::get('no_entries'), ['colspan'=>4]);
			
		} else {
		
			foreach($content as $addon) {
				
				$perc = round($addon['rate_sum'] / $addon['rate_ppl'] * 10);
				
				if($perc < 33)
					$class = 'danger';
				elseif($perc < 66)
					$class = 'warning';
				else
					$class = 'success';
				
				$table->addRow()
				->addCell('<span class="label label-'.$class.'">'.$perc.'%</span>')
				->addCell($addon['title'])
				->addCell($addon['description'])
				->addCell('<a href="'.$addon['link'].'" target="_blank" class="btn btn-sm btn-default">'.lang::get('download').'</a>');
						
			}
		
		}
		
		return $table->show();
		
	}
	
}

?>