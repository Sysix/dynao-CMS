<?php

class url
{
    /*
     * @see urlBe::__construct()
     * @return urlBe
     */
    public static function be($page, array $params = [])
    {
        return new urlBe($page, $params);
    }

    /*
     * @see self::be
     * @deprecated deprecated since v0.3
     */
    public static function backend($page, array $params = [])
    {
        return self::be($page, $params);
    }

    /*
     * Url generieren für Frontend (fe = FrontEnd), abgekürz für schnelleres programmieren
     */
    public static function fe($id = '', $params = [])
    {
        if ($id == '') {
            type::super('page_id', 'int', dyn::get('start_page'));
        }

        $url = extension::get('URL_REWRITE', ['id' => $id, 'params' => $params]);

        if (!extension::has('URL_REWRITE')) {

            $url = 'index.php?page_id=' . $id;
            foreach ($params as $name => $value) {

                $url .= '&amp;' . $name . '=' . $value;

            }

        }

        return $url;
    }


}

?>