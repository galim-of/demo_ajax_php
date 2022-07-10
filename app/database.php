<?php
$hostname = getenv("DB_HOST");
$username = getenv("DB_USER");
$password = getenv("DB_PASSWORD");
$db = getenv("DB_NAME");
$port = getenv("DB_PORT");
$dbh = null;
try {
    // there i'm using charset=latin1 because with utf8 i get scribbles
    $dbh = new PDO("mysql:host=$hostname;port=$port;dbname=$db;charset=latin1", $username, $password);
} catch (PDOException $e) {
    print "can't connect to database: " . $e->getMessage();
    die();
}
