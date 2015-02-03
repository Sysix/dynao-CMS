<?php

class addon {
    use traitNeed;
	
	public $config = [];
	public $name;
	public $sql;
	public $isChange = false;
	protected $newEntrys = [];
	
	const INSTALL_FILE = 'install.php';
	const CONFIG_FILE = 'config.php';
	const UNINSTALL_FILE = 'uninstall.php';
	
	public function __construct($addon, $config = true) {
		
		$this->name = $addon;
		
		if($config) {
			$configfile = $this->getFile('config.json');
			$this->config = json_decode(file_get_contents($configfile), true);
		}
		
		addonConfig::isSaved($addon);
		
		$this->sql = $this->getSqlObj()->select()->result();

	}
	
	public function getSql($name, $default = null) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function get($name, $default = null) {
		
		if(isset($this->config[$name])) {
			return $this->config[$name];
		}
		
		return $default;
		
	}
	
	public function add($name, $value, $toSave = false) {
		
		$this->config[$name] = $value;
		
		if($toSave) {
			$this->isChange = true;
			$this->newEntrys[$name] = $value;
		}
		
	}
	
	public function saveConfig() {
		
		if(!$this->isChange)
			return true;
			
		$newEntrys = array_merge($this->config, $this->newEntrys);
			
		return file_put_contents($this->getFile('config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));
		
	}
	
	public function isInstall() {
	
		return $this->getSql('install', 0) == 1;
		
	}
	
	public function isActive() {
	
		return $this->getSql('active', 0) == 1;
		
	}

    public function isAvaible() {

        return ($this->isActive() && $this->isInstall());

    }

    public function getFile($file = '') {

        return dir::addon($this->name, $file);

    }

	/**
	 * @return sql
	 */
    public function getSqlObj() {

        $sql = sql::factory();
        return $sql->setTable('addons')
            ->setWhere('`name` = "'.$this->name.'" AND `plugin` = ""');
    }

    /*
     * install the Addon
     *
     * @return bool
     */
    public function install() {

        try {

            $success = true;
		
            if(!$this->checkNeed()) {
                return false;
            }

            $file = $this->getFile(self::INSTALL_FILE);

			addonConfig::loadConfig($this->name, dir::addon($this->name));
			addonConfig::includeLangFiles(dir::addon($this->name));
			addonConfig::includeLibs(dir::addon($this->name));

            if(file_exists($file)) {
               $success = include $file;
            }

            if(!$success) {
                return false;
            }

        } catch(mysqli_sql_exception $e) {

            echo message::warning('<b>SQL Error:<br />'.$e->getMessage());

        } catch(Exception $e) {

            echo message::danger($e->getMessage());

        }
		
		return true;
				
	}
	
	public function uninstall() {
		
		$file = $this->getFile(self::UNINSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}

        foreach($this->showPluginFolder() as $plugin) {
            $plugin->uninstall();
            $plugin->getSqlObj()->addPost('install', 0)->addPost('active', 0)->update();
        }
		
		return $this;
				
	}
	
	public function delete() {
	
		$this->uninstall();
		
		$this->getSqlObj()->delete();
			
		$dir = $this->getFile();
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		
		foreach($files as $file) {
			
			if ($file->getFilename() === '.' || $file->getFilename() === '..') {
				continue;
			}
			if ($file->isDir()){
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
			
		}
		
		rmdir($dir);
			
		return message::success(sprintf(lang::get('addon_deleted'), $this->name));
		
	}


	public function active($active) {

		$this->getSqlObj()->addPost('active', $active)->update();

		if($active) {
			addonConfig::loadConfig($this->name, dir::addon($this->name));
			addonConfig::includeLangFiles(dir::addon($this->name));
			addonConfig::includeLibs(dir::addon($this->name));
		}

		$config = $this->getFile(self::CONFIG_FILE);
		if($active && file_exists($config)) {
			include $config;
		}

		return $this;
	}

	/**
	 * @return plugin[]
	 */
    public function showPluginFolder() {

        $pluginPath = $this->getFile('plugins'.DIRECTORY_SEPARATOR);

        if(!file_exists($pluginPath)) {
            return [];
        }

        $dir = new DirectoryIterator($pluginPath);
        $list = [];
        foreach($dir as $file) {

            if($file->isDot()) {
                continue;
            }
            $list[$file->getFileName()] = new plugin($this->name, $file->getFileName());
        }

        return $list;

    }
	
	public function getConfig() {
		
		return $this->config;
		
	}
	
}

?>