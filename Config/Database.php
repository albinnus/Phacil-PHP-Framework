<?php
/**
 * Description of database
 *
 * @author alisson
 */
class Database {
    
    public static $default = array(
        'driver' => "driver",
        'host' => "host",     
        'database' => "database",
        'username' => "user",
        'password' => "",
        // 'port' => "", 
       // 'charset' => 'utf-8',
       // 'prefix' => '',
    );
    
    public static $dev = array(
        'driver' => "driver",
        'host' => "localhost",     
        'database' => "database",
        'user' => "user",
        'password' => "",
        'port' => "", 
    );
    
}
