<?php

ob_start();
session_start();

$Nonavbar = "";

include "init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Check If Data From Login By Submit Name
    if (isset($_POST['login'])) {
        $username = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $userpass = sha1($_POST['password']);

        $stmt = $conn->prepare("SELECT * FROM customers WHERE Name = ? And Password = ?");
        $stmt->execute(array($username, $userpass));
        $cost_data = $stmt->fetchAll();
        $count = $stmt->rowCount();
        foreach ($cost_data as $cost) {
            $cost_Id = $cost['Id'];
        }
        if ($count > 0) {
            $_SESSION['cost_name'] = $username;
            $_SESSION['Id'] = $cost_Id;
            header('location:index.php');
        }
        //Check If Data From Signup By Submit Name
    } elseif (isset($_POST['signup'])) {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['password']);
        $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT);

        $stat = $conn->prepare("SELECT * FROM customers WHERE Name = ?");
        $stat->execute(array($name));
        $cont = $stat->rowCount();
        if($cont > 0){
            echo "Sorry Name Is Unavailable";
        }else{
            $stmt = $conn->prepare("INSERT INTO customers (Name, Password, Mobile)
                                    VALUES ('$name', '$pass', '$mobile')
                                    ");
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count > 0){
                echo "Registered Successfully";
            }else{
                echo "Failed To Registered";
            }
        }
        
    }
}

$do = isset($_GET['do']) ? $_GET['do'] : 'login';

echo "<div class='container'>";
echo '<h1 class="text-center"><a href="?do=login">Login</a> | <a href="?do=signup">Signup</a></h1>';

//==============Login=================
if ($do == 'login') {
    ?>
    <div class="login-f">
        <form class="form-horizontal text-center" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <!--Name -->
            <div class="form-group row">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="name" class="form-control" placeholder="Name" required="required" qutocomplete="off" />
                </div>
            </div>
            <!--Password -->
            <div class="form-group row">
                <label class="col-sm-3 control-label">Password</label>
                <div class="col-md-6 col-sm-10">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="required" />
                </div>
            </div>
            <!--Submit -->
            <div class="form-group row">
                <div class="offset-2 col-sm-3">
                    <input type="submit" value="Login" name="login" class="btn btn-primary btn-sm" />
                </div>
            </div>
            <!-- -->
        </form>
    </div>
<?php
    //============Signup====================
} elseif ($do == 'signup') {
    ?>
    <div class="signup-f">
        <form class="form-horizontal text-center" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <!--Name -->
            <div class="form-group row">
                <label class="col-sm-3 control-label">Name</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="name" class="form-control" placeholder="Name" required="required" autocomplete="off" />
                </div>
            </div>
            <!--Password -->
            <div class="form-group row">
                <label class="col-sm-3 control-label">Password</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="password" class="form-control" placeholder="Password" required="required" autocomplete="off" />
                </div>
            </div>
            <!--Mobile-->
            <div class="form-group row">
                <label class="col-sm-3 control-label">Mobile</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" name="mobile" class="form-control" placeholder="mobile" required="required" autocomplete="off" />
                </div>
            </div>
            <!--Submit -->
            <div class="form-group row">
                <div class="offset-md-2 col-md-3">
                    <input type="submit" value="Save" name="signup" class="btn btn-success btn-sm" />
                </div>
            </div>
            <!-- -->
        </form>
    </div>

<?php
}
echo "</div>";

include $tpl . "footer.php";

ob_end_flush();
