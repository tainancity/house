<?php

//Router::connect('/', array('controller' => 'doors', 'action' => 'index'));
Router::connect('/', array('controller' => 'tasks', 'action' => 'index'));//首頁預設畫面
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';
