<?php
function dbConnect() {
    $dsn = 'mysql:dbname=mybbs;host=localhost;charset=utf8';
    $user = 'root';
    $pass = 'root';
    $dbh = new PDO($dsn, $user, $pass);
    return $dbh;
}