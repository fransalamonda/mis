<?php
function show_web(){
    ?>
<html>
    <head>
        <title>reset</title>
        <style type="text/css">
            body{font-family: "courier"}
        </style>
    </head>
    <body>
        <form action="" method="GET">
            <span>Initial Password :</span>&nbsp;<input type="text" name="initial"/>
        </form>
    </body>
</html>
<?php
}
if(isset($_GET['initial']) && !empty($_GET['initial'])){
    include './inc/constant.php';
    if(md5($_GET['initial']) == INITIAL_PASSWORD){
        $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $q_check = "UPDATE `tbl_user` SET `password`='".  INITIAL_PASSWORD."' WHERE `group_id` = 4";
        $r_check = $mysqli->query($q_check);
        if(!$r_check){
            exit("Error:".  mysqli_error($mysqli));
        }
        echo "Done!";
    }
    else{
        show_web();
        exit("Failed!");
    }
}
else{
    show_web();
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

