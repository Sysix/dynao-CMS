<?php

class metainfos {
	
	static $multiTypes = ['select', 'radio', 'checkbox'];
	
	public static function getMetaInfos($form, $type) {
		
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('metainfos').' WHERE `type` = "'.$type.'" ORDER BY `sort`')->result();
		while($sql->isNext()) {
			
			$element = self::getElement($sql->getRow(), $form->get($sql->get('name')));
			$form->addElement($sql->get('name'), $element);
			
			$sql->next();	
		}
		
		return $form;
		
	}
	
	public static function getElement($attributes, $default = null) {
		
		if(is_null($default)) {
			$default = $attributes['default'];	
		}
		
		$class = self::getElementClass($attributes, $default);
		$class->fieldName($attributes['label']);
		$class = self::convertAttributes($class, $attributes['attributes']);
		
		if(in_array($attributes['formtype'], self::$multiTypes)) {
			$class = self::convertParams($class, $attributes['params']);
		}
		
		return $class;
		
	}
	
	protected static function getElementClass($attributes, $default) {
		
		if($attributes['formtype'] == 'text') {
			$class = formInput::factory($attributes['name'], $default);
			$class->addAttribute('type', 'text');
			return $class;
		}
		
		// formSelect, formCheckbox, formTextarea, ..
		$class = 'form'.ucfirst($attributes['formtype']);
		
		return new $class($attributes['name'], $default);
		
	}
	
	protected static function convertAttributes($element, $attributes) {
		
		if(trim($attributes) == '')
			return $element;
			
		// Serverseitig
		$attributes = str_replace("\n\r", "\n", $attributes);
		
		$attr = explode("\n", $attributes);
		foreach($attr as $attrString) {
			
			preg_match("/([^=]*)=(.*)/", $attrString, $attrArray);	
			$element->addAttribute($attrArray[1], $attrArray[2]);
			
		}
		
		return $element;
		
	}
	
	protected static function convertParams($element, $params) {
		
		if(trim($params) == '')
			return $element;
			
		$params = explode('|', $params);
		foreach($params as $paramString) {
			
			preg_match('/([^:]*):(\w*)/', $paramString, $parmArray);
			
			if(empty($parmArray)) {
				$element->add($paramString, $paramString);	
			} else {
				$element->add($parmArray[1], $parmArray[2]);
			}
			
		}
		
		return $element;
			
	}
	
}

?>