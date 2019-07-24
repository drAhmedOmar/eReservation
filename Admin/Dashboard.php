<?php

ob_start();
session_start();

if (isset($_SESSION['username'])) {

    $PageTitle = "Dashboard";
    include "Init.php";
    ?>
    <div class="container">
        <h1 class="text-center">Dashboard</h1>
        <div class="panel-3">
            <div class="row">
                <!--panel -->
                <?php
                    $stmt = $conn->prepare("SELECT Name FROM customers ORDER BY Date DESC LIMIT 5");
                    $stmt->execute();
                    $names = $stmt->fetchAll();
                ?>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header text-center">
                            Last 5 Customers
                        </div>
                        <div class="card-body">
                            <ul class="cust-name list-unstyled">
                                <?php
                                    foreach($names as $name){
                                        echo "<li>{$name['Name']}</li>";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--Customers Count -->
                <?php
                    $cust_count = counts('Id', 'customers', '');
                    $sheep_count =  counts('Id', 'sheep', 'WHERE Delivered = 0');             
                ?>
                <div class="col-sm-4">
                    <div class="cust-num">
                        <h3 class="text-center">Customers Count</h3>
                        <div class="count text-center">
                            <i class="fa fa-users fa-3x"></i>
                            <span class="c-num"><?php echo $cust_count ?></span>
                        </div>
                    </div>
                </div>
                <!--Sheep Count -->
                <div class="col-sm-4">
                    <div class="sheep-num">
                        <h3 class="text-center">Sheep Count</h3>
                        <div class="count text-center">
                            <i class="fa fa-dragon fa-3x"></i>
                            <span class="c-num"><?php echo $sheep_count ?></span>
                        </div>
                    </div>
                </div>
                <!-- -->
            </div>
        </div>
    </div>


    <?php
    include $tpl . "Footer.php";
} else {
    header('location:Index.php');
    exit();
}
ob_end_flush();
