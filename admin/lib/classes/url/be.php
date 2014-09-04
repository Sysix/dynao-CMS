<?php

class urlBe
{
    public $params = [];

    static $paramsLevel = [
        'page', 'subpage', 'action'
    ];

    /*
     * create a Backend Url
     * @param string $page pagename
     * @param array $params List of Params
     * @return this
     */
    public function __construct($page,array $params = [])
    {
        $params['page'] = $page;

        $this->params = self::sortParams($params);
    }

    /*
     * add a param
     * @param string $name
     * @param string $value
     * @return this
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /*
     * check if a param exists
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }

    /*
     * return a param
     * @param string $name
     * @param mixed $default If the Key not exists
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }

        return $default;
    }

    /*
     * return a array of params
     * @param array $name List of params
     * @param mixed $default If the key not exists
     * @return array
     */
    public function getParams(array $name, $default = null)
    {
        $newArray = [];

        foreach ($name as $values) {
            $newArray[$values] = $this->getParam($values, $default);
        }

        return $newArray;
    }

    /*
     * return a url
     * @param array $params add params for output
     * @return string
     */
    public function get(array $params = [])
    {
        $params = array_merge($this->params, $params);
        $params = self::sortParams($params);

        $return = 'index.php?';
        $x = 0;
        foreach ($params as $name => $value) {

            $str = ($x == 0) ? '?' : '&amp;';

            $return .= $str . $name . '=' . $value;
            $x = 1;
        }

        return $return;
    }

    public function __toString(array $params = [])
    {
        return $this->get($params);
    }

    /*
     * sort the params in a right order
     * @param array $params
     * @return array
     */
    public static function sortParams(array $params)
    {
        uksort($params, function ($param1, $param2) {

            if (!isset(self::$paramsLevel[$param1]) && !isset(self::$paramsLevel[$param2])) {
                return 0;
            }

            if (!isset(self::$paramsLevel[$param1])) {
                return -1;
            }

            if (!isset(self::$paramsLevel[$param2])) {
                return 1;
            }

            $paramsLvl = array_keys(self::$paramsLevel);

            return $paramsLvl[$param2] - $paramsLvl[$param1];

        });

        return $params;
    }
}

?>