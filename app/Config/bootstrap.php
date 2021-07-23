<?php
Cache::config('acl_config', array(
    'engine' => 'File',
    'duration' => '+366 days',
    "groups" => array("Acl"),
    'path' => CACHE,
    'prefix' => ''
));

Cache::config('month', array(
    'engine' => 'File',
    'duration' => '+1 months',
    'path' => CACHE,
    'prefix' => 'month_'
));

Cache::config('week', array(
    'engine' => 'File',
    'duration' => '+1 week',
    'path' => CACHE,
    'prefix' => 'week_'
));

Cache::config('day', array(
    'engine' => 'File',
    'duration' => '+1 days',
    'path' => CACHE,
    'prefix' => 'day_'
));

Cache::config('hour', array(
    'engine' => 'File',
    'duration' => '+1 hours',
    'path' => CACHE,
    'prefix' => 'hour_'
));

Cache::config('hour_3', array(
    'engine' => 'File',
    'duration' => '+3 hours',
    'path' => CACHE,
    'prefix' => 'hour_3_'
));

Cache::config('hour_6', array(
    'engine' => 'File',
    'duration' => '+6 hours',
    'path' => CACHE,
    'prefix' => 'hour_6_'
));

Cache::config('min_15', array(
    'engine' => 'File',
    'duration' => '+15 mintues',
    'path' => CACHE,
    'prefix' => 'min_15_'
));


Cache::config('default', array('engine' => 'File'));

Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

require_once('functions.php');
require_once('constants.php');
require_once('static_array.php');
require_once('icon_constants.php');

require_once VENDORS . '/hardeep-vicky/php-query-builder/Where.php';