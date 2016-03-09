<?php

namespace Business\Statics;

class Statics extends \Business\Controller{

    public function index(){
        \View::set('theme_title', 'Index');
    }
    
}
?>
