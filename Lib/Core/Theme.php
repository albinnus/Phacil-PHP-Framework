<?php

class Theme{
    
    protected static $name = 'default';
    protected static $asset_dir = 'assets';
    
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
    
    public static function css($param = array()) {
        $assets = null;
        
        if(!is_array($param)){
            $param = (array) $param;
        }
        
        foreach ($param as $asset) {
            
            if(empty($asset)) { continue;}
            
            $asset = ($asset[0] == '/')?$asset: '/css/' . $asset;
            
            $assets .= HTML::link()->href(THEMES_URL. self::$name . '/' . self::$asset_dir . $asset . '.css')
                                    ->rel('stylesheet')
                                    ->type('text/css')
                                    ->output(); 
            $assets .= "\n";
        }        
        return $assets;
    }
    
    public static function js($param = array()) {
        $assets = null;
        
        if(!is_array($param)){
            $param = (array) $param;
        }
        
        foreach ($param as $asset) {
            
            if(empty($asset)) { continue;}
            
            $asset = ($asset[0] == '/')?$asset: '/js/' . $asset;
            $assets .= HTML::script()->src(THEMES_URL. self::$name . '/' . self::$asset_dir . $asset . '.js')
                    ->type('text/javascript')
                    ->output(); 
            $assets .= "\n";
        }        
        return $assets;
    }
    
    public static function image($param = '') {
        return HTML::img()->src(THEMES_URL. self::$name . '/' . self::$asset_dir . '/images/' . $param);
    }
}
