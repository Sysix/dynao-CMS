<?php


class pageArea {

	public $isNew;
	public $sql;
    public $lang;
	
	public $eval = true;
	
	public static $types = [
		'varsValue',
		'varsLink',
		'varsLinklist',
		'varsPhp',
	];


    /**
     * @param sql $sql
     */
	public function __construct(sql $sql) {

        $this->sql = $sql;

	}

    /**
     * @param string $lang
     * @return $this
     */
    public function setLang($lang) {

        $this->lang = $lang;

        return $this;

    }

    /**
     * @return string
     */
    public function getLang() {

        return $this->lang;

    }
	
	public static function addType($class) {
		
		if(!class_exists($class, false)) {
			throw new InvalidArgumentException(__CLASS__.'::'.__METHOD__.' 1. Parameter erwartet eine vorhandene Klasse');
		}
		self::$types[] = $class;
			
	}
	
	public function setNew($new) {
	
		$this->isNew = $new;
		
		return $this;
		
	}
	
	public function isNew() {
		
		return $this->isNew;
			
	}
	
	public function setEval($bool) {
	
		$this->eval = $bool;
		
		return $this;
		
	}
	
	public function get($value) {
		
		if($this->isNew)
			return;
			
		return $this->sql->get($value);
		
	}

    /**
     * @return sql
     */
	public function getSql() {
		
		return $this->sql;
			
	}
	
	public function getModulId() {
		
		if($this->isNew) {
			
			return type::super('modul', 'int');
			
		} else {
	
			return $this->get('modul');
			
		}
		
	}
	
	public function getSort() {
		
		if($this->isNew) {
	
			return type::super('sort', 'int');
			
		} else {
		
			return $this->get('sort');
			
		}
		
	}
	
	public function getId() {
	
		return ($this->isNew) ? 0 : $this->get('id');
		
	}
	
	public function getStructureId() {
	
		if($this->isNew) {
			
			return type::super('structure_id', 'int');
			
		} else {
			
			return $this->get('structure_id');
			
		}
		
	}
	
	public function isOnline() {
	
		return $this->get('online') == 1;
		
	}
	
	public function isFirstBlock() {
		
		return $this->getSort() == 1;
		
	}
	
	public function isLastBlock() {
		
		$block = ($this->block) ? 1 : 0;
		
		$sql = sql::factory();
		$sql->query('SELECT sort FROM '.sql::table('structure_area').' WHERE block = '.$block.' AND structure_id = '.$this->getStructureId());
			
		return $sql->num() == $this->getSort();
		
		
	}	
	
	////////////////////// Eingabe/Ausgabe Dynamisch machen
	
	public static function getEval($content) {
		
		ob_start();
		ob_implicit_flush(0);
		
		// PHP-Startzeichen  im Code verwenden können
		$content = eval(' ?>'.$content.' <?php ');
		
		// Falls irgendein Fehler gekommen ist
		if(false === $content) {
			echo message::danger(lang::get('modul_php_error'));
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	public function OutputFilter($content, $form) {
			
		try {
			
			if(!(is_object($form) && (is_a($form, 'form') || is_a($form, 'sql')))) {
				throw new Exception(__CLASS__.'::OutputFilter Parameter muss Form/SQL Object sein');
			}
			
		} catch(Exception $e) {
			echo $e->getMessage();	
		}

		foreach(self::$types as $class) {
			$class = new $class($content);
			$class->getOutValue($form);

			$content = $class->getContent();
		}

		preg_match_all('/\/\/DYN-NOT-EVAL(.*)\/\/DYN-NOT-EVAL-END/us', $content, $matches);
		
		if(is_array($matches)) {
			
			foreach($matches[1] as $match) {
				$matchNew = str_replace(['<?php', '?>', '//DYN-NOT-EVAL-END', '//DYN-NOT-EVAL'], ['&lt;?php', '?&gt;', '', ''], $match);
				$content = str_replace($match, $matchNew, $content);
			}
			
		}
		
		$content = str_replace(['//DYN-NOT-EVAL-END', '//DYN-NOT-EVAL'], '', $content);
		
		if($this->eval) {
			$content = self::getEval($content);
		}
		
		return $content;
		
	}
	
}

?>