<?php

require_once 'functions.inc.php';

/** nullify any existing autoloads */
spl_autoload_register(null, false);

/** specify extensions that may be loaded */
spl_autoload_extensions('.php, .class.php, .inc.php');

/*** register the loader functions */
spl_autoload_register(function($class_name){
    
            if(is_file(LIB_DIR . 'Core' . DS . $class_name. '.php')){
               require(LIB_DIR . 'Core' . DS . $class_name. '.php');
    }else   if(is_file(LIB_DIR . 'Core' . DS . $class_name. DS . $class_name. '.php')){
               require(LIB_DIR . 'Core' . DS . $class_name. DS . $class_name. '.php');
    }else   if(is_file(CONFIG_DIR . $class_name. '.php')){
               require(CONFIG_DIR . $class_name. '.php');           
    }else   if(is_file(LIB_DIR . 'External'. DS . $class_name. '.php')){
               require(LIB_DIR . 'External'. DS . $class_name. '.php');
    }else   if(is_file(LIB_DIR . 'External'. DS . $class_name. DS . $class_name. '.php')){
               require(LIB_DIR . 'External'. DS . $class_name. DS . $class_name. '.php');
    }else{
        require_once(ROOT. str_replace('\\', DS, $class_name . '.php'));
    }    
});

