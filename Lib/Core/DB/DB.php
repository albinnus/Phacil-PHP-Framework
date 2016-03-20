<?php
/**
 * Description of DB
 *
 * @author alisson
 */

require 'QueryBuilder.php';

class DB {
        
    private static $dbConfigs = array();
    public static $dbConfig = 'default';    
    
    private static function __setDbConfig($var = 'default'){
        
        $dbConfig = Database::$$var;
        
        return new QueryBuilder($dbConfig);
    }

    public static function __callStatic($name, $arguments) {
        
        if(!isset(self::$dbConfigs[self::$dbConfig])){
            self::$dbConfigs[self::$dbConfig] = self::__setDbConfig(self::$dbConfig);
        }
        
        $connection = self::$dbConfigs[self::$dbConfig];
        
        if(method_exists($connection, $name)){
            return call_user_func_array(array($connection, $name), $arguments);
        }else{
            $connection2 = call_user_func_array(array($connection,'table'), (array) $name);
        
            $args = !empty($arguments)?$arguments:array('1', '1');

            return call_user_func_array(array($connection2,'where'), $args);
        }
    } 
}
