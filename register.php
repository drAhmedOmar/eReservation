<?php

ob_start();
session_start();

$pageTitle = "Register";

include "init.php";

if (isset($_SESSION['cost_name'])) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Get Data From Login Form
        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $mobile     = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT);
        $number     = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_INT);
        $type       = $_POST['type'];
        $cutting    = $_POST['cutting'];
       
        //Upload Images Variables
        $image_name = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp  = $_FILES['image']['tmp_name'];

        $tmpend = strtolower(end(explode('.', $image_name)));
        $allowedext = array('jpg', 'jpeg', 'png', 'gif');

        $formerrors = array();

        if (isset($image_name) && !in_array($tmpend, $allowedext)) {
            $formerrors[] = "This Extension Is Not Allowed";
        }
        if (empty($formerrors)) {

            //Upload Image
            $imagenew = rand(0, 1000000) . '_' . $image_name;
            move_uploaded_file($image_tmp, 'Upload/' . $imagenew);

            //Update Customer In Database
            $stmt = $conn->prepare("UPDATE customers SET CuttingType = ?, SheepNumber = ?, Image = ? WHERE Name = ?");
            $stmt->execute(array($cutting, $number, $imagenew, $name));
            $count = $stmt->rowCount();

            //Add Or Update Sheep In Database

            $sheepdata = getdata('*', 'sheep', 'WHERE Owner = ?', $_SESSION['Id']);
            foreach ($sheepdata as $sheep) {
                $sheepid = $sheep['Id'];
            }
            //Check If This Sheep Id Exist In Database Or Not
            if (empty($sheepid)) {
                //Sheep Id Not Exist So Insert New
                $stat = $conn->prepare("INSERT INTO sheep (Type, GroupNumber, Owner, Delivered)
                                    VALUES ($type, '1', {$_SESSION['Id']}, '0')
                                ");
                $stat->execute();
                $count = $stat->rowCount();
            } else {
                //Sheep Id Exist So Make Update
                $stmt = $conn->prepare("UPDATE sheep SET Type = ?, GroupNumber = ?, Owner = ?, Delivered = ? WHERE Id = ?");
                $stmt->execute(array($type, '1', $_SESSION['Id'], '0', $sheepid));
                $count = $stmt->rowCount();
            }
        }
    }

    //Get Customer And Sheep Data To Registered Card
    $dataAll = getdata('*', 'customers', 'WHERE Name = ?', $_SESSION['cost_name']);
    foreach ($dataAll as $data) {
        $mobile     = $data['Mobile'];
        $shnumber   = $data['SheepNumber'];
        $cutype     = $data['CuttingType'];
        $Groupnum   = $data['GroupNumber'];
        $shimage    = $data['Image'];
    }

    $datasheep = getdata('*', 'sheep', 'WHERE Owner = ?', $_SESSION['Id']);
    foreach ($datasheep as $datash) {
        $shtype     = $datash['Type'];
    }
    ?>
    <div class="container">
        <div class="reg-card text-center">
            <h1 class="text-center">Register Card</h1>
            <h3 class="text-center"><?php echo $_SESSION['cost_name']; ?></h3>
            <ul class="list-unstyled">
                <li><img src="<?php echo 'Upload/' . $shimage; ?>" alt="Image" ></li>
                <li>Mobile: <?php echo $mobile ?></li>
                <li>Sheep Number: <?php echo $shnumber ?></li>
                <li>Sheep Type: <?php echo $shtype ?></li>
                <li>Cutting Type: <?php echo $cutype ?></li>
                <li>Group Number: <?php echo $Groupnum ?></li>
                <li><b>Cost : </b> <?php echo ($shnumber * 50 . ' SR'); ?></li>
            </ul>
        </div>
    </div>

<?php
}

include $tpl . "footer.php";

ob_end_flush();
