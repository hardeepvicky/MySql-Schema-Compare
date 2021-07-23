<?php
Router::connect('/', array('controller' => 'users', 'action' => 'login'));

CakePlugin::routes();
require CAKE . 'Config' . DS . 'routes.php';
