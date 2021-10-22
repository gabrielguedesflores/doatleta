<?php

function getConnection(){

    $dsn = "mysql:host=sistemafdoatleta.fun;dbname=sist6299_fdoatleta";
    $user = "";
    $pass = "";

    // $dsn = "mysql:host=localhost;dbname=fdoatleta";
    // $user = "root";
    // $pass = "";

    try {
        $pdo = new PDO($dsn, $user, $pass);

        return $pdo;

    } catch (PDOException $ex) {
        
        echo 'Erro: ' . $ex->getMessage();

    }
}

