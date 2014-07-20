<?php

class plugin extends addon {

    public $addonname;
    public $name;
    public $sql;

    /*
     * Constructur
     *
     * @param string $addon addonname
     * @param string $plugin pluginname
     */
    public function __construct($addon, $plugin, $config = true) {

        $this->addonname = $addon;
        $this->name = $plugin;

        if($config) {
            $configfile = $this->getFile('config.json');
            $this->config = json_decode(file_get_contents($configfile), true);
        }

        addonConfig::isSaved($addon, $plugin);

        $this->sql = $this->getSqlObj()->select()->result();

    }

    public function getFile($file = '') {

        return dir::plugin($this->addonname, $this->name, $file);

    }

    public function getSqlObj() {

        $sql = sql::factory();
        return $sql->setTable('addons')
            ->setWhere('`name` = "'.$this->addonname.'" AND `plugin` = "'.$this->name.'"');


    }

    public function showPluginFolder() {
        return [];
    }

}

?>