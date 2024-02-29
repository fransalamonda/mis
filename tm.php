<?php
/*
 * Main Menu Truk Mixer
 */
if(isset($_GET['menu']) && !empty($_GET['menu'])){
    if($_GET['menu'] == "truck_list"){
        include_once './content/truck_list2.php';
    }
    elseif($_GET['menu'] == "detail_truck"){
        include_once './content/detail_truck.php';
    } 
}
