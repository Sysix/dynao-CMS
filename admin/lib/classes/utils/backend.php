<?php

class backend {
	
	public static $navi = array();
	public static $subnavi = array();
	public static $addonNavi = array();
	
	public static $page;
	public static $subpage;
	
	public static $getVars = [];

	/*
	 * Hauptnavigation Methoden
	 */
	public static function addNavi($name, $link, $icon = 'circle', $pos = -1, $callback = null) {
		
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
		
		$item = [
			'name'=>$name,
			'link'=>$link,
			'icon'=>$icon,
			'callback'=>$callback
		];
		
		self::array_insert(self::$navi,	$pos, [$name=>$item]);
		
	}
	
	public static function getNavi() {
		
		return self::generateNavi(self::$navi, self::getPageName(), '', true);
		
	}
	
	public static function setPageName($name) {
		
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
	public static function addSubNavi($name, $link, $pos = -1, $callback = null) {
		
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
		
		$item = [
			'name'=>$name,
			'link'=>$link,
			'callback'=>$callback
		];
		
		self::array_insert(self::$subnavi, $pos, [$name=>$item]);
		
	}
	
	public static function getSubNavi() {
		
		if(count(self::$subnavi) > 1)
		return self::generateNavi(self::$subnavi, self::getSubpageName(), 'subnav', false);
		
	}
	
	public static function setSubpageName($name) {
		
		self::$subpage = $name;
		
	}
	
	public static function getSubpageName($prefix = '') {
		
		if(self::$subpage) {
			return $prefix.self::$subpage;
		}
		
	}
	
	public static function addAddonNavi($name, $link, $icon = 'circle', $pos = -1, $callback = null) {
		
		if(empty(self::$getVars)) {
			self::setGets();	
		}
		
		if($pos < 0) {
			$pos = count(self::$addonNavi);	
		}
		
		$page = self::$getVars[0];
		
		if(strpos($link, 'page='.$page) !== false && !is_null($page)) {
			self::setPageName($name);
		}
		
		$item = [
			'name'=>$name,
			'link'=>$link,
			'icon'=>$icon,
			'callback'=>$callback
		];
	
		self::array_insert(self::$addonNavi, $pos, [$name=>$item]);
	}
	
	public static function getAddonNavi() {
		
		return self::generateNavi(self::$addonNavi, self::getPageName(), '', true);
		
	}
	
	/*
	 * Include der Hauptnavigation
	 */
	 public static function getNaviInclude($addon = false) {
		
		self::setCurrents();
		
		if(isset(self::$navi[self::getPageName()])) {
			$current = self::$navi[self::getPageName()];
		} else {
			$current = self::$addonNavi[self::getPageName()];
		}
		// isset gibt bei null false aus
		if(isset($current['callback']) && is_callable($current['callback'])) {
			return $current['callback']();
		}
		
		$page = self::$getVars[0];
		
		if(!$addon) {
			
			if(file_exists(dir::page($page.'.php'))) {
				return dir::page($page.'.php');
			}
			
		} else {
			
			if(file_exists(dir::addon($addon, 'page'.DIRECTORY_SEPARATOR.$page.'.php'))) {
				return dir::addon($addon, 'page'.DIRECTORY_SEPARATOR.$page.'.php');
			}
			
		}
		
		echo message::danger(lang::get('page_not_found'));
		
		return false;
		 
	 }
	
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
	 * Zusätzliche Funktionen
	 */
	 
	public static function getTitle() {
	
		return self::getSubpageName().self::getPageName(' - ').' - '.dyn::get('hp_name');
		
	}
	 
	public static function setGets() {
		
		$page = type::super('page', 'string');
		$subpage = type::super('subpage', 'string');
		
		self::$getVars = [$page, $subpage];

	}
	
	public static function array_insert(&$array, $position, $insert) {
		$first_array = array_splice($array, 0, $position);
  		$array = array_merge($first_array, $insert, $array);
	}
	
	public static function setCurrents() {
		
		$page = self::$getVars[0];
		$subpage = self::$getVars[1];
		
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
		
	}
	
	public static function generateNavi($naviArray, $name, $Ulclass = '', $main = false) {
	
		$return = [];
		
		foreach($naviArray as $navi) {
			
			$class = '';
			$i_class = '';
			
			$tmp = '';
			
			if($name == $navi['name']) {
				$class = ' class="active"';
			}
			
			$i_tag = (isset($navi['icon'])) ? '<i class="fa fa-'.$navi['icon'].'"></i> ' : '';
			
			$tmp = '<li'.$class.'><a href="'.$navi['link'].'" title="'.htmlspecialchars($navi['name']).'">'.$i_tag.'<span>'.$navi['name'].'</span></a>';

            if($name == $navi['name'] && $main == true) {
                $tmp .= self::getSubNavi();
            }
			
			$tmp .= '</li>';
			
			$return[] = $tmp;
			
		}
		
		if($Ulclass != '') {
			$Ulclass = ' class="'.$Ulclass.'"';	
		}
			
		if($naviArray) {
			return '<ul'.$Ulclass.'>'.implode(PHP_EOL, $return).'</ul>';
		}
		
	}
	
}

?>