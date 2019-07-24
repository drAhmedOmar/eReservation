<?php

    $dsn        = "mysql:host=localhost;dbname=reserve";
    $user       = "root";
    $pass       = "";
    $options    = array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    );

    try{
        $conn = new PDO($dsn, $user, $pass, $options);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        'FAILED TO CONNECT ' . $e->getMessage();
    }