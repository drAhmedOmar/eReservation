<?php

    ob_start();

    session_start();

    session_unset();

    session_destroy();

    header('location:Index.php');
    exit();

    ob_end_flush();