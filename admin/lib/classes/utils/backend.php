<?php

class backend {
	
	static $navi = [];
	static $subnavi = [];
	static $currentPage;
	static $currentSubpage;
	
	public static function addNavi($name, $link, $icon = 'circle', $pos = -1) {
		
		if($pos < 0) {
			$pos = count(self::$navi);
		}
		
		if(!self::$currentPage) {
			
			$page = type::super('page', 'string');
			
			if(strpos($link, 'page='.$page) !== false || (is_null($page) && !count(self::$navi))) {
				self::$currentPage = $name; 	
			}
		}
		
		$list = ['name'=>$name, 'link'=>$link, 'icon'=>$icon];
		array_splice(self::$navi, $pos, 0, [$list]);
		
	}
	
	public static function addSubnavi($name, $link, $icon = 'circle', $pos = -1) {
		
		if($pos < 0) {
			$pos = count(self::$subnavi);
		}
		
		if(!self::$currentSubpage) {
			
			$subpage = type::super('subpage', 'string');
			
			if(strpos($link, 'subpage='.$subpage) !== false || (is_null($subpage) && !count(self::$subnavi))) {
				self::$currentSubpage = $name; 	
			}
		}
		
		$list = ['name'=>$name, 'link'=>$link, 'icon'=>$icon];		
		array_splice(self::$subnavi, $pos, 0, [$list]);
		
	}
	
	public static function getNavi() {
		
		$return = '';	
		
		foreach(self::$navi as $navi) {
			
			if(self::$currentPage == $navi['name']) {
				$class = ' class="active"';
			} else {
				$class = '';	
			}
			
			$return .= '<li'.$class.'><a class="fa fa-'.$navi['icon'].'" href="'.$navi['link'].'" title="'.$navi['name'].'"> <span>'.$navi['name'].'</span></a>';
			
		}
		
		return '<ul>'.$return.'</ul>';
		
	}
	
	public static function getSubnavi() {
		
		$return = '';
		
		foreach(self::$subnavi as $subnavi) {
			
			if(self::$currentSubpage == $subnavi['name']) {
				$class = ' class="active"';
			} else {
				$class = '';	
			}
			
			$return .= '<li'.$class.'><a class="fa fa-'.$subnavi['icon'].'" href="'.$subnavi['link'].'"> <span>'.$subnavi['name'].'</span></a>';
			
		}
		
		return '<ul class="subnav">'.$return.'</ul>';
		
	}
	
	public static function getSubnaviInclude($addon = false) {
		
		$allowPages = [];
		
		$page = preg_match("/page=(\w*)/", self::$subnavi[0]['link'], $output);
		$page = $output[1];
		
		foreach(self::$subnavi as $subnavi) {
			
			preg_match("/subpage=(\w*)/", $subnavi['link'], $output);
			if(isset($output[1])) {
				$allowPages[] = $output[1];	
			}
			
		}
		
		$allowPages = extension::get('page_subpages', $allowPages);
		$subpage = type::super('subpage', 'string', $allowPages[0]);
		
		if(!$addon) {
		
			if(in_array($subpage, $allowPages)) {
		
				return dir::page($page.'.'.$subpage.'.php');
				
			} else {
				
				return dir::page($page.'.'.$allowPages[0].'.php');
				
			}
			
		} else {
			
			if(in_array($subpage, $allowPages)) {
	
				return dir::addon($addon, 'page/'.$page.'.'.$subpage.'.php');
				
			} else {
				
				return dir::addon($addon, 'page/'.$page.'.'.$allowPages[0].'.php');
				
			}
			
		}
		
		
	}
	
	public static function getCurrentPageName($prefix = '') {
		
		if(self::$currentPage) {
			return $prefix.self::$currentPage;
		}
			
	}
	
	public static function getCurrentSubpageName($prefix = '') {
		
		if(self::$currentSubpage) {
			return $prefix.self::$currentSubpage;
		}
			
	}
	
	public static function getTitle() {
		
		return self::getCurrentPageName().self::getCurrentSubpageName(' - ').' - '.dyn::get('hp_name');
		
	}
	
}

?>