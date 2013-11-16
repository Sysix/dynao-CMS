<?php

class slot {
	use traitFactory;
	use traitMeta;
	
	static $slots = [];
	
	protected $sql;
	
	public static function getArray() {
		
		return self::$slots;
		
	}
	
	public static function set($name) {
		
		array_push(self::$slots, $name);
		
	}
	
	//Slots sollen Platzhalter im Template sein, die automatisch im Backend dann aufgelistet werden, diesen Slots kann man dann Inhalt in Form von Modulen zuteilen, um z.B. was im Header zu platizeren oder in der Sidebar, optional können Slots dann auch im Backend erstellt werden - den dazugehörigen Code muss man dann im Template einfügen - wie man diese dann nen Template zuordnet oder ob diese dann nur für jedes Template gelten ... bin mir noch nicht 100% sicher :D was hälts du davon ?
	
}

?>