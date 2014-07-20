<?php

class pluginConfig extends addonConfig {

    const TYPE = 'plugins';
	
	static $all = [];
	static $allConfig = [];
	
	public static function getAll() {

		if(!count(self::$all)) {

			$sql = sql::factory();		
			$sql->query('SELECT name, plugin FROM '.sql::table('addons').' WHERE `install` = 1  AND `active` = 1 AND `plugin` != ""')->result();
            while($sql->isNext()) {
				self::$all[dir::plugin($sql->get('name'), $sql->get('plugin'))] = $sql->get('name').'-'.$sql->get('plugin');
				$sql->next();		
			}
			
		}

		return self::$all;

	}
	
}

?>
