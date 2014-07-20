<?php

trait traitNeed {
    use traitMeta;

    /*
     * Check if its all okay
     *
     * @return bool
     */
    public function checkNeed() {

        $error = false;

        foreach($this->get('need', []) as $key=>$value) {

            if($this->checkNeedKey($key, $value) !== true) {
                $error = true;
            }

        }

        if($error == true) {
            return false;
        }

        return true;

    }

    public function checkNeedKey($name, $value) {

        $method = 'check'.ucfirst($name);

        if(!method_exists(get_called_class(), $method)) {
            throw new Exception(sprintf(lang::get('check_error'), __CLASS__, $method));
        }

        return $this->$method($value);

    }

    public function checkVersion($version) {

        if(dyn::checkVersion(dyn::get('version'), $version) !== lang::get('version_fail_connect')) {
            return true;
        }

        throw new Exception(sprintf(lang::get('wrong_version'), dyn::get('version'), $version));

    }

    public function checkAddon($addons) {

        $check = true;

        foreach($addons as $name=>$version) {

            // Keine Version angegeben
            if(is_int($name)) {
                $name = $version;
                $version = false;
            }

            if(isset(dyn::get('addons')[$name])) {
                $config = dyn::get('addons')[$name];
            }
            // Nicht installiert
            if(!isset($config) || !is_array($config)) {
                $check = false;
                throw new Exception(sprintf(lang::get('addon_not_found'), $name));
            }

            if(!addonConfig::isActive($name)) {
                $check = false;
                throw new Exception(sprintf(lang::get('addon_not_install_active'), $name));
            }

            if(dyn::checkVersion($config['version'], $version) === false) {
                $check = false;
                throw new Exception(sprintf(lang::get('addon_need_version'), $name, $version));
            }

        }

        return $check;

    }

}
?>