<?php

class pageCache extends cache {
	
	const FOLDER = 'page';
	
	public static function clearAll() {
		
		if($dir =  opendir(dir::cache(self::FOLDER))) {
			
			while (($file = readdir($dir)) !== false) {
				
				if($file != "." && $file != "..") {
					// parent da mit der pageCache::deleteFile() eine $id benötigt wird
					parent::deleteFile(self::FOLDER.DIRECTORY_SEPARATOR.$file);
					
				}
			
			}
			
			closedir($dir);
			
		}	
			
	}
	
	public static function write($content, $id) {
		
		$file = self::getFileName($id, 'article');
	
		if(!file_put_contents(dir::cache(self::FOLDER.DIRECTORY_SEPARATOR.$file), $content, LOCK_EX)) {
				return false;
		}
		
		return true;
		
	}
	
	static public function deleteFile($id) {
		
		$file = self::getFileName($id, 'article');
	
		parent::deleteFile(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	static public function exist($id, $time = false) {
		
		$file = self::getFileName($id, 'article');
	
		return parent::exist(self::FOLDER.DIRECTORY_SEPARATOR.$file, $time);
		
	}
	
	static public function read($id) {
		
		$file = self::getFileName($id, 'article');
		
		return parent::read(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function generateArticle($id) {
		
		$return = [];
		
		$backend = dyn::get('backend');
		dyn::add('backend', false);
		
		$page = new page($id);
		$blocks = $page->getBlocks();
		
		foreach($blocks as $block) {
			$block->setEval(false);
			$return[] =  $block->getContent();
		}	
		
		if(self::exist($id)) {
			self::deleteFile($id);
		}
		
		self::write(implode(PHP_EOL, $return), $id);
		
		dyn::add('backend', $backend);
		
	}
	
}

?>