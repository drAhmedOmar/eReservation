<?php

    include "connect.php";

    //Routes
    $langs  = "Includes/languages/";
    $func   = "Includes/functions/";
    $tpl    = "Includes/templates/";
    $css    = "Layout/css/";
    $js     = "Layout/js/";

    //Includes
    include $langs . "english.php";
    include $func . "function.php";
    include $tpl . "header.php";

    //Nonavbar
    if(! isset($Nonavbar)){
        include $tpl . "navbar.php";
    }
