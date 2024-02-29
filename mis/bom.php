<?php
/*
 * Main Menu BOM
 */
if(isset($_GET['menu']) && !empty($_GET['menu'])){
    if($_GET['menu'] == "transfer_list"){
        include_once './content/bom_chart_list.php';
    }
    elseif($_GET['menu'] == "mix_list"){
        include_once './content/list_mix.php';
    }
    elseif($_GET['menu'] == "list_detail"){
        include_once './content/list_mix_detail.php';
    }
    //tes
    elseif($_GET['menu'] == "transfer"){
        include_once './content/transfer.php';
    }
    elseif($_GET['menu'] == "batch_reques_adjustment"){
        include './content/batch_request_Adjustment.php';
    }
    elseif($_GET['menu'] == "batch_reques_adjustment_not"){
        include './content/batch_request_Adjustment_not.php';
    }
    elseif($_GET['menu'] == "exsport_csv"){
        include './content/exsport_csv.php';
    }
    elseif($_GET['menu'] == "export_akumulasi"){
        include './content/export_akumulasi.php';
    }
    elseif($_GET['menu'] == "about"){
        include './content/about.php';
    }
    elseif($_GET['menu'] == "list_query"){
        include './content/list_data_batch_so.php';
    }    
    else{
        include './content/upload_bom_chart.php';
    }
}
else{
    include './content/upload_bom_chart.php';
}