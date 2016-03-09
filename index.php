<?php
/**
 * 
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * 
 */
define('ROOT', dirname(__FILE__) . DS);

/**
 * 
 */
define('ROOT_URL', str_replace(basename($_SERVER['SCRIPT_NAME']), '' , $_SERVER['SCRIPT_NAME']));

/**
 * 
 */
define('LIB_FOLDER', 'Lib');

/**
 * 
 */
define('LIB_DIR', ROOT . LIB_FOLDER . DS);

/**
 * 
 */
define('CONFIG_FOLDER', 'Config');

/**
 * 
 */
define('CONFIG_DIR', ROOT . CONFIG_FOLDER . DS);

/**
 * 
 */
define('THEMES_FOLDER', 'Themes');

/**
 * 
 */
define('THEMES_DIR', ROOT . THEMES_FOLDER . DS);

/**
 * 
 */
define('THEMES_URL', ROOT_URL . THEMES_FOLDER . DS);

/**
 * 
 */
define('BUSINESS_FOLDER', 'Business');

/**
 * 
 */
define('BUSINESS_DIR', ROOT . BUSINESS_FOLDER . DS);

/**
 * 
 */
define('BUSINESS_URL', ROOT_URL . BUSINESS_FOLDER . DS);

/**
 * 
 */
require ROOT . 'app.php';


