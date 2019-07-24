<?php

ob_start();
session_start();

include "init.php";
if (isset($_SESSION['cost_name'])) {
    ?>
    <div class="container">
        <h1 class="text-center">Eid Mubarak</h1>
        <div class="board text-center">
            <div class="row">
                <!--Customers Number -->
                <div class="col-sm-6">
                    <?php
                    $Counts = getdata('COUNT(Id)', 'customers', '', '');
                    foreach ($Counts as $count) {
                        $number = $count['COUNT(Id)'];
                    }
                    ?>
                    <div class="card ">
                        <div class="card-header">
                            Number Of Customers
                        </div>
                        <div class="card-body">
                            <span><?php echo $number; ?></span>
                        </div>
                    </div>
                </div>
                <!--Sheep Number -->
                <div class="col-sm-6">
                    <?php
                    $counts = getdata('SheepNumber', 'customers', '', '');
                    $sum = 0;
                        foreach($counts as $shcount){
                            $sum+= $shcount['SheepNumber'];
                        }
                    ?>
                    <div class="card">
                        <div class="card-header">
                            Numbers Of Sheep
                        </div>
                        <div class="card-body">
                            <?php echo $sum; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Register Form -->
        <h1 class="text-center">Register Your Sheep</h1>
        <div class="reg-form">
            <form class="form-horizontal text-center" action="register.php" method="POST" enctype="multipart/form-data">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?php echo $_SESSION['cost_name'] ?>" autocomplete="off" required="required" readonly />
                    </div>
                </div>
                <!--Mobile -->
                <?php
                $cost_data = getdata('*', 'customers', 'WHERE Name = ?', $_SESSION['cost_name']);
                foreach ($cost_data as $cost) {
                    $mobilenum = $cost['Mobile'];
                }
                ?>
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Mobile</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="<?php echo $mobilenum ?>" autocomplete="off" required="required" readonly />
                    </div>
                </div>
                <!--Sheep Number -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Sheep Number</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" name="number" class="form-control" autocomplete="off" placeholder="Sheep Number" required="required" />
                    </div>
                </div>
                <!--Sheep Type -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Type</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="type" class="form-control">
                            <option value="1">Sheep</option>
                            <option value="2">Goat</option>
                        </select>
                    </div>
                </div>
                <!--Cutting -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Cutting</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="cutting" class="form-control">
                            <option value="0">....</option>
                            <option value="1">Full</option>
                            <option value="2">4 Pieces</option>
                            <option value="3">6 Pieces</option>
                            <option value="4">8 Pieces</option>
                        </select>
                    </div>
                </div>
                <!--Image -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Select Photo</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="file" name="image" class="form-control"/>
                    </div>
                </div>
                <!--Submit -->
                <div class="form-group row">
                    <div class="offset-2 col-sm-3">
                        <input type="submit" value="Save" class="btn btn-sm btn-success" />
                    </div>
                </div>
                <!-- -->
            </form>
        </div>
    </div>


<?php
    //If Visitor Is Not Registered Customer
} else {
    ?>
    <!--Register Form -->
    <div class="container">
        <h1 class="text-center">Eid Mubarak</h1>
        <div class="board text-center">
            <div class="row">
                <!--Customers Number -->
                <div class="col-sm-6">
                    <?php
                    $Counts = getdata('COUNT(Id)', 'customers', '', '');
                    foreach ($Counts as $count) {
                        $number = $count['COUNT(Id)'];
                    }
                    ?>
                    <div class="card ">
                        <div class="card-header">
                            Number Of Customers
                        </div>
                        <div class="card-body">
                            <span><?php echo $number; ?></span>
                        </div>
                    </div>
                </div>
                <!--Sheep Number -->
                <div class="col-sm-6">
                    <?php
                    $counts = getdata('COUNT(Id)', 'sheep', '', '');
                    foreach ($counts as $count) {
                        $number = $count['COUNT(Id)'];
                    }
                    ?>
                    <div class="card">
                        <div class="card-header">
                            Numbers Of Sheep
                        </div>
                        <div class="card-body">
                            <?php echo $number; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Login -->
        <h4 class="text-center">You Must Login Or Signup To Register Your Sheep</h4>
        <div class="log text-center">
            <span><a href="login.php">Login</a></span> | <span><a href="login.php">Signup</a></span>
        </div>
    </div>
<?php
}

include $tpl . "footer.php";
ob_end_flush();
