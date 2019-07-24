<?php

ob_start();
session_start();

$PageTitle = "Sheep";
include "Init.php";

if (isset($_SESSION['username'])) {
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    //===================Manage Sheep=====================
    if ($do == 'Manage') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Manage Sheep</h1>";

        ?>
        <div class="sheep-table">
            <table class="table table-bordered main-table text-center">
                <tr>
                    <th>#ID</th>
                    <th>Type</th>
                    <th>Group</th>
                    <th>Owner</th>
                    <th>Delivered</th>
                    <th>Control</th>
                </tr>
                <?php
                $sheepall = GetAllData('*', 'sheep', '', '', 'ORDER BY GroupNumber ASC');
                foreach ($sheepall as $sheep) {
                    $shid       = $sheep['Id'];
                    $shtype     = $sheep['Type'];
                    $shgroup    = $sheep['GroupNumber'];
                    $showner    = $sheep['Owner'];
                    $shdeliver  = $sheep['Delivered'];
                    echo "<tr>";
                    echo "<td>$shid</td>";
                    //Get Sheep Type
                    echo "<td>";
                    if ($shtype == '1') {
                        echo 'Sheep';
                    } else {
                        echo 'goat';
                    }
                    echo "</td>";
                    echo "<td>$shgroup</td>";
                    //Get Owner name
                    $ownername = GetAllData('Name', 'customers', 'WHERE Id = ?', $showner, '');
                    foreach ($ownername as $ownern) {
                        $ownerna = $ownern['Name'];
                    }
                    echo "<td>" . $ownerna . "</td>";
                    //Check Delivered Or Not
                    echo "<td>";
                    if ($shdeliver == '0') {
                        echo 'No';
                    } else {
                        echo 'Yes';
                    }
                    echo "</td>";
                    //Control Buttons
                    echo "<td>";
                    echo "<a href='?do=Edit&sheepid=" . $shid . "' class='btn btn-primary btn-sm'>Edit</a> ";
                    echo "<a href='?do=Delete&sheepid=" . $shid . "' class='btn btn-danger btn-sm confirm'>Delete</a> ";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <a href="Sheep.php?do=Add" class="btn btn-success">Add New Sheep</a>
        </div>

        <?php
        echo "</div>";
        //===================Edit Sheep=====================
    } elseif ($do == 'Edit') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Edit Sheep</h1>";

        $sheepid = isset($_GET['sheepid']) ? $_GET['sheepid'] : '';
        $sheepall = GetAllData('*', 'Sheep', 'WHERE Id = ?', $sheepid, '');

        foreach ($sheepall as $sheep) {
            $sid        = $sheep['Id'];
            $stype      = $sheep['Type'];
            $sgroup     = $sheep['GroupNumber'];
            $sowner     = $sheep['Owner'];
            $sdeliver   = $sheep['Delivered'];
        }
        ?>
        <div class="sheep-edit main-form text-center">
            <form class="form-horizontal edit-sheep" action="?do=Update" method="POST">
                <!--Hidden Id Input -->
                <input type="hidden" name="sheepid" class="form-control" value="<?php echo $sid ?>" />
                <!--Type -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Type</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="type" class="form-control">
                            <option value='1' <?php if ($stype == '1') {
                                                    echo 'selected';
                                                } ?>>Sheep</option>
                            <option value='2' <?php if ($stype == '2') {
                                                    echo 'selected';
                                                } ?>>Goat</option>
                        </select>
                    </div>
                </div>
                <!--Group -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Group</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" name="group" class="form-control" value="<?php echo $sgroup ?>" />
                    </div>
                </div>
                <!--Owner -->
                <?php
                $ownerdata = GetAllData('*', 'customers', '', '', '');
                ?>
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Owner</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="owner" class="form-control">
                            <?php
                            foreach ($ownerdata as $owner) {
                                echo "<option value='{$owner['Id']}'";
                                if ($owner['Id'] == $sowner) {
                                    echo 'selected';
                                }
                                echo ">{$owner['Name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!--Delivered -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Delivered</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="deliver" class="form-control">
                            <option value="0" <?php if ($sdeliver == '0') {
                                                    echo 'selected';
                                                } ?>>No</option>
                            <option value="1" <?php if ($sdeliver == '1') {
                                                    echo 'selected';
                                                } ?>>Yes</option>
                        </select>
                    </div>
                </div>
                <!--Submit -->
                <div class="form-group row">
                    <div class="offset-2 col-sm-4">
                        <input type="submit" value="Save Changes" class="btn btn-sm btn-success" />
                    </div>
                </div>
                <!-- -->
            </form>
        </div>
        <?php
        echo "</div>";
        //===================Update Sheep=====================
    } elseif ($do == 'Update') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Update Sheep</h1>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $shid       = $_POST['sheepid'];
            $shtype     = $_POST['type'];
            $shgroup    = $_POST['group'];
            $showner    = $_POST['owner'];
            $shdeliver  = $_POST['deliver'];
        }

        $formerrors = array();

        if (empty($shgroup)) {
            $formerrors[] = "Add Group";
        }

        if (empty($formerrors)) {
            $stmt = $conn->prepare("UPDATE sheep SET Type = ?, GroupNumber = ?, Owner = ?, Delivered = ? WHERE Id = ?");
            $stmt->execute(array($shtype, $shgroup, $showner, $shdeliver, $shid));
            $count = $stmt->rowCount();
            if ($count > 0) {
                $message = "<div class='alert alert-success'>$count Has Been Updated Successfully</div>";
                redirectpage($message, 'Sheep.php', '3');
            } else {
                $message = "<div class='alert alert-danger'>Failed To Update Sheep</div>";
                redirectpage($message, 'back', '3');
            }
        }else{
            foreach($formerrors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }
            $message = "<div class='alert alert-warning'>Enter Valid data</div>";
            redirectpage($message, 'back', '3');
        }

        echo "</div>";
        //===================Add Sheep=====================
    } elseif ($do == 'Add') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Add Sheep</h1>";
        ?>
        <div class="ad-sheep text-center">
            <form class="form-horizontal main-form" action="?do=Insert" method="POST">
                <!--Type -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Type</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="type" class="form-control">
                            <option value="1">Sheep</option>
                            <option value="2">Goat</option>
                        </select>
                    </div>
                </div>
                <!--Group -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Group</label>
                    <div class="col-md-6 col-sm-10">
                        <input type="text" name="group" class="form-control" placeholder="Group Number" />
                    </div>
                </div>
                <!--Owner -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Owner</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="owner" class="form-control">
                            <option value="0">....</option>
                        <?php
                            $owners = GetAllData('*', 'customers', 'WHERE Id != 1', '', '');
                            foreach($owners as $owner){
                                echo "<option value='{$owner['Id']}'>{$owner['Name']}</option>";
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <!--Delivered -->
                <div class="form-group row">
                    <label class="col-sm-3 control-label">Delivered</label>
                    <div class="col-md-6 col-sm-10">
                        <select name="delivered" class="form-control">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <!--Submit -->
                <div class="form-group row">
                    <div class="offset-2 col-sm-3">
                        <input type="submit" value="Add Sheep" class="btn btn-success btn-sm"/>
                    </div>
                </div>
                <!-- -->
            </form>
        </div>

        <?php
        echo "</div>";
        //===================Insert Sheep=====================
    } elseif ($do == 'Insert') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Insert Sheep</h1>";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $type       = $_POST['type'];
            $group      = filter_var($_POST['group'], FILTER_SANITIZE_NUMBER_INT);
            $owner      = $_POST['owner'];
            $delivered  = $_POST['delivered'];

            $formerrors = array();

            if(empty($group)){
                $formerrors[] = "Add Group Number";
            }
            if($owner == '0'){
                $formerrors[] = "Choose Owner";
            }

            if(empty($formerrors)){
                $stat = $conn->prepare("INSERT INTO sheep
                                            (Type, GroupNumber, Owner, Delivered)
                                        VALUES
                                            ($type, $group, $owner, $delivered)
                                    ");
                $stat->execute();
                $count = $stat->rowCount();
                if($count > 0){
                    $message = "<div class='alert alert-success'>$count Has Been Added Successfully</div>";
                    redirectpage($message, 'Sheep.php', '3');
                }else{
                    $message = "<div class='alert alert-danger'>Failed To Add Sheep</div>";
                    redirectpage($message, 'back', '3');
                }                 
            }else{
                foreach ($formerrors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
                $message = "<div class='alert alert-warning'>Add Valid Data</div>";
                redirectpage($message, 'back', '3');
            }
        }

        echo "</div>";
        //===================Delete Sheep=====================
    } elseif ($do == 'Delete') {
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Sheep</h1>";

        $sheepid = isset($_GET['sheepid'])? $_GET['sheepid']: '';

        $stmt = $conn->prepare("DELETE FROM Sheep WHERE Id = ?");
        $stmt->execute(array($sheepid));
        $count = $stmt->rowCount();
        if($count > 0){
            $message = "<div class='alert alert-success'>$count Has Been Deleted Successfully</div>";
            redirectpage($message, 'Sheep.php', '3');
        }else{
            $message = "<div class='alert alert-danger'>Failed To Delete Sheep</div>";
            redirectpage($message, 'back', '3');
        }

        echo "</div>";
    }
}

include $tpl . "Footer.php";
ob_end_flush();
