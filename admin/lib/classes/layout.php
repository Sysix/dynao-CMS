<?php

class layout {
	
	static $jsFiles = array();
	
	static $cssFiles = array();
				
	static $jsCode = array('jquery'=>array(), 'all'=>array());
	
	public static function addJs($js_file, $attributes = array()) {
		
		
		$attributes['src'] = $js_file;
		
		self::$jsFiles[] = $attributes;
			
	}
	
	public static function addCss($css_file, $media = 'screen', $attributes = array()) {
		
		if(!isset($attributes['rel']))
			$attributes['rel'] = 'stylesheet';
		
		$attributes['href'] = $css_file;
		$attributes['media'] = $media;
		
		self::$cssFiles[] = $attributes;
		
	}
	
	public static function addJsCode($code, $jquery = true) {
	
		$assoz = ($jquery) ? 'jquery' : 'all';
		
		self::$jsCode[$assoz][] = $code;
		
	}
	
	public static function getCSS() {
		
		$return = '';
		
		foreach(self::$cssFiles as $css) {						
				
			$return .= '<link'.self::convertAttr($css).'>'.PHP_EOL;
			
		}
		
		return $return;
		
	}
	
	public static function getJS() {
		
		$return = '';
		
		foreach(self::$jsFiles as $css) {						
				
			$return .= '<script'.self::convertAttr($css).'></script>'.PHP_EOL;
			
		}
		
		$return .= self::getJSCode();
		
		return $return;		
		
	}
	
	public static function getJSCode() {
		
		$return = '<script>';
		$return .= '$(document).ready(function() {';
		
		foreach(self::$jsCode['jquery'] as $code) {
			
			$return .= $code;
			
		}
		
		$return .= '});';
		
		foreach(self::$jsCode['all'] as $code) {
			
			$return .= $code;
			
		}
		
		$return .= '</script>';
		
		return $return;
		
	}
	
	
	protected static function convertAttr($attr) {
		
		return html_convertAttribute($attr);
		
	}
	
}

?>