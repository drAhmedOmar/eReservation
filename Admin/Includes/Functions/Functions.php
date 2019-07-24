<?php

    /*Title Change According To Page */
    function PageTitle(){
        global $PageTitle;
        if(isset($PageTitle)){
            $Title = $PageTitle;
        }else{
            $Title = "Default";
        }
        return $Title;
    }

    /*Mysql General Query Function */
    function GetAllData($select, $from, $where = null, $value = null, $and = null){
        global $conn;
        if($where == null || $where == ""){
            $where = null;
            $value = null;
        }else{
            $where = $where;
            $value = $value;
        }
        $stmt    = $conn->prepare("SELECT $select FROM $from $where $and");
        $stmt->execute(array($value));
        $Alldata = $stmt->fetchAll();
        return $Alldata;
    }

    /*Redirect Page */
    function redirectpage($message, $location = null, $time){
        $locate = '';
        if($location != null && $location == 'back'){
            if(isset($_SERVER['HTTP_REFERER'])){
                $locate = $_SERVER['HTTP_REFERER'];
            }else{
                $locate = 'Dashboard.php';
            }
        }elseif($location != null && $location != 'back'){
            $locate = $location;
        }else{
            $locate = 'Dashboard.php';
        }
        header("refresh:$time;url=$locate");
        echo $message;
        echo "<div class='alert alert-info'>You Will Be Directed In $time Seconds</div>";
    }


    /*Select Count */
    function counts($column, $from, $where = null){
        global $conn;
        $stat = $conn->prepare("SELECT COUNT($column) FROM $from $where");
        $stat->execute();
        $column =  $stat->fetchColumn();
        return $column;
    }















