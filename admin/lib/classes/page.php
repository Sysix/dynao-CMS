<?php

class page {
	use traitFactory;
	use traitMeta;
	
	private $block;
	
	protected $sql;
	
	public function __construct($id, $offlinePages = true, $type = 'article') {
		
		$this->block = $type;
		
		if(is_object($id)) {
			
			$this->sql = $id;
			
		} else {
			
			$extraWhere = '';

            $lang = '';

            //TODO:: add sql table blocks add column lang
            if($this->block == 'article')
                $lang = ' AND `lang` ='.lang::getLangId();
			
			if(!$offlinePages)		
				$extraWhere =  ' AND WHERE online = 1';
			
			$table = ($this->block != 'article') ? sql::table('blocks') : sql::table('structure');

			$this->sql = sql::factory();
			$this->sql->query('SELECT * FROM `'.$table.'` WHERE id = '.$id.$lang.$extraWhere)->result();
		
		}
		
	}
	
	public function get($value, $default = null) {
		
		return $this->sql->get($value, $default);
		
	}
	
	public function isOnline() {
	
		return $this->get('online', 0) == 1;
		
	}
	
	public function getBlocks() {
		return module::getByStructureId($this->get('id'), $this->get('lang', lang::getLangId()), $this->block);
	}
	
	public function getTemplate() {
		
		ob_start();
		
		if(!pageCache::exist($this->get('id'), $this->get('lang', lang::getLangId()))) {
			pageCache::generateArticle($this->get('id'), $this->get('lang', lang::getLangId()));
		}
				
		$content = pageCache::read($this->get('id'), $this->get('lang',  lang::getLangId()));
		
		$content = pageArea::getEval($content);
		
		$content = extension::get('FRONTEND_OUTPUT', $content);
		
		dyn::add('content', $content);
		
		include(dir::template(dyn::get('template'), $this->get('template')));
		
		$content = ob_get_contents();	
		
		ob_end_clean();
		
		return $content;
		
	}
	
	public static function getChildPages($parentId, $offlinePages = true) {
		
		$return = [];
		$classname = __CLASS__;
		
		$extraWhere = '';
			
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
	
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();							
		while($sql->isNext()) {
		
			$return[] = new $classname($sql);
			
			$sql->next();	
		}
		
		return $return;
	
	}
	
	public static function getRootPages($offlinePage = true) {
		
		return self::getChildPages(0, $offlinePage);
		
	}
	
	public static function isValid($id) {

        $lang = lang::getLangId();
		
		$sql = sql::factory();
		return (bool)$sql->num('SELECT id FROM `'.sql::table('structure').'` WHERE id = '.$id.' AND `lang` = '.$lang);
	}
	
	public function isStart() {
		
		return (dyn::get('start_page') == $this->get('id'));
			
	}
	
}

?>