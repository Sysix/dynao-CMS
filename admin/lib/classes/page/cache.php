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
	
	public static function write($content, $id, $type = 'article') {
		
		$file = self::getFileName($id, $type);
	
		if(!file_put_contents(dir::cache(self::FOLDER.DIRECTORY_SEPARATOR.$file), $content, LOCK_EX)) {
				return false;
		}
		
		return true;
		
	}
	
	static public function deleteFile($id, $type = 'article') {
		
		$file = self::getFileName($id, $type);
	
		parent::deleteFile(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	static public function exist($id, $time = false, $type = 'article') {
		
		$file = self::getFileName($id, $type);
	
		return parent::exist(self::FOLDER.DIRECTORY_SEPARATOR.$file, $time);
		
	}
	
	static public function read($id, $type = 'article') {
		
		$file = self::getFileName($id, $type);
		
		return parent::read(self::FOLDER.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function generateArticle($id, $block = false) {
		
		$return = [];
		
		$type = ($block) ? 'block' : 'article';
		
		$backend = dyn::get('backend');
		dyn::add('backend', false);
		
		$page = new page($id, true, $block);
		$blocks = $page->getBlocks();
		
		foreach($blocks as $block) {
			$block->setEval(false);
			$return[] =  $block->getContent();
		}	
		
		if(self::exist($id, false, $type)) {
			self::deleteFile($id, $type);
		}
		
		self::write(implode(PHP_EOL, $return), $id, $type);
		
		dyn::add('backend', $backend);
		
	}
	
}

?>