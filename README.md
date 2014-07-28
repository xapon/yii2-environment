yii2-environment [![Latest Stable Version](https://poser.pugx.org/xapon/yii2-environment/v/stable.svg)](https://packagist.org/packages/xapon/yii2-environment)
===============

Environment-based configuration class (for example in development, testing, staging and production) for Yii2 basic application template.

It is used to set configuration and debugging depending on environment.
You can predefine configurations for use in different environments,
like _development, testing, staging and production_.

This library is mainly based on [Environment extension for Yii 1](https://github.com/marcovtwout/yii-environment) by [Marco van 't Wout](https://github.com/marcovtwout). Those who have accustomed with it's previous version could easily use this version with Yii2. 
It is probably useless for `advanced application template` since environment management for it has already been provided by framework developers. Nevertheless, I don't like advanced template for several reasons but do want to handle environment configurations in basic template.

The main config (`main.php`) is extended to include the Yii path and debug flags.
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

## Requirements

Tested on current (unstable) Yii2,  and will probably work after final release (I'll keep track on it's updates to be sure).

## Installation

### Installation via Composer

The preferred installation method is through [Composer](https://getcomposer.org/).
We assume the vendor directory is placed in application root.

1. Add the dependency to your project
    
    ```
    php composer.phar require xapon/yii2-environment "1.*"
    ```

2. Modify your `web/index.php` (and other bootstrap files)
3. Modify your `main.php` config file and add mode specific config files
4. Set your local environment (see next section)

### Installation from zip file

See the previous instructions: https://github.com/xapon/yii2-environment/tree/1.0.0#installation

### Setting environment

Here are some examples for setting your environment to `DEV`.

#### Windows

1. Go to: Control Panel > System > Advanced > Environment Variables
2. Add new SYSTEM variable: name = `YII_ENV`, value = `DEV`
 * Details: http://support.microsoft.com/kb/310519/en-us

#### Linux/Mac

1. Open profile files:
 * Global bash shell: `/etc/profile`
 * Apache (as service): `/etc/apache2/envvars`
2. Add the following line: `export YII_ENV="DEV"`
 * Details: http://www.cyberciti.biz/faq/linux-unix-set-java_home-path-variable/

#### Apache only (cannot be used for console applications)

1. Check if mod_env is enabled
2. Open your `httpd.conf` or create a `.htaccess` file
3. Add the following line: `SetEnv YII_ENV DEV`
 * Details: http://httpd.apache.org/docs/1.3/mod/mod_env.html#setenv

#### Project only

1. Create a file `mode.php` in the config directory of your application.
2. Set the content of the file to: `DEV`

## Usage

### Update bootstrap files

See [example-index/index.php](example-index/index.php)

### Structure of config directory

Your `config/` directory will look like this:

```
config/main.php                     (Global configuration)
config/mode_dev.php         (Environment-specific configurations)
config/mode_test.php
config/mode_staging.php
config/mode_prod.php
config/local.php                    (Optional, local override for mode-specific config. Don't put in your VCS!)
```

### Modify your config/main.php

See [example-config/main.php](example-config/main.php)

Optional: in configConsole you can copy settings from configWeb by using value key `@` (see examples folder).

### Create mode-specific config files

Create `config/mode_<mode>.php` files for the different modes. These will override or merge attributes that exist in the main config.

- See [example-config/mode_dev.php](example-config/mode_dev.php)
- See [example-config/mode_test.php](example-config/mode_test.php)
- See [example-config/mode_staging.php](example-config/mode_staging.php)
- See [example-config/mode_prod.php](example-config/mode_prod.php)

Optional: also create a `config/local.php` file for local overrides.

## Credits

Original library for Yii 1: [https://github.com/marcovtwout/yii-environment](https://github.com/marcovtwout/yii-environment)


## Notes

- I tried to keep the library as much compatible with it's previous version as possible. Still, I have introduced some changes.
    - First, in order to 'inherit' configuration from `configWeb` in `configConsole` you should use `@` symbol instead of `inherit` keyword (that looks much shorter!). 
    - Second, in order to keep the names of environments uniformly with Yii2, I have changed `development` mode to `dev`, `production` to `prod`, and `testing` to `test`. There're now handy boolean constants like `YII_ENV_DEV` set up for you by Yii, and this library makes sure they're defined according to selected `mode`.

- This is probably my first project on github, so I gladly expect issue reports, pull requests, any help, comments or criticism.