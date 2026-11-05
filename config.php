<?php

$host='localhost';
$dbname='aa_security';
$dbusername='root';
$dbpass='';

try{

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die ("Connection failed: " . $e->getMessage());
}


?>