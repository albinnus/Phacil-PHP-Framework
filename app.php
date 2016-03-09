<?php

require LIB_DIR . 'config.inc.php';

Router::add('GET', '/', ['Statics']);

Router::run();
