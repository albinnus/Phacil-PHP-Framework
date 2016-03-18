<?php
/**
 * Description of DB
 *
 * @author alisson
 */
class DB {
        
    private static $dbConfigs = array();
    public static $dbConfig = 'default';    
    
    private static function __setDbConfig($var = 'default'){
        
        $dbConfig = Database::$$var;
        
        $pdo = new PDO("{$dbConfig['driver']}:host={$dbConfig['host']};dbname={$dbConfig['database']}", $dbConfig['user'], $dbConfig['password']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);

        return new FluentPDO($pdo);
    }

    public static function __callStatic($name, $arguments) {
        
        if(!isset(self::$dbConfigs[self::$dbConfig])){
            self::$dbConfigs[self::$dbConfig] = self::__setDbConfig(self::$dbConfig);
        }
        
        $connection = self::$dbConfigs[self::$dbConfig];
        
        return call_user_func_array(array($connection,$name), $arguments);
    } 
}
