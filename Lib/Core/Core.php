<?php
/**
 * Description of Core
 *
 * @author alisson
 */

class Core {
    
    protected static $__vars = array();
    
    public static function set($var, $value = ''){
        self::$__vars[$var] = $value;
    }
    
    public static function get($var){
        if(isset(self::$__vars[$var])){
            return self::$__vars[$var];
        }
        return false;
    }
}
