<?php

class seo_robots {
	
	public function get() {
		
		$return = '';

        // Not indexing
        if(!dyn::get('addons')['seo']['robots']) {
            return 'User-agent: *'.PHP_EOL.'Disallow: /';
        }
		
		$sql = sql::factory();
		$sql->query('SELECT id FROM '.sql::table('structure').' WHERE seo_robots = 0');
		while($sql->isNext()) {
			
			$return .= 'Disallow: /'.seo_rewrite::rewriteId($sql->get('id')).PHP_EOL;
			
			$sql->next();
		}
		
		if($return != '') {
			$return = 'User-agent: *'.PHP_EOL.$out.PHP_EOL;
		}
		
		if($return == '') {
			return 'User-agent: *'.PHP_EOL.'Disallow:';	
		}
		
		return $return;
		
	}
	
	public function getSitemapLink() {
	
		return PHP_EOL.PHP_EOL.'Sitemap: '.dyn::get('hp_url').'sitemap.xml';
		
	}
	
	public function send() {
	
		$content = $this->get();
		$content .= $this->getSitemapLink();
		
		while(ob_get_level()) {
			ob_end_clean();	
		}
		
		header('Content-Type: text/plain; charset=utf-8');
		header('Content-Length: '.strlen($content));
		
		header('X-Robots-Tag: noindex, noarchive');
		header('Cache-Control: must-revalidate, proxy-revalidate, post-check=0, pre-check=0, private');
		header('Pragma: no-cache');
		header('Expires: -1');
		
		echo $content;
		
	}
	
}

?>