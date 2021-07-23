<?php
require_once 'env.php';
/**
 * Configure the Error handler used to handle errors for your application. By default
 * ErrorHandler::handleError() is used. It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callable type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `level` - integer - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
Configure::write('Error', array(
        'handler' => 'ErrorHandler::handleError',
        'level' => E_ALL & ~E_DEPRECATED,
        'trace' => true
));

if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/web-api') !== false)
{
    Configure::write('Exception.handler', function ($error) 
    {
        $response = array("status" => 0, "msg" => $error->getMessage() . " in " . $error->getFile() . " at Line " . $error->getLine() );
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
        echo json_encode($response);
    });
}
else
{
    Configure::write('Exception', array(
        'handler' => 'ErrorHandler::handleException',
        'renderer' => 'ExceptionRenderer',
        'log' => true
    ));
}

Configure::write('App.encoding', 'UTF-8');

Configure::write('Session', array(
    'defaults' => 'cake',
    'timeout' => 57600 // 8 days
));

$path = APP . "tmp" . DS . "sessions";
if (!file_exists($path))
{
    mkdir($path, 0777, TRUE);    
}

/**
 * A random string used in security hashing methods.
 */
Configure::write('Security.salt', 'DYhG93b0qyJfIxfs2guVodsfdsfsdfUubWwvniR2G0FgaC9mi');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
Configure::write('Security.cipherSeed', '768593096574535sdfsdfsdfsd42496749683645');

Configure::write('Acl.classname', 'CacheDbAcl');
Configure::write('Acl.database', 'default');

date_default_timezone_set('Asia/Kolkata');


$engine = 'File';

// In development mode, caches should expire quickly.
$duration = '+999 days';
if (Configure::read('debug') > 0) {
    $duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$prefix = 'myapp_';

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));
Configure::write('Routing.prefixes', array('admin'));
Configure::write('Cache.check', true);