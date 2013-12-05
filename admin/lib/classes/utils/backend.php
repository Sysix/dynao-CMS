<?php

class backend {
	
	public static $navi = array();
	public static $subnavi = array();
	public static $secondnavi = array();
	
<<<<<<< HEAD
	public static function addNavi($name, $link, $icon = 'circle', $pos = -1, $file = false) {
=======
	public static $page;
	public static $subpage;
	public static $secondpage;
	
	public static $getVars = [];

	/*
	 * Hauptnavigation Methoden
	 */
	public static function addNavi($name, $link, $icon = 'circle', $pos = -1, $callback = null) {
>>>>>>> update backend class
		
		if(empty(self::$getVars)) {
			self::setGets();	
		}
		
		if($pos < 0) {
			$pos = count(self::$navi);	
		}
		
		$page = self::$getVars[0];
		
		if(strpos($link, 'page='.$page) !== false && !is_null($page)) {
			self::setPageName($name);
		}
		
<<<<<<< HEAD
		$addon = self::isAddon();
		
		$list = ['name'=>$name, 'link'=>$link, 'icon'=>$icon, 'addon'=>$addon];
		array_splice(self::$navi, $pos, 0, [$list]);
		
	}
	
	protected static function isAddon() {
		
		$addon = debug_backtrace();
		$addon = $addon[1]['file'];
		$addon = explode(DIRECTORY_SEPARATOR, $addon);
		if(in_array("addons", $addon))
			return true;
		else
			return false;
		
	}
	
	public static function addSubnavi($name, $link, $icon = 'circle', $pos = -1, $file = false) {
=======
		$item = [
			'name'=>$name,
			'link'=>$link,
			'icon'=>$icon,
			'callback'=>$callback
		];
		
		self::array_insert(self::$navi,	$pos, [$name=>$item]);
		
		
	}
	
	public static function getNavi() {
		
		return self::generateNavi(self::$navi, self::getPageName());
		
	}
	
	public static function setPageName($name) {
>>>>>>> update backend class
		
		self::$page = $name;
		
	}
	
	public static function getPageName($prefix = '') {
		
		if(self::$page) {
			return $prefix.self::$page;
		}
		
	}
	
	
	/*
	 * Subnavigation Methoden
	 */
	public static function addSubNavi($name, $link, $icon = 'circle', $pos = -1, $callback = null) {
		
		if(empty(self::$getVars)) {
			self::setGets();	
		}
		
		if($pos < 0) {
			$pos = count(self::$subnavi);	
		}
		
		$subpage = self::$getVars[1];
		
		if(strpos($link, 'subpage='.$subpage) !== false && !is_null($subpage)) {
			self::setSubpageName($name);
		}
		
<<<<<<< HEAD
		$addon = self::isAddon();
		
		$list = ['name'=>$name, 'link'=>$link, 'icon'=>$icon, 'addon'=>$addon];		
		array_splice(self::$subnavi, $pos, 0, [$list]);
		
	}
	
	public static function addSecondnavi($name, $link, $pos = -1, $file = false) {
=======
		$item = [
			'name'=>$name,
			'link'=>$link,
			'icon'=>$icon,
			'callback'=>$callback
		];
		
		self::array_insert(self::$subnavi, $pos, [$name=>$item]);
		
	}
	
	public static function getSubNavi() {
>>>>>>> update backend class
		
		return self::generateNavi(self::$subnavi, self::getSubpageName(), 'subnav', self::getSecondNavi());
		
	}
	
	public static function setSubpageName($name) {
		
		self::$subpage = $name;
		
	}
	
	public static function getSubpageName($prefix = '') {
		
		if(self::$subpage) {
			return $prefix.self::$subpage;
		}
		
	}
	
	/*
	 * Subnavigation Methoden
	 */
	public static function addSecondNavi($name, $link, $pos = -1, $callback = null) {
		
		if(empty(self::$getVars)) {
			self::setGets();	
		}
		
		if($pos < 0) {
			$pos = count(self::$secondnavi);	
		}
		
		$secondpage = self::$getVars[2];
		
		if(strpos($link, 'secondpage='.$secondpage) !== false && !is_null($secondpage)) {
			self::setSecondpageName($name);
		}
		
<<<<<<< HEAD
		$addon = self::isAddon();
		
		$list = ['name'=>$name, 'link'=>$link, 'parent'=>self::$currentSublink, 'addon'=>$addon];		
		array_splice(self::$secondnavi, $pos, 0, [$list]);
=======
		$item = [
			'name'=>$name,
			'link'=>$link,
			'callback'=>$callback
		];
		
		self::array_insert(self::$secondnavi, $pos, [$name=>$item]);
>>>>>>> update backend class
		
	}
	
	public static function getSecondNavi() {
		
		return self::generateNavi(self::$secondnavi, self::getSecondpageName());
		
	}
	
	public static function setSecondpageName($name) {
		
		self::$secondpage = $name;
		
	}
	
	public static function getSecondpageName($prefix = '') {
		
		if(self::$secondpage) {
			return $prefix.self::$secondpage;
		}
		
	}
	
	/*
	 * Include der Subnavigation
	 */
	 /*
	 public static function getNaviInclude($addon = false) {
		
		self::setCurrents();
		
		$page = self::$getVars[0];
		
		$current = self::$navi[self::getPageName()];
		// isset gibts bei null false aus
		if(isset($current['callback']) && is_callable($current['callback'])) {
			return $current['callback']();
		}
		
		if(!$addon) {
			return dir::page($page.'.php');
		} else {
			return dir::addon($addon, 'page/'.$page.'.php');
		}
		 
	 }
	 */
	
	/*
	 * Include der Subnavigation
	 */
	 public static function getSubnaviInclude($addon = false) {
		
		self::setCurrents();
		
		$page = self::$getVars[0];
		$subpage = self::$getVars[1];
		
		$current = self::$subnavi[self::getSubpageName()];
		// isset gibts bei null false aus
		if(isset($current['callback']) && is_callable($current['callback'])) {
			return $current['callback']();
		}
		
		if(!$addon) {
			return dir::page($page.'.'.$subpage.'.php');
		} else {
			return dir::addon($addon, 'page/'.$page.'.'.$subpage.'.php');
		}
		 
	 }
	 
	 /*
	 * Include der Subnavigation
	 */
	 public static function getSecondnaviInclude($addon = false) {
		
		self::setCurrents();
		
		$page = self::$getVars[0];
		$subpage = self::$getVars[1];
		$secondpage = self::$getVars[2];
		
		$current = self::$secondnavi[self::getSecondpageName()];
		
		// isset gibts bei null false aus
		if(isset($current['callback']) && is_callable($current['callback'])) {
			return $current['callback']();
		}
		
		if(!$addon) {
			return dir::page($page.'.'.$subpage.'.'.$secondpage.'.php');
		} else {
			return dir::addon($addon, 'page/'.$page.'.'.$subpage.'.'.$secondpage.'.php');
		}
		 
	 }
	
	/*
	 * Zusätzliche Funktionen
	 */
	 
	public static function getTitle() {
	
		return self::getSubpageName().self::getPageName(' - ').' - '.dyn::get('hp_name');
		
<<<<<<< HEAD
=======
	}
	 
	public static function setGets() {
		
		$page = type::super('page', 'string');
		$subpage = type::super('subpage', 'string');
		$secondpage = type::super('secondpage', 'string');
		
		self::$getVars = [$page, $subpage, $secondpage];
		
>>>>>>> update backend class
	}
	
	public static function array_insert(&$array, $position, $insert) {
		$first_array = array_splice($array, 0, $position);
  		$array = array_merge($first_array, $insert, $array); 
	}
	
	public static function setCurrents() {
		
		$page = self::$getVars[0];
		$subpage = self::$getVars[1];
		$secondpage = self::$getVars[2];
		
		if(is_null($page) && reset(self::$navi)) {
			$navi = current(self::$navi);
			self::setPageName($navi['name']);
			preg_match("/page=(\w*)/", $navi['link'], $output);
			self::$getVars[0] = $output[1];
		}
		
		if(is_null($subpage) && reset(self::$subnavi)) {
			$subnavi = current(self::$subnavi);
			self::setSubpageName($subnavi['name']);
			preg_match("/subpage=(\w*)/", $subnavi['link'], $output);
			self::$getVars[1] = $output[1];
		}
		
		if(is_null($secondpage) && reset(self::$secondnavi)) {
			$secondnavi = current(self::$secondnavi);
			self::setSecondpageName($secondnavi['name']);
			preg_match("/secondpage=(\w*)/", $secondnavi['link'], $output);
			self::$getVars[2] = $output[1];
		}
		
	}
	
	public static function generateNavi($naviArray, $name, $Ulclass = '', $extras = '') {
	
		$return = [];
		
		foreach($naviArray as $navi) {
			
			$class = '';
			$a_class = '';
			
			if($name == $navi['name']) {
				$class = ' class="active"';
			}
			
			if(isset($navi['icon'])) {
				$a_class = ' class="fa fa-'.$navi['icon'].'"';
			}
			
			$return[] = '<li'.$class.'><a'.$a_class.' href="'.$navi['link'].'" title="'.htmlspecialchars($navi['name']).'"> <span>'.$navi['name'].'</span></a>';
			
		}
		
		if($Ulclass != '') {
			$Ulclass = ' class="'.$Ulclass.'"';	
		}
			
		
		return '<ul'.$Ulclass.'>'.implode(PHP_EOL, $return).$extras.'</ul>';
		
	}
	
}

?>