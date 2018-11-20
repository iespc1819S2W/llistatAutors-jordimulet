<?php
define("url", "localhost");
define("user", "root");
define("pass", "1234");
define("database", "biblioteca");

function conectar(){ 
    $mysqli = new mysqli(url, user, pass, database);
    $mysqli->set_charset("utf8mb4");
        
    if (!$mysqli){ 
        echo "Connection error: ". mysqli_connect_error(); 
        exit();
    }
    return $mysqli;
}

function desconectar($mysqli){
    $mysqli->close();
}
?>