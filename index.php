<?php
session_start();

// error reporting
ini_set('display_errors', true);
error_reporting(1);

// autoload dependencies automatically via magical composer autoload
require_once 'vendor/autoload.php';

// website configuration file
require_once 'boot.php';

// db configuration file
require_once 'config.php';

// flight framework
require 'core/flight/Flight.php';

// custom functions
require_once 'func.php';

// autoload classes
require_once('autoload.php');

// error logging
if ($config['log_errors']) {
    Flight::set('flight.log_errors', true);
    $logFile = fopen($config['log_path'] . 'applog.log', 'a+');

    Flight::map(
       'error',
       function (Exception $ex) use ($logFile) {
           $message = date('d-m-Y h:i:s') . PHP_EOL . $ex->getTraceAsString() . PHP_EOL . str_repeat(
                 '-',
                 80
              ) . PHP_EOL . PHP_EOL;

           fwrite($logFile, $message);
           fclose($logFile);
       }
    );
}

// set config
Flight::set('config', $config);

// view path
Flight::set('flight.views.path', 'app/views/');

// set base path variable to be used in setting css js files in views
$request = (array) Flight::request();
Flight::set('base', $request['base']);
Flight::set('controller', $request['url']);
Flight::set('lastSegment', end(explode('/', $request['url'])));

// connect configuration
$database = $_SESSION['db'] ? $_SESSION['db'] : $config['database_dbname'];

ORM::configure('mysql:host=' . $config['database_host'] . ';dbname=' . $database);
ORM::configure('username', $config['database_user']);
ORM::configure('password', $config['database_password']);
$db = ORM::get_db();

// enable query logging
ORM::configure('logging', true);

Flight::set('db', $db);
Flight::set('dbname', $database);

$stmt = $db->query("SHOW TABLES FROM " . Flight::get('dbname'));
$data = $stmt->fetchAll(PDO::FETCH_NUM);
$data = arrayFlatten($data);

// create table names json file
$json = array();
foreach ($data as $datakey => $datavalue) {
    $json[]['word'] = $datavalue;
}

@file_put_contents('tables.json', json_encode($json));

$tables = Presenter::listTables($data);
Flight::set('tables', $tables);

if (false !== strpos($_SERVER['REQUEST_URI'], '/table')) {
    $currentTableKey = array_search(Flight::get('lastSegment'), $data, true);
    unset($data[$currentTableKey]); // remove current table

    // make dropdown options
    Flight::set('tablesOptions', getOptions($data));
}

// get an array of databases
$stmt = $db->query("SHOW DATABASES");
$data = $stmt->fetchAll(PDO::FETCH_NUM);
$data = arrayFlatten($data);
Flight::set('databaseOptions', getOptions($data, true, null, $database));

// setup custom 404 page
Flight::map(
   'notFound',
   function () {
       //include 'errors/404.html';
       header("HTTP/1.0 404 Not Found");
       exit('404 Not Found');
   }
);

// set global variables
Flight::set('appname', $config['appname']);

///////// setup routes /////////////
require_once 'routes.php';

// auto-logout after inactivity for 10 minutes
timeoutLogout(60);

// flight now
Flight::start();

//TODO: dropdown in header to select a different database
//TODO: ability to edit recordset in place
//TODO: tutorial of VisualQuery