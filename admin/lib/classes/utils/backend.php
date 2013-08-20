<?php

class backend {
	
	static $subnavi = array();
	
	public static function addSubnavi($name, $link, $icon = 'circle-blank') {
		
		self::$subnavi[] = array('name'=>$name, 'link'=>$link, 'icon'=>$icon);
		
	}
	
	public static function getSubnavi() {
		
		$return = '';
		
		$first_active = (!type::super('subpage', 'bool'));
		
		foreach(self::$subnavi as $subnavi) {
			if((type::super('subpage', 'string', '') && strpos($subnavi['link'], 'subpage='.type::super('subpage', 'string', '')) !== false) || $first_active) {
				$class = ' class="active"';
				$first_active = false;
			} else {
				$class = '';	
			}
			
			$return .= '<li'.$class.'><a class="icon-'.$subnavi['icon'].'" href="'.$subnavi['link'].'"> '.$subnavi['name'].'</a>';
			
		}
		
		return '<ul class="subnav">'.$return.'</ul>';
		
	}
	
}

?>