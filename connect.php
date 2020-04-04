<?php 

    $db = 'mysql:host=localhost;dbname=shop';
    $user = 'root';
    $pass = '';
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ); 

    try {
        $connect = new PDO($db, $user, $pass, $option);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo 'Connected Successfully ^_^';
    } catch(PDOEXCEPTION $e) {
        echo 'ERROR: ' . $e->getMessage();
    }

?>