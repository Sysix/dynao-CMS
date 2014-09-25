<?php

class pageCache /* extends cache */ {
	
	const FOLDER = 'page';
	
	public static function clearAll() {
		
		if($dir =  opendir(dir::cache(self::FOLDER))) {
			
			while (($file = readdir($dir)) !== false) {
				
				if($file != "." && $file != "..") {
					// cache da mit der pageCache::deleteFile() eine $id benötigt wird
					cache::deleteFile(self::FOLDER.DIRECTORY_SEPARATOR.$file);
					
				}
			
			}
			
			closedir($dir);
			
		}	
			
	}

    /**
     * Gibt einen Namen für die Datei raus
     *
     * @param	id	$id
     * @param	id	$lang
     * @return	string
     *
     */
    static public function getFileName($id, $lang, $type) {

        return md5($id.$lang.$type).'.cache';

    }
	
	public static function write($content, $id, $lang, $type = 'article') {
		
		$file = self::getFileName($id, $lang, $type);
	
		if(!file_put_contents(dir::cache(self::FOLDER.DIRECTORY_SEPARATOR.$file), $content, LOCK_EX)) {
				return false;
		}
		
		return true;
		
	}
	
	static public function deleteFile($id, $lang, $type = 'article') {
		
		$file = self::getFileName($id, $lang, $type);
	
		cache::deleteFile(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	static public function exist($id, $lang, $time = false, $type = 'article') {
		
		$file = self::getFileName($id, $lang, $type);
	
		return cache::exist(self::FOLDER.DIRECTORY_SEPARATOR.$file, $time);
		
	}
	
	static public function read($id, $lang, $type = 'article') {
		
		$file = self::getFileName($id, $lang, $type);
		
		return cache::read(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function generateArticle($id, $lang, $type = 'article') {
		
		$return = [];
		
		$backend = dyn::get('backend');
		dyn::add('backend', false);
		
		$page = new page($id, true, $type);
		$blocks = $page->getBlocks();

		foreach($blocks as $block) {
			$block->setEval(false);
			$return[] =  $block->getContent();
		}	
		
		if(self::exist($id, $lang, false, $type)) {
			self::deleteFile($id, $lang, $type);
		}
		
		self::write(implode(PHP_EOL, $return), $id, $lang, $type);
		
		dyn::add('backend', $backend);
		
	}
	
}

?>