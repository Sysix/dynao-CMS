<?php

class backend {
	
	static $navi = array();
	static $subnavi = array();
	
	public static function addNavi($name, $link, $icon = 'circle', $pos = -1) {
		
		if($pos < 0) {
			$pos = count(self::$navi);
		}
		
		$list = array('name'=>$name, 'link'=>$link, 'icon'=>$icon);
		array_splice(self::$navi, $pos, 0, array($list));
		
	}
	
	public static function addSubnavi($name, $link, $icon = 'circle', $pos = -1) {
		
		if($pos < 0) {
			$pos = count(self::$subnavi);
		}
		
		$list = array('name'=>$name, 'link'=>$link, 'icon'=>$icon);		
		array_splice(self::$subnavi, $pos, 0, array($list));
		
	}
	
	public static function getNavi() {
		
		$return = '';
		
		$first_active = (!type::super('page', 'bool'));
		$page = type::super('page', 'string', '');
		
		foreach(self::$navi as $navi) {
			
			if(($page && strpos($navi['link'], 'page='.$page) !== false) || $first_active) {
				$class = ' class="active"';
				$first_active = false;
			} else {
				$class = '';	
			}
			
			$return .= '<li'.$class.'><a class="fa fa-'.$navi['icon'].'" href="'.$navi['link'].'"> <span>'.$navi['name'].'</span></a>';
			
		}
		
		return '<ul>'.$return.'</ul>';
		
	}
	
	public static function getSubnavi() {
		
		$return = '';
		
		$first_active = (!type::super('subpage', 'bool'));
		$subpage = type::super('subpage', 'string', '');
		
		foreach(self::$subnavi as $subnavi) {
			
			if(($subpage && strpos($subnavi['link'], 'subpage='.$subpage) !== false) || $first_active) {
				$class = ' class="active"';
				$first_active = false;
			} else {
				$class = '';	
			}
			
			$return .= '<li'.$class.'><a class="fa fa-'.$subnavi['icon'].'" href="'.$subnavi['link'].'"> <span>'.$subnavi['name'].'</span></a>';
			
		}
		
		return '<ul class="subnav">'.$return.'</ul>';
		
	}
	
}

?>