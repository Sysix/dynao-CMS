<?php

class template {

	static $jsFiles = [];	
	static $cssFiles = [];
				
	static $jsCode = [
		'jquery'=>[],
		'all'=>[]
	];
	
	/**
	 * Eine Javascript Datei einbinden
	 *
	 * @param	string	$js_file		Pfad der Datei
	 * @param	array	$attributes		HTML Attribute
	 *
	 */
	public static function addJs($js_file, $attributes = []) {
		
		
		$attributes['src'] = $js_file;
		
		self::$jsFiles[] = $attributes;
			
	}
	
	/**
	 * Eine CSS Datei einbinden
	 *
	 * @param string	$css_file		Pfad der Datei
	 * @param string	$medi			HTML Attribut Media
	 * @param array		$attributes		HTML Attribute
	 *
	 */
	public static function addCss($css_file, $media = 'screen', $attributes = []) {
		
		if(!isset($attributes['rel']))
			$attributes['rel'] = 'stylesheet';
		
		$attributes['href'] = $css_file;
		$attributes['media'] = $media;
		
		self::$cssFiles[] = $attributes;
		
	}
	
	/**
	 * JS Code einbinden
	 *
	 * @param string	$code			Der Code
	 * @param bool		$jquery			Im Jquery document ready laden oder nicht
	 *
	 */
	public static function addJsCode($code, $jquery = true) {
	
		$assoz = ($jquery) ? 'jquery' : 'all';
		
		self::$jsCode[$assoz][] = $code;
		
	}
	
	/**
	 * Alle CSS Daten ausgeben
	 *
	 * @return string
	 *
	 */
	public static function getCSS() {
		
		$return = '';
		
		foreach(self::$cssFiles as $css) {						
				
			$return .= '<link'.self::convertAttr($css).'>'.PHP_EOL;
			
		}
		
		return $return;
		
	}
	
	/**
	 * Alle JS Daten ausgeben
	 *
	 * @return string
	 *
	 */
	public static function getJS() {
		
		$return = '';
		
		foreach(self::$jsFiles as $css) {						
				
			$return .= '<script'.self::convertAttr($css).'></script>'.PHP_EOL;
			
		}
		
		$return .= self::getJSCode();
		
		return $return;		
		
	}
	
	/**
	 * Alle JS Codes ausgeben
	 *
	 * @return	string
	 *
	 */
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
	
	/**
	 * Attribute in HTML Attribute Konvetieren
	 *
	 * @param	array	$attr			Die Attribute
	 * @return	string
	 *
	 */
	protected static function convertAttr($attr) {
		
		return html_convertAttribute($attr);
		
	}
	
}

?>
