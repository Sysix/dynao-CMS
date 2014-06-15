<?php

class seo_sitemap {

    public static $articles;

    public function setArticles($offlines) {

        if($offlines) {
            $where = '';
        } else {
            $where = ' WHERE online = 1';
        }

        $sql = sql::factory();
        $sql->query('SELECT * FROM '.sql::table('structure').$where.' ORDER BY `sort`')->result();
        while($sql->isNext()) {

            $prio = ($sql->get('id') == dyn::get('start_page')) ? 1 : 0.8;

            $updatedAt = new DateTime($sql->get('updatedAt'));
            $freq = self::getChangeFreq($updatedAt);

            self::$articles[] = [
                'loc' => dyn::get('hp_url').seo_rewrite::rewriteId($sql->get('id')),
                'prio' => $prio,
                'freq' => $freq,
                'lastmod' => $updatedAt->format('c')
            ];

            $sql->next();
        }

    }

    public static function getChangeFreq($time) {

       $age = time() - $time->getTimestamp();

       switch($age) {
           case($age < 604800):
               return 'daily';
           case($age < 2419200):
               return 'weekly';
           default:
               return 'monthly';
       }

    }

    public static function getUrlFragmet($loc, $lastmod, $freq, $prio) {

        $urlFragmetTab =  PHP_EOL."\t\t";

        return "\t<url>".
        $urlFragmetTab.'<loc>'.$loc.'</loc>'.
        $urlFragmetTab.'<lastmod>'.$lastmod.'</lastmod>'.
        $urlFragmetTab.'<changefreq>'.$freq.'</changefreq>'.
        $urlFragmetTab.'<priority>'.$prio.'</priority>'.
        PHP_EOL."\t</url>".PHP_EOL;

    }

    public function  send() {

        while(ob_get_level()) {
			ob_end_clean();
		}

        header('Content-type: text/xml');

        echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        foreach(self::$articles as $article) {
            echo self::getUrlFragmet(
                $article['loc'],
                $article['lastmod'],
                $article['freq'],
                $article['prio']
            );
        }

        echo '</urlset>';
    }

}

?>