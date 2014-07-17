<?php

class plugin {
    use traitNeed;

    public $addonname;
    public $name;
    public $sql;

    /*
     * Constructur
     *
     * @param string $addon addonname
     * @param string $plugin pluginname
     */
    public function __construct($addon, $plugin) {

        $this->addonname = $addon;
        $this->name = $plugin;

        addonConfig::isSaved($addon, $plugin);

        $this->sql = sql::factory();
        $this->sql->query('SELECT * FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'" AND `plugin` = "'.$plugin.'"')->result();

    }

}

?>