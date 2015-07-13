<?php
namespace xapon\YiiEnvironment;

/**
 * @name Environment
 * @author Anton Sergeyev
 * @author Marco van 't Wout | Tremani
 *
 * Simple class used to set configuration and debugging depending on environment.
 * Using this you can predefine configurations for use in different environments,
 * like _dev, test, staging and prod_.
 *
 * @see README.md
 */
class Environment
{
    /**
     * Inherit key that can be used in configConsole
     */
    const INHERIT_KEY = '@';

    /**
     * @var string name of env var to check
     */
    protected $envVar = 'YII_ENV';

    /**
     * @var string selected environment mode
     */
    protected $mode;

    /**
     * @var string filename where mode is stored
     */
    public $modeFileName = 'mode.php';

    /**
     * @var string config dir
     */
    protected $configDir;

    /**
     * @var string path to yii.php
     */
    public $yiiPath;

    /**
     * @var string yii environment
     */
    public $yiiEnv;

    /**
     * @var int debug level
     */
    public $yiiDebug;

    /**
     * @var array
     */
    public $aliases = [];

    /**
     * @var array
     */
    public $classMap = [];

    /**
     * @var array web config array
     */
    public $configWeb;

    /**
     * @var array console config array
     */
    public $configConsole;

    /**
     * Extend Environment class and merge parent array if you want to modify/extend these
     * @return array list of valid modes
     */
    protected function getValidModes()
    {
        return array(
            100 => 'dev',
            200 => 'test',
            300 => 'staging',
            400 => 'prod'
        );
    }

    /**
     * Initializes the Environment class with the given mode
     * @param string $mode used to override automatically setting mode
     * @param string $configDir override default configDir
     */
    public function __construct($mode = null, $configDir = null)
    {
        $this->setConfigDir($configDir);
        $this->setMode($mode);
        $this->setEnvironment();
    }

    /**
     * Set config dir.
     * @param string $configDir
     */
    protected function setConfigDir($configDir)
    {
        if ($configDir !== null) {
            $this->configDir = rtrim($configDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        } else {
            $this->configDir = '.' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Set environment mode, if valid mode can be determined.
     * @param string $mode if left empty, determine automatically
     * @throws \Exception
     */
    protected function setMode($mode = null)
    {
        // If not overridden, determine automatically
        if ($mode === null) {
            $mode = $this->determineMode();
        }

        // Check if mode is valid
        $mode = strtolower($mode);
        if (!in_array($mode, $this->getValidModes(), true)) {
            throw new \Exception('Invalid environment mode supplied or selected: ' . $mode);
        }

        $this->mode = $mode;
    }

    /**
     * Determine current environment mode depending on environment variable.
     * Also checks if there is a mode file that might override this environment.
     * Override this function if you want to implement your own method.
     * @return string mode
     * @throws \Exception
     */
    protected function determineMode()
    {
        if (file_exists($this->getModeFilePath())) {
            // Is there a mode file?
            $mode = trim(file_get_contents($this->getModeFilePath()));
        } else {
            // Else, return mode based on environment var
            $mode = getenv($this->envVar);
            if ($mode === false) {
                throw new \Exception('"Environment mode cannot be determined, see class for instructions.');
            }
        }
        return $mode;
    }

    /**
     * @return string mode file path
     */
    protected function getModeFilePath()
    {
        return $this->configDir . $this->modeFileName;
    }

    /**
     * Load and merge config files into one array.
     * @return array $config array to be processed by setEnvironment.
     * @throws \Exception
     */
    protected function getConfig()
    {
        // Load main config
        $fileMainConfig = $this->configDir . 'main.php';
        if (!file_exists($fileMainConfig)) {
            throw new \Exception('Cannot find main config file "' . $fileMainConfig . '".');
        }
        $configMain = require($fileMainConfig);

        // Load specific config
        $fileSpecificConfig = $this->configDir . 'mode_' . strtolower($this->mode) . '.php';
        if (!file_exists($fileSpecificConfig)) {
            throw new \Exception('Cannot find mode specific config file "' . $fileSpecificConfig . '".');
        }
        $configSpecific = require($fileSpecificConfig);

        // Merge specific config into main config
        $config = self::mergeArray($configMain, $configSpecific);

        // If one exists, load and merge local config
        $fileLocalConfig = $this->configDir . 'local.php';
        if (file_exists($fileLocalConfig)) {
            $configLocal = require($fileLocalConfig);
            $config = self::mergeArray($config, $configLocal);
        }

        // Return
        return $config;
    }

    /**
     * Sets the environment and configuration for the selected mode.
     */
    protected function setEnvironment()
    {
        $config = $this->getConfig();

        // Set attributes
        $this->yiiPath = $config['yiiPath'];
        $this->yiiEnv = isset($config['yiiEnv']) ? $config['yiiEnv'] : $this->mode;
        $this->yiiDebug = isset($config['yiiDebug']) ? $config['yiiDebug'] : false;

        $this->configWeb = $config['configWeb'];
        $this->configWeb['params']['environment'] = strtolower($this->mode);

        // Set console attributes and related actions
        if (isset($config['configConsole']) && !empty($config['configConsole'])) {
            $this->configConsole = $config['configConsole'];
            $this->processInherits($this->configConsole); // Process configConsole for inherits
            $this->configConsole['params']['environment'] = strtolower($this->mode);
        }

        $this->classMap = isset($config['classMap']) ? $config['classMap'] : [];
        $this->aliases = isset($config['aliases']) ? $config['aliases'] : [];
    }

    /**
     * Defines Yii constants, includes base yii class and sets aliases and classmap
     */
    public function setup()
    {
        //set yii constants
        defined('YII_DEBUG') or define('YII_DEBUG', $this->yiiDebug);
        defined('YII_ENV') or define('YII_ENV', $this->yiiEnv);
        //include yii
        require_once($this->yiiPath);
        //set aliases and classmap
        foreach ($this->aliases as $alias => $path) {
            \Yii::setAlias($alias, $path);
        }
        \Yii::$classMap = array_merge(\Yii::$classMap, $this->classMap);
    }

    /**
     * Show current Environment class values
     */
    public function showDebug()
    {
        echo '<div style="position: absolute; bottom: 0; left: 0; z-index: 99999; height: 250px; overflow: auto;
            background-color: #ddd; color: #000; border: 1px solid #000; margin: 5px; padding: 5px;">
            <pre>' . htmlspecialchars(print_r($this, true)) . '</pre>
        </div>';
    }

    /**
     * Merges two arrays into one recursively.
     * @param array $a array to be merged to
     * @param array $b array to be merged from
     * @return array the merged array (the original arrays are not changed.)
     *
     * Taken from Yii's CMap::mergeArray, since php does not supply a native
     * function that produces the required result.
     * @see http://www.yiiframework.com/doc/api/1.1/CMap#mergeArray-detail
     */
    protected static function mergeArray($a, $b)
    {
        foreach ($b as $k => $v) {
            if (is_integer($k)) {
                $a[] = $v;
            } elseif (is_array($v) && isset($a[$k]) && is_array($a[$k])) {
                $a[$k] = self::mergeArray($a[$k], $v);
            } else {
                $a[$k] = $v;
            }
        }
        return $a;
    }

    /**
     * Loop through console config array, replacing values called 'inherit' by values from $this->configWeb
     * @param array $array target array
     * @param array $path array that keeps track of current path
     */
    private function processInherits(&$array, $path = array())
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->processInherits($value, array_merge($path, array($key)));
            }

            if ($value === self::INHERIT_KEY) {
                $value = $this->getValueFromArray($this->configWeb, array_reverse(array_merge($path, array($key))));
            }
        }
    }

    /**
     * Walk $array through $path until the end, and return value
     * @param array $array target
     * @param array $path path array, from deep key to shallow key
     * @return mixed
     */
    private function getValueFromArray(&$array, $path)
    {
        if (count($path) > 1) {
            $key = end($path);
            return $this->getValueFromArray($array[array_pop($path)], $path);
        } else {
            return $array[reset($path)];
        }
    }
}
