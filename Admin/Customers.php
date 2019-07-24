<?php

ob_start();
session_start();

$PageTitle = "Customers";

include "Init.php";

if (isset($_SESSION['username'])) {
    $do = '';
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    } else {
        $do = 'Manage';
    }
    //=====================Manage Page=======================
    if ($do == 'Manage') {
        //Fetch Customer Data And Show Data In Table Where Customer Not An Admin
        $custData = GetAllData('*', 'Customers', 'WHERE GroupType != 1', '', '');
        ?>
        <h1 class="text-center">Manage Customers</h1>
        <div class="container">
            <div class="main-table">
                <table class="table table-bordered text-center">
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Sheep No.</th>
                        <th>Cutting type</th>
                        <th>Date</th>
                        <th>Control</th>
                    </tr>
                    <?php
                    foreach ($custData as $cust) {
                        echo "<tr>";
                        echo "<td>" . $cust['Id'] . "</td>";
                        echo "<td>" . $cust['Name'] . "</td>";
                        echo "<td>" . $cust['Mobile'] . "</td>";
                        echo "<td>" . $cust['SheepNumber'] . "</td>";
                        echo "<td>" . $cust['CuttingType'] . "</td>";
                        echo "<td>" . $cust['Date'] . "</td>";
                        echo "<td>";
                        echo "<a href='Customers.php?do=Edit&Cust-Id=" . $cust['Id'] . "' class='btn btn-primary btn-sm'>Edit</a> ";
                        echo "<a href='Customers.php?do=Delete&Cust-Id=" . $cust['Id'] . "' class='btn btn-danger btn-sm confirm'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="?do=Add" class="btn btn-primary">Add New Customer</a>
        </div>

    <?php
        //=====================Add Page=======================   
    } elseif ($do == 'Add') {
        ?>
        <div class="Add-Cust">
            <div class="container">
                <h1 class="text-center">Add New Customer</h1>
                <form class="form-horizontal text-center main-form" action="?do=Insert" method="POST">
                    <!--Name -->
                    <div class="form-group row">
                        <label class="col-sm-2">Name</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" class="form-control" name="name" placeholder="Customer Name" autocomplete="off" />
                        </div>
                    </div>
                    <!--Password -->
                    <div class="form-group row">
                        <label class="col-sm-2">Password</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" />
                        </div>
                    </div>
                    <!--Mobile -->
                    <div class="form-group row">
                        <label class="col-sm-2">Mobile</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" class="form-control" name="mobile" placeholder="Mobile Number" autocomplete="off" />
                        </div>
                    </div>
                    <!--Sheep Number -->
                    <div class="form-group row">
                        <label class="col-sm-2">Sheep Number</label>
                        <div class="col-md-8 col-sm-10">
                            <input type="text" name="sheep" class="form-control" placeholder="Sheep Number" />
                        </div>
                    </div>
                    <!--Cutting Type -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label">Cutting Type</label>
                        <div class="col-md-8 col-sm-10">
                            <select name="cutting" class="form-control">
                                <option value='1'>Full</option>
                                <option value='2'>4 Pieces</option>
                                <option value='3'>6 Pieces</option>
                                <option value='4'>8 Pieces</option>
                            </select>
                        </div>
                    </div>
                    <!--Submit -->
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="submit" value="Add Customer" class="btn btn-success btn-sm" />
                        </div>
                    </div>
                    <!--End Submit -->
                </form>
            </div>
        </div>


    <?php
        //=====================Insert Page=======================
    } elseif ($do == 'Insert') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Insert Page</h1>";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name   = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $pass1  = $_POST['password'];
            $pass   = sha1($_POST['password']);
            $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT);
            $sheep  = filter_var($_POST['sheep'], FILTER_SANITIZE_NUMBER_INT);
            $cut    = $_POST['cutting'];

            //Check Form Errors
            $formerrors = array();

            if (empty($name)) {
                $formerrors[] = "Enter Name";
            }
            if (empty($pass1)) {
                $formerrors[] = "Enter Password";
            }
            if (empty($mobile)) {
                $formerrors[] = "Enter Mobile number";
            }
            if (empty($formerrors)) {

                $stmt = $conn->prepare("INSERT INTO customers
                                                (`Name`, `Password`, `Mobile`, `SheepNumber`, `CuttingType`, `Status`, `GroupType`, `Date`)
                                            VALUES
                                                ('$name', '$pass', '$mobile', '$sheep', '$cut', '1', '0', now())
                                        ");
                $stmt->execute();
                if ($stmt) {
                    $message = "<div class='alert alert-success'>Customer has Been Added Successfully</div>";
                    redirectpage($message, 'Customers.php', '3');
                }
            } else {
                foreach ($formerrors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                $message = "<div class='alert alert-warning'>Enter Valid Data</div>";
                redirectpage($message, 'back', '3');
            }
        }
        echo "</div>";

        //=====================Edit Page=======================
    } elseif ($do == 'Edit') {
        $Cust_Id = isset($_GET['Cust-Id']) ? $_GET['Cust-Id'] : 'NNo';
        $Custdata = GetAllData('*', 'customers', 'WHERE Id = ?', $Cust_Id, '');
        foreach ($Custdata as $Cust) {
            $cid        = $Cust['Id'];
            $cname      = $Cust['Name'];
            $cpass      = $Cust['Password'];
            $cmobile    = $Cust['Mobile'];
            $csheep     = $Cust['SheepNumber'];
            $ccutting   = $Cust['CuttingType'];
        }
        /*
        echo $Cust_Id;
        print_r($Custdata);
        echo $cname . " " . $cmobile . " " . $csheep . " " . $ccutting;
        */
        ?>
        <div class="container">
            <h1 class="text-center">Edit Customer</h1>
            <div class="Edit-Cust text-center">
                <form class="form-horizontal main-form" action="?do=Update" method="POST">
                    <!--Hidden Password & Id-->
                    <input type="hidden" name="oldpass" class="form-control" value="<?php echo $cpass ?>" />
                    <input type="hidden" name="custid" class="form-control" value="<?php echo $cid ?>" />
                    <!--Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="name" class="form-control" value="<?php echo $cname ?>" />
                        </div>
                    </div>
                    <!--Password -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="password" name="password" class="form-control" placeholder="Add New Password Or Leave Blank" autocomplete="new-password" />
                        </div>
                    </div>
                    <!--Mobile -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Mobile</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="mobile" class="form-control" value="<?php echo $cmobile ?>" />
                        </div>
                    </div>
                    <!--Sheep Number -->
                    <div class="form-group row">
                        <label class="col-sm-3">Sheep Number</label>
                        <div class="col-md-6 col-sm-10">
                            <input type="text" name="sheep" class="form-control" value="<?php echo $csheep ?>" />
                        </div>
                    </div>
                    <!--Cutting type -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label">Cutting type</label>
                        <div class="col-md-6 col-sm-10">
                            <select name="cutting" class="form-control">
                                <option value="1" <?php if ($ccutting == '1') {
                                                        echo "selected";
                                                    } ?>>Full</option>
                                <option value="2" <?php if ($ccutting == '2') {
                                                        echo "selected";
                                                    } ?>>4 Pieces</option>
                                <option value="3" <?php if ($ccutting == '3') {
                                                        echo "selected";
                                                    } ?>>6 Pieces</option>
                                <option value="4" <?php if ($ccutting == '4') {
                                                        echo "selected";
                                                    } ?>>8 Pieces</option>
                            </select>
                        </div>
                    </div>
                    <!--Submit -->
                    <div class="form-group row">
                        <div class="offset-2 col-sm-4">
                            <input type="submit" value="Save Changes" class="btn btn-success btn-sm" />
                        </div>
                    </div>
                    <!-- -->
                </form>
            </div>
        </div>

    <?php
        //=====================Update Page=======================  
    } elseif ($do == 'Update') {

        echo "<div class='container'>";
        echo "<h1 class='text-center'>Update Page</h1>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cid        = $_POST['custid'];
            $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $oldpass    = $_POST['oldpass'];
            $pass       = sha1($_POST['password']);
            $mobile     = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT);
            $sheep      = filter_var($_POST['sheep'], FILTER_SANITIZE_NUMBER_INT);
            $cutting    = filter_var($_POST['cutting'], FILTER_SANITIZE_NUMBER_INT);

            $newpass = '';
            if(empty($_POST['password']) || $_POST['password'] == ''){
                $newpass = $_POST['oldpass'];
            }else{
                $newpass = $pass;
            }

            $formerrors = array();

            if (empty($name)) {
                $formerrors[] = 'Enter Valid Name';
            }
            if (empty($mobile)) {
                $formerrors[] = 'Enter Mobile Number';
            }
            if (empty($sheep)) {
                $formerrors[] = 'Enter Sheep Number';
            }
            if (empty($cutting)) {
                $formerrors[] = 'Chosse Cutting Type';
            }

            if (empty($formerrors)) {
                /*Update Customer Data In Database */
                $stat = $conn->prepare("UPDATE 
                                            customers
                                        SET
                                            Name = ?,
                                            Password = ?,
                                            Mobile = ?,
                                            SheepNumber = ?,
                                            CuttingType = ?
                                        WHERE
                                            Id = ?
                                    ");
                $stat->execute(array(
                    $name, $newpass, $mobile, $sheep, $cutting, $cid
                ));
                $count = $stat->rowCount();

                if ($count > 0) {
                    $message = "<div class='alert alert-success'>$count Customer Data Has Been Updated Successfully</div>";
                    redirectpage($message, 'Customers.php', 3);
                } else {
                    $message = "<div class='alert alert-danger'>Failed To Update Data</div>";
                    redirectpage($message, 'back', 3);
                }
            } else {
                foreach ($formerrors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                $message = "<div class='alert alert-warning'>Enter Valid Data</div>";
                redirectpage($message, 'back', 4);
            }
        }

        echo "</div>";
        //=====================Delete Page=======================
    } elseif ($do == 'Delete') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Page</h1>";

        $custId = isset($_GET['Cust-Id']) ? $_GET['Cust-Id'] : '';

        $stmt = $conn->prepare("DELETE FROM customers WHERE Id = ?");
        $stmt->execute(array($custId));
        $count = $stmt->rowCount();

        if ($count > 0) {
            $message = "<div class='alert alert-success'>$count Customer Has Been Deleted Successfully</div>";
            redirectpage($message, 'Customers.php', 3);
        } else {
            $message = "<div class='alert alert-danger'>Failed To delete Customer</div>";
            redirectpage($message, 'back', 3);
        }

        echo "</div>";
    } //For Elseif Delete
}

include $tpl . "Footer.php";
ob_end_flush();
