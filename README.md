yii-environment
===============

Environment-based configuration class (for example in development, testing, staging and production).

Simple class used to set configuration and debugging depending on environment.
Using this you can predefine configurations for use in different environments,
like _development, testing, staging and production_.

The main config (`main.php`) is extended to include the Yii paths and debug flags.
There are `mode_<environment>.php` files for overriding and extending main.php for specific environments.
Additionally, you can overrride the resulting config by using a `local.php` config, to make
changes that will only apply to your specific installation.

This class was designed to have minimal impact on the default Yii generated files.
Minimal changes to the index/bootstrap and existing config files are needed.

The Environment is determined with PHP's getenv(), which searches $_SERVER and $_ENV.
There are multiple ways to set the environment depending on your preference.
Setting the environment variable is trivial on both Windows and Linux, instructions included.
You can optionally override the environment by creating a `mode.php` in the config directory.

If you want to customize this class or its config and modes, extend it! (see [ExampleEnvironment.php](ExampleEnvironment.php))

## Installation

1. Put the yii-environment directory in `protected/extensions/`
2. Modify your `index.php` (and other bootstrap files)
3. Modify your `main.php` config file and add mode specific config files
4. Set your local environment (see next section)

### Setting environment

Here are some examples for setting your environment to `DEVELOPMENT`.

#### Windows

1. Go to: Control Panel > System > Advanced > Environment Variables
2. Add new SYSTEM variable: name = `YII_ENVIRONMENT`, value = `DEVELOPMENT`
 * Details: http://support.microsoft.com/kb/310519/en-us

#### Linux

1. Open your profile file:
 * Locally: `~/.profile` or `~/.bash_profile` (exact filename depends on your linux distro)
 * Globally: `/etc/profile`
 * Apache: `/etc/apache2/envvars` (if apache process doesn't use bash shell, try this file)
2. Add: `export YII_ENVIRONMENT="DEVELOPMENT"`
 * Details: http://www.cyberciti.biz/faq/linux-unix-set-java_home-path-variable/

#### Apache only (cannot be used for console applications)

1. Check if mod_env is enabled
 * Modify your `httpd.conf` or create a `.htaccess` file
 * Add: `SetEnv YII_ENVIRONMENT DEVELOPMENT`
 * Details: http://httpd.apache.org/docs/1.3/mod/mod_env.html#setenv

#### Project only

1. Create a file `mode.php` in the config directory of your application.
2. Set the contents of the file to: `DEVELOPMENT`

#### Problems?

* Q: After setting environment var, I get "Environment cannot be determined" when accessing the web application.
* A: Make sure that where the Apache process starts, it can access the environment variable (by setting it as a system/global var).

## Usage

### Index.php usage example:

See [example-index/index.php](example-index/index.php) or use the following code block:

```php
// set environment
require_once(dirname(__FILE__) . '/protected/extensions/yii-environment/Environment.php');
$env = new Environment();
//$env = new Environment('PRODUCTION'); //override mode

// set debug and trace level
defined('YII_DEBUG') or define('YII_DEBUG', $env->yiiDebug);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $env->yiiTraceLevel);

// run Yii app
//$env->showDebug(); // show produced environment configuration
require_once($env->yiiPath);
Yii::createWebApplication($env->configWeb)->run();
```

### Structure of config directory

Your `protected/config/` directory will look like this:

```
config/main.php                     (Global configuration)
config/mode_development.php         (Environment-specific configurations)
config/mode_test.php
config/mode_staging.php
config/mode_production.php
config/local.php                    (Optional, local override for mode-specific config. Don't put in your SVN!)
```

### Modify your config/main.php

See [example-config/main.php](example-config/main.php) or use the following code block:

```php
return array(
    // Set yiiPath (relative to Environment.php)
    'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
    'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
    'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => true,
    'yiiTraceLevel' => 0,

    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => array(
        (...)
    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(
        (...)
    ),
);
```

Optional: in configConsole you can copy settings from configWeb by
using value key `inherit` (see examples folder).

### Create mode-specific config files

Create `config/mode_<mode>.php` files for the different modes. These will override or merge attributes that exist in the main config.

Optional: also create a `config/local.php` file for local overrides

```php
return array(
    // Set yiiPath (relative to Environment.php)
    //'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
    //'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
    //'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => true,
    'yiiTraceLevel' => 0,

    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => array(
        (...)
    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used
    // Use value 'inherit' to copy from generated configWeb
    'configConsole' => array(
        (...)
    ),
);
```

## Credits

Original sources: http://www.yiiframework.com/doc/cookbook/73/
