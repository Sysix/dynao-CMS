<?php

class seo_rewrite {
	
	public static $pathlist;
	public function __construct() {
	
		$pathlist = dir::addon('seo', 'pathlist.json');
		
		if(is_file($pathlist)) {
			self::$pathlist = json_decode(file_get_contents($pathlist), true);
		}
		
	}
	
	/*
	 * URL parsen und Page_id auslesen
	 *
	 * @param string $url Die URL
	 * @return int
	 */
	public function parseUrl($url) {
		
		$url = str_replace(dyn::get('hp_url'), '', $url); 
		
		$url = trim($url, '/');
		
		if(isset(self::$pathlist[$url])) {
			
			$id = self::$pathlist[$url];
			extension::add('SET_PAGE_ID', function() use ($id) {
				return $id;
			});
			
		}
		
		if($url == '') {
			$id = dyn::get('start_page');
		}
		
		return $id;
		
	}
	
	/*
	 * Page_id zu einer SEO-Url generieren
	 *
	 * @param int $id Page_id
	 * @return string
	 */
	public static function rewriteId($id) {
		
		$pathlist = array_flip(self::$pathlist);
		
		if(isset($pathlist[$id])) {
			return $pathlist[$id];
		}
		
		return 'index.php?page_id='.$id;
		
	}
	
	/*
	 * Pathliste generieren
	 *
	 * @return int/bool
	 */
	public static function generatePathlist() {
		
		$return = [];
		
		$sql = sql::factory();
		$sql->query('SELECT name, id, seo_costum_url, parent_id FROM '.sql::table('structure'))->result();
		while($sql->isNext()) {
			
			if($sql->get('seo_costum_url')) {
				$name = $sql->get('seo_costum_url');
			} else {			
				$name = self::makeSEOName($sql->get('name'));
			}
			
			if($sql->get('parent_id')) {
				$name = self::getParentsName($sql->get('parent_id')).'/'.$name;
			}
			
			$return[$name] = $sql->get('id');
			
			$sql->next();
		}
		
		return file_put_contents(dir::addon('seo', 'pathlist.json'), json_encode($return, JSON_PRETTY_PRINT));	
		
	}
	
	/*
	 * Ausgabe des Namen's von der Vater-Seite
	 *
	 * @param int $id structure-Id
	 * @return string
	 */
	public static function getParentsName($id) {
		
		$sql = sql::factory();
		$sql->query('SELECT name, id, seo_costum_url, parent_id FROM '.sql::table('structure').' WHERE id = '.$id)->result();
			
		if($sql->get('seo_costum_url')) {
			$name = $sql->get('seo_costum_url');
			$name = str_replace('.html', '', $name); 
		} else {			
			$name = self::makeSEOName($sql->get('name'), false);
		}
		
		if($sql->get('parent_id')) {			
			$name = self::getParentsName($sql->get('parent_id')).'/'.$name;			
		}	
		
		return $name;
			
	}
	
	/*
	 * URL-Freundliche Namen erstellen
	 *
	 * @param string $name Der Name
	 * @param bool $html .html ransetzten oder nicht
	 * @return string
	 */
	public static function makeSEOName($name, $html = true) {
		
		$name = mb_strtolower($name);
	
		$search = ['ä', 'ü', 'ö', 'ß', '&'];
		$replace = ['ae', 'ue', 'oe', 'ss', 'und'];
		
		$name = str_replace($search, $replace, $name);
		
		$name = preg_replace('/[^a-z0-9]/', '-',  $name);
		
		$name = preg_replace('/-{2,}/', '-', $name);
		
		if($html) {
			$name .= '.html';
		}
		
		return $name;
	
	}
	
}

?>