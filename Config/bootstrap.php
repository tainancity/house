<?php

CakePlugin::loadAll();
CakePlugin::load('Media', array('bootstrap' => true));
require App::pluginPath('Permissible') . 'Config/init.php';
