<?php

class url {
	
	public static function backend($page = '', $params = []) {		
		
		$return = 'index.php';
		
		if($page != '')			
			$return .= '?page='.$page;
		
		if(count($params)) {
			
			foreach($params as $key=>$val) {
				$return .= '&amp;'.$key.'='.$val;	
			}
			
		}
			
		return $return;
		
	}
	
	/*
	 * Url generieren für Frontend (fe = FrontEnd), abgekürz für schnelleres programmieren
	 */
	public static function fe($id = '', $params = []) {
		
		if($id == '') {
			type::super('page_id', 'int', dyn::get('start_page'));
		}
		
		$url = extension::get('URL_REWRITE', ['id'=>$id, 'params'=>$params]);
		
		if(!extension::has('URL_REWRITE')) {
			
			$url = 'index.php?page_id='.$id;
			foreach($params as $name=>$value) {
				
				$url .= '&amp;'.$name.'='.$value;
				
			}
			
		}
		
		return $url;
		
	}
	
	
}

?>