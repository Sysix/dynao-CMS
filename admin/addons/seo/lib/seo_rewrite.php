<?php

class seo_rewrite {
	
	public static $pathlist;
	
	public function __construct() {
	
		self::loadPathlist();
		
	}
	
	public static  function loadPathlist() {
		
		if(is_null(self::$pathlist)) {
			
			$pathlist = dir::addon('seo', 'pathlist.json');
		
			if(is_file($pathlist)) {
				self::$pathlist = json_decode(file_get_contents($pathlist), true);
			}

			if(empty(self::$pathlist)) {
				self::generatePathlist();
				self::loadPathlist();
			}
			
		}
		
	}
	
	/*
	 * URL parsen und Page_id auslesen
	 *
	 * @param string $url Die URL
	 * @return int
	 */
	public function parseUrl($url) {
		
		$url = $this->deleteSubDir($url);
		
		$url = ltrim($url, '/');
		
		//Redirect falls alte URL
		preg_match('/.*?page_id=(\d*).*[&lang=](\d*)/', $url, $match);
		if(isset($match[1]) && page::isValid($match[1])) {
			$this->redirect($match[1], $match[2]);
		}
		
		// Überflüssige Zeichen löschen, damit eine reine URL angezeigt wird
        $url = self::removeWastedParts($url);


		if(isset(self::$pathlist[$url])) {

			$id =  self::$pathlist[$url]['id'];
			$lang =  self::$pathlist[$url]['lang'];
			lang::setDefaultLangId($lang);

			extension::add('SET_PAGE_ID', function() use ($id, $lang) {
				return $id;
			});
			
		}
		
		if($url == '') {
			$id = dyn::get('start_page');
		}
		
		if(!isset($id)) {
			header('HTTP/1.0 404 Not Found');
			$id = dyn::get('error_page');	
		}
		
		return $id;
		
	}

    public static function removeWastedParts($url) {

        foreach(['?', '#'] as $char) {

            if(($pos = strpos($url, $char)) !== false) {
                $url = substr($url, 0, $pos);
            }

        }

        return $url;

    }
	
	/*
	 * Falls im Unterordner installiert, von $url löschen
	 *
	 * @param string $url Die URL
	 * @return string
	 */
	public function deleteSubDir($url) {
		
		$urlPath = parse_url(dyn::get('hp_url'));
		
		if(isset($urlPath['path'])) {
			$subdir = trim($urlPath['path'], '/');
			$url = str_replace($subdir, '', $url);
		}
		
		return $url;
		
	}
	
	/*
	 * Weiterleitung auf einer SEO-freundliche URL
	 *
	 * @param int $id Page_id
	 * @param int $lang Lang ID
	 */
	public function redirect($id, $lang) {
		
		header('HTTP/1.1 301 Moved Permanently');
		
		while(ob_get_level()){
		  ob_end_clean();
		}

		header('Location:'.self::rewriteId($id, $lang));
   		exit();
		
	}
	
	/*
	 * Page_id zu einer SEO-Url generieren
	 *
	 * @param int $id Page_id
	 * @return string
	 */
	public static function rewriteId($id, $lang) {
		
		self::loadPathlist();

		foreach(self::$pathlist as $url => $params) {
			if($params['id'] == $id && $params['lang'] == $lang) {
				return $url;
			}
		}
		
		return 'index.php?page_id='.$id .'&lang=' . $lang;
		
	}
	
	/*
	 * Pathliste generieren
	 *
	 * @return int/bool
	 */
	public static function generatePathlist() {
		
		$return = [];

		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure'))->result();
		while($sql->isNext()) {


			if($sql->get('seo_costum_url')) {
				$name = $sql->get('seo_costum_url');
			} else {			
				$name = self::makeSEOName($sql->get('name'));
			}
			
			if($sql->get('parent_id')) {
				$name = self::getParentsName($sql->get('parent_id'), $sql->get('lang')).'/'.$name;
			}

            if($sql->get('id') == dyn::get('start_page') && dyn::get('addons')['seo']['start_url'] == 0) {
                $name = '';
            }

			$return[self::getLangSlug($sql->get('lang')) . $name] =  [
				'id' => (int)$sql->get('id'),
				'lang' => (int) $sql->get('lang')
			];
			
			$sql->next();
		}

		$return = extension::get('SEO_GENERATE_PATHLIST', $return);
		
		return file_put_contents(dir::addon('seo', 'pathlist.json'), json_encode($return, JSON_PRETTY_PRINT));	
		
	}
	
	/**
	 * Ausgabe des Namen's von der Vater-Seite
	 *
	 * @param int $id structure-Id
	 * @param int $lang langId
	 * @return string
	 */
	public static function getParentsName($id, $lang) {
		
		$sql = sql::factory();
		$sql->query('SELECT name, id, seo_costum_url, parent_id FROM '.sql::table('structure').' WHERE id = '. $id . ' AND `lang` = ' . $lang)->result();
			
		if($sql->get('seo_costum_url')) {
			$name = $sql->get('seo_costum_url');
			$name = str_replace('.html', '', $name); 
		} else {			
			$name = self::makeSEOName($sql->get('name'), false);
		}
		
		if($sql->get('parent_id')) {			
			$name = self::getParentsName($sql->get('parent_id'), $lang) . '/' . $name;
		}	
		
		return $name;
			
	}
	
	/*
	 * URL-Freundliche Namen erstellen
	 *
	 * @param string $name Der Name
	 * @param bool $ending .html bzw / ransetzten oder nicht
	 * @return string
	 */
	public static function makeSEOName($name, $ending = true) {
		
		$name = mb_strtolower($name);
	
		$search = ['ä', 'ü', 'ö', 'ß', '&'];
		$replace = ['ae', 'ue', 'oe', 'ss', 'und'];
		
		$name = str_replace($search, $replace, $name);
		
		$name = preg_replace('/[^a-z0-9]/', '-',  $name);
		
		$name = preg_replace('/-{2,}/', '-', $name);

        if($ending) {
		    $name .= dyn::get('addons')['seo']['ending'];
        }

		return $name;
	}

	public static function getLangSlug($id) {

		$langs = lang::getAvailableLangs();

		if(!isset($langs[$id]) || count($langs) == 1) {
			return '';
		}

		return self::makeSEOName($langs[$id], false) . '/';
	}

}

?>