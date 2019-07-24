<?php

    ob_start();
    session_start();

    $Nonavbar = "";

    include "Init.php";
    
    /*If Admin Make Direction*/
    if(isset($_SESSION['username'])){
        header('location:Dashboard.php');
        exit();
    }

    /*Check Form Data And Make Sessions */
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $Filtername   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $Pass       = sha1($_POST['password']);
    
        /*General Query Mysql Function */
        $Alldata = GetAllData("*", "Customers", "WHERE Name = ?", $Filtername, "AND GroupType = 1");
        $UserName = "";
        $Password = "";
        foreach($Alldata as $Userdata){
            $UserName = $Userdata['Name'];
            $Password = $Userdata['Password'];
        }

        /*Check If Data Present In Database */
        if($Filtername == $UserName && $Pass == $Password){
            $_SESSION['username'] = $Filtername;
            header('location:Dashboard.php');
            exit();
        }else {
            header('location:Index.php');
            exit();
        }
    
    }
    
    ?>
    <!--Form To Login -->
    <div id="Form-log" class="index-form">
        <div class="container"></div>
            <h2 class="text-center">Login</h2>
            <div class="Login-Form text-center">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                    <!--Username -->
                    <div class="form-group">
                        <label class="control-form">User Name</label>
                        <input type="text" name="username" class="form-control" autocomplete="off" />
                    </div>
                    <!--Password -->
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" />
                    </div>
                    <!--Submit -->
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Form Login -->

    <?php
    
    include $tpl . "Footer.php";
    ob_end_flush();