<?php

class lang {
	
	static $lang;
	static $langs = [];
	static $default = [];
	static $defaultLang = 'en_gb';

    static $defaultLangId = 1;
	
	/**
	 * Die Sprache ersetzen, mit automaitschen laden der main Datei
	 *
	 * @param	string	$lang			Die Sprache
	 *
	 */
	static public function setLang($lang = 'en_gb') {
		
		if(is_dir(dir::lang($lang))) {
			
			self::$lang = $lang;	
			self::loadLang(dir::lang(self::getLang(), 'main.json'));
			
		}
		
		// throw new Exception();
		
	}
	
	/**
	 * String in der ensprechende Sprache bekommen, falls nicht gefunden, wird die DefaultSprache genommmen
	 *
	 * @param	string	$name			Der Sprachstring
	 * @return	string
	 *
	 */
	static public function get($name) {

        $vars = func_get_args();
        array_shift($vars);
		
		if(isset(self::$langs[$name])) {
			return self::getVars(self::$langs[$name], $vars);
		}
		
		if(isset(self::$default[$name])) {
            return self::getVars(self::$default[$name], $vars);
		}
		
		return $name;
		
	}

    static public function getVars($name, $vars = []) {

        if(empty($vars)) {
            return $name;
        }

        return vsprintf($name, $vars);

    }
	
	/**
	 * Gibt die aktuelle Sprache zurück
	 *
	 * @return	string
	 *
	 */
	static public function getLang() {
		
		return self::$lang;
			
	}
	
	/**
	 * Gibt die aktuelle Default Sprache zurück
	 *
	 * @return	string
	 *
	 */
	static public function getDefaultLang() {
		
		return self::$defaultLang;
		
	}
	
	/**
	 * Lädt die entsprechende Datei und fügt sie zur "Datenbank" hinzu
	 *
	 * @param	string	$file			Der Dateipfad ohne .json ende
	 * @param	bool	$defaultLang	Zur Normalen Sprache oder zur Defaultsprache
	 *
	 */
	static public function loadLang($file, $defaultLang = false) {
		
		$file = file_get_contents($file);
		
		// Alle Kommentare löschen (mit Raute beginnen
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
		$array = json_decode($file, true);
		
		if(!$defaultLang) {
			self::$langs = array_merge((array)$array, self::$langs);
		} else {
			self::$default = array_merge((array)$array,self:: $default);
		}
		
	}
	
	
	
	/**
	 * Standardsprache setzen
	 *
	 */
	static public function setDefault() {
			
		$file = dir::lang(self::getDefaultLang(), 'main.json');
					
		self::loadLang($file, true);
		
	}

    /*
     * return a HTML DOM for the language-switcher
     * @return string
     */
    static public function getStructureSelection($page = 'structure', $params = []) {

        $subpage = type::super('subpage', 'string', 'pages');

        $return = '';

        $sql = new sql();
        $sql->result('SELECT * FROM `'.sql::table('lang').'` ORDER BY `sort`');
        if($sql->num() != 1) {
            $return .= '
            <ul class="nav nav-pills" id="structure-language">';
            while($sql->isNext()) {

                $active = (lang::getLangId() == $sql->get('id')) ? ' class="active"' : '';

                $urlParam = array_merge(['subpage'=>$subpage, 'lang' => $sql->get('id')], $params);

                $return .= '<li><a href="'.url::backend($page, $urlParam).'"'.$active.'>'.$sql->get('name').'</a></li>';
                $sql->next();

            }
            $return .= '
            </ul>';
        }

        return $return;

    }

    public static function setDefaultLangId($id) {
        self::$defaultLangId = $id;
    }

    public static function getLangId() {

        if(dyn::get('backend')) {

            return type::session('backend-lang', 'int', self::$defaultLangId);

        }

        return type::super('lang', 'int', self::$defaultLangId);


    }
	
}

?>
