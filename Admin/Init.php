<?php

    include "Connect.php";

    //-----Routes---------
    $lang   = "Includes/Languages/";
    $func   = "Includes/Functions/";
    $tpl    = "Includes/Templates/";
    $Css    = "Layout/Css/";
    $Js     = "Layout/Js/";
    
    
    include $lang . "English.php";
    include $func . "Functions.php";
    include $tpl . "Header.php";

    if(! isset($Nonavbar)){
        include $tpl . "Navbar.php";
    }