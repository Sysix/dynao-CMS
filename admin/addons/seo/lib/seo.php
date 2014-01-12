<?php

class seo {
	
	public static $pathlist;
	
	public function __construct() {
	
		$pathlist = dir::addon('seo', 'pathlist.json');
		
		if(is_file($pathlist)) {
			self::$pathlist = json_decode(file_get_contents($pathlist), true);
		}
		
	}
	
	public function parseUrl($url) {
		
		$url = str_replace(dyn::get('hp_url'), '', $url); 
		
		$url = trim($url, '/');
		
		if(isset(self::$pathlist[$url])) {
			
			$id = self::$pathlist[$url];
			extension::add('SET_PAGE_ID', function() use ($id) {
				return $id;
			});
			
		}
		
	}
	
	public static function rewriteId($id) {
		
		$pathlist = array_flip(self::$pathlist);
		
		if(isset($pathlist[$id])) {
			return $pathlist[$id];
		}
		
		return 'index.php?page_id='.$id;
		
	}
	
	public static function generatePathlist() {
		
		$return = [];
		
		$sql = new sql();
		$sql->query('SELECT name, id FROM '.sql::table('structure'))->result();
		while($sql->isNext()) {
			
			$name = self::makeSEOName($sql->get('name'));
			$return[$name] = $sql->get('id');
			
			$sql->next();
		}
		
		
		
		return file_put_contents(dir::addon('seo', 'pathlist.json'), json_encode($return, JSON_PRETTY_PRINT));
		
		
	}
	
	public static function makeSEOName($name) {
		
		$name = strtolower($name);
	
		$search = ['ä', 'ü', 'ö', 'ß', '&'];
		$replace = ['ae', 'ue', 'oe', 'ss', 'und'];
		
		$name = str_replace($search, $replace, $name);
		
		$name = preg_replace('/[^a-z0-9]/', '-',  $name);
		
		$name = preg_replace('/-{2,}/', '-', $name);
		
		return $name;
	
	}
	
}

?>