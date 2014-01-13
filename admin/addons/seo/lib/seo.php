<?php

class seo {
	
	public static $currentPage;
	public static $pageId;
	
	public static function getTitle() {
		
		self::setCurrentPage();
		
		if(self::$currentPage->get('seo_title')) {
			
			$title = self::$currentPage->get('seo_title');
				
		} else {
		
			$title = self::$currentPage->get('name');
			
		}
		
		return $title.' | '.dyn::get('hp_name');		
			
	}
	
	public static function getKeywords() {
		
		self::setCurrentPage();
		
		return self::$currentPage->get('seo_keywords');
		
	}
	
	public static function getDescription() {
		
		self::setCurrentPage();
		
		return self::$currentPage->get('seo_description');
		
	}
	
	public static function getHTML() {
	
		return '<title>'.self::getTitle().'</title>
		<meta name="description" content="'.self::getDescription().'">
		<meta name="keywords" content="'.self::getKeywords().'">
		<base href="'.dyn::get('hp_url').'">'.PHP_EOL;
		
		print_r(self::$currrentPage);
		
	}
	
	public static function setPageId($id) {
	
		self::$pageId = $id;
		
	}
	
	public static function setCurrentPage() {
		
		if(is_null(self::$currentPage)) {
					
			self::$currentPage = page::factory(self::$pageId);	
		
		}
		
	}
	
}

?>