<?php
date_default_timezone_set('America/Mexico_City');
chdir("..");
require getcwd() . '/vendor/autoload.php';

Config::init();

$Config = Config::get();

$db = new SQLite3('testing.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
// Create a table.
$db->query(
'CREATE TABLE IF NOT EXISTS "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR
  )'
);
// // Insert some sample data.
// $db->query('INSERT INTO "users" ("name") VALUES ("Karl")');
// $db->query('INSERT INTO "users" ("name") VALUES ("Linda")');
// $db->query('INSERT INTO "users" ("name") VALUES ("John")');
// // Get a count of the number of users
// $userCount = $db->querySingle('SELECT COUNT(DISTINCT "id") FROM "users"');
// echo("User count: $userCount\n");
// // Close the connection
// $db->close();
// exit;

$whoops = new Whoops\Run();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
$whoops->register();

$URI = $_SERVER['REQUEST_URI'];
$URI = parse_url('http://phproutingsystem.com'.$URI);
$URI = str_replace("/index.php", "", $URI["path"]);
$URI = str_replace("index.php", "", $URI);
$URI = str_replace("/", " ", $URI);
$URI = trim($URI);

$components = (strlen($URI) > 0) ? explode(" ", $URI) : array();
$namespace = "Controllers\\";
$controller = $Config->getVar("defaults.controller", "");
if(count($components) > 0) {
	$controller = $components[0];
	if($controller == "api") {
		$namespace .= "API\\";
		$controller = $components[1];
		$components = array_slice($components, 2);
	} else {
		$components = array_slice($components, 1);
	}
}
$_classController = $namespace.ucfirst($controller);
$_controller = new $_classController($components);