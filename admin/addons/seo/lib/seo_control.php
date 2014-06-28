<?php

class seo_control {


    public static $filter = [
        'default' => "seo_control::convertToUrl([^)*])"
    ];

    /*
     * Gibt von der Pathliste die entsprechende Url aus
     * @param string $url Standard-Url
     * @param string $name Addon Row Name
     *
     * @return string
     */
    public static function getUrl($url, $name) {

        $url = rtrim($url, dyn::get('addons')['seo']['ending']);
        if($url) {
            $url .= '/';
        }

        return $url.seo_rewrite::makeSEOName($name);

    }

    /*
     * return the addon table id
     * @param array List of Urls
     *
     * @return int|null
     */
    public static function getId($addonUrls) {

       $addonUrls = array_flip($addonUrls);

        $url = seo_rewrite::removeWastedParts($_SERVER['REQUEST_URI']);
        $urlParts = explode('/', $url);
        $urlLast = end($urlParts);

        if(isset($addonUrls[$urlLast])) {
            return $addonUrls[$urlLast];

        }

        return null;

    }

    /*
     * add a filter
     * @param string $name filtername
     * @param string $regex SQL-Regex
     */
    public static function addFilter($name, $regex) {

        self::$filter[$name] = $regex;

    }

    /*
     * add Url to the Pathlist
     * @param string $filter filtername
     * @param array $addonsUrl list of the raw urlname
     *
     * @return bool
     */
    public static function addToPathlist($filter, $addonsUrls) {

        $addonnames = [];
        foreach(seo_control::getArticleNames(seo_control::getArticleByFilter($filter)) as $id=>$name) {

            $name = rtrim($name, dyn::get('addons')['seo']['ending']);
            if($name) {
                $name .= '/';
            }

            foreach($addonsUrls as $addonname) {
                $addonnames[$name.$addonname] = $id;
            }
        }

        extension::add('SEO_GENERATE_PATHLIST', function($list) use ($addonnames) {

            $list = array_merge($addonnames, $list);

            return $list;
        });

    }

    /*
     * Url von einer Tabelle ausgeben
     *
     * @param string $table Tabellennamen
     * @param array $cols Attribute der Tabelle
     * @param string $where SQL Where Filter
     * @param string $filter Filtername
     *
     * @return bool
     */
    public static function getUrlsFromTable($table, $cols = ['id', 'title'], $where = '') {

        if($where) {
            $where = ' WHERE '.$where;
        }

        $return = [];

        $sql = sql::factory();
        $sql->result('SELECT `'.$cols[0].'`, `'.$cols[1].'` FROM '.sql::table($table).$where);
        while($sql->isNext()) {

            $name = seo_rewrite::makeSEOName($sql->get($cols[1]));

            $return[$sql->get($cols[0])] = $name;

            $sql->next();
        }

        return $return;



    }
    /*
     * Get All Articles filtered by RegexFilter
     * @param string $filter filtername
     * @param string $where Where-Filter
     *
     * @return array
     */
    public static function getArticleByFilter($filter, $where = '') {

        $regex = self::$filter[$filter];

        if($where) {
            $where = 'AND '.$where;
        }

        $return = [];

        $sql = sql::factory();
        $sql->result('SELECT
			  s.id
			FROM
			  '.sql::table('structure').' AS s
			  LEFT JOIN
			  '.sql::table('structure_area').' AS a
			    ON s.id = a.structure_id
			  LEFT JOIN
			    '.sql::table('module').' AS m
				ON m.id = a.modul
			WHERE
			  a.online = 1
			  AND
			  a.block = 0
			  '.$where.'
			  AND (
			    m.output REGEXP "'.$regex.'"
			    OR
			    m.input REGEXP "'.$regex.'"
			  )
			GROUP BY s.id
			ORDER BY
			  a.sort');
        while($sql->isNext()) {
            $return[] = $sql->get('id');
            $sql->next();
        }

        return $return;

    }

    public static function getArticleNames(array $ids) {

        seo_rewrite::loadPathlist();
        $pathlist = array_flip(seo_rewrite::$pathlist);

        $return = [];
        foreach($ids as $id) {

            $return[$id] = $pathlist[$id];
        }

        return $return;

    }

} 