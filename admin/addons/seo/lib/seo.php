<?php

class seo {
	
	public static $currentPage;
	public static $pageId;
	
	/*
	 * Seitentitel ausgeben
	 *
	 * @return string
	 */
	public static function getTitle() {
		
		self::setCurrentPage();
		
		if(self::$currentPage->get('seo_title')) {
			
			$title = self::$currentPage->get('seo_title');
				
		} else {
		
			$title = self::$currentPage->get('name');
			
		}
		
		return $title.' | '.dyn::get('hp_name');		
			
	}
	/*
	 * Keywords ausgeben
	 *
	 * @return string
	 */
	public static function getKeywords() {
		
		self::setCurrentPage();
		
		return self::$currentPage->get('seo_keywords');
		
	}
	
	/*
	 * Beschreibung ausgeben
	 *
	 * @return string
	 */
	public static function getDescription() {
		
		self::setCurrentPage();
		
		return self::$currentPage->get('seo_description');
		
	}
	
	/*
	 * Canonical-URL ausgeben
	 *
	 * @return string
	 */
	public static function getCanonicalUrl() {
		
		new seo_rewrite();
		
		return seo_rewrite::rewriteId(self::$pageId);
		
	}
	
	/*
	 * Head-Teilbereich ausgeben (title, description, keywords, base, canonical)
	 *
	 * @return string
	 */
	public static function getHTML() {
	
		return '<title>'.self::getTitle().'</title>
<meta name="description" content="'.self::getDescription().'">
<meta name="keywords" content="'.self::getKeywords().'">
<base href="'.dyn::get('hp_url').'">
<link rel="canonical" href="'.self::getCanonicalUrl().'">'.PHP_EOL;
		
	}
	
	/*
	 * Artikel-ID für spätere Abfragen setzen
	 *
	 * @param int $id Artikel-Id
	 */
	public static function setPageId($id) {
	
		self::$pageId = $id;
		
	}
	
	/*
	 * Aktuelle Seite in $currentPage setzten, falls noch nicht geschehen
	 *
	 */
	public static function setCurrentPage() {
		
		if(is_null(self::$currentPage)) {
					
			self::$currentPage = page::factory(self::$pageId);	
		
		}
		
	}
	
}

?>