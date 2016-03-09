<?php

class Theme{
    
    protected static $name = 'default';
    
    public static function getName(){
        return self::$name;
    }
        	
    public static function loadView($viewPath, $view, $vars){		
        if(!ob_start("ob_gzhandler")) ob_start();
        foreach($vars as $var => $value){
            if(!isset($$var)){
                $$var = $value;  
            } 
        }
        
        include($viewPath . $view . '.htp');
        $file_content = ob_get_contents();
        ob_end_clean ();	
        return $file_content;
    }
	
    public static function includeLayout($layout = '', $content = '', $vars = ''){
        foreach($vars as $var => $value){
            if(!isset($$var)){
                $$var = $value;  
            }
        }
        
        include THEMES_DIR . self::$name . DS . $layout. '.htp';
    }
    
    public static function includeLayoutViewOnTheme($layout = null, $viewPath = null, $view = null, $vars = array()){
        $content = self::loadView($viewPath, $view, $vars);
        self::includeLayout($layout, $content, $vars);
    }
    
}
