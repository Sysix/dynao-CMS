<?php

class url
{
    public static $langId = 1;

    public static function init() {

        self::$langId = lang::getLangId();
    }
    /**
     * @see urlBe::__construct()
     * @return urlBe
     */
    public static function be($page, array $params = [])
    {
        return new urlBe($page, $params);
    }

    /**
     * @see self::be
     * @deprecated deprecated since v0.3
     */
    public static function backend($page, array $params = [])
    {
        return self::be($page, $params);
    }

    /**
     * Url generieren für Frontend (fe = FrontEnd), abgekürz für schnelleres programmieren
     *
     * @param int $id
     * @param int $lang
     * @param array $params
     * @return string
     */
    public static function fe($id = 0, $lang = 1, $params = [])
    {
        if(is_array($lang)) {
            $params = $lang;
            $lang = 1;
        }

        if ($id == 0) {
            type::super('page_id', 'int', dyn::get('start_page'));
        }

        $url = extension::get('URL_REWRITE', ['id' => $id, 'lang' => $lang, 'params' => $params]);
        if (!extension::has('URL_REWRITE')) {

            $url = 'index.php?page_id=' . $id;
            if(count(lang::getAvailableLangs()) > 1) {
                $url .= '&amp;lang='.$lang;
            }
            foreach ($params as $name => $value) {

                $url .= '&amp;' . $name . '=' . $value;

            }

        }

        return $url;
    }


}

?>