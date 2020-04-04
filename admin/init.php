<?php
    include 'connect.php';


    // Routes
    
    $func = 'includes/functions/';
    $templates = 'includes/templates/';
    $css = 'themes/Default/css/';
    $js = 'themes/Default/js/';
    $lang = 'includes/languages/';


    include $func . 'functions.php';
    include $lang . 'en.php';
    include $templates . 'header.php';
    if (!isset($noNavbar)) {
    	include $templates . 'navbar.php';
    }

    