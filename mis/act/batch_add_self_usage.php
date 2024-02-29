<?php
include '../inc/constant.php';
include '../db.php';
if(IS_AJAX){
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $conn = mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
            mysqli_select_db($conn,DB_NAME);
            
            $pland = PLANT_ID;
            
            if(empty($_POST['cust']) ){ throw new Exception("Kolom <b>cust</b> must be filled!"); }
            if(empty($_POST['proj']) ){ throw new Exception("Kolom <b>proj Name</b> must be filled!"); }
            if(empty($_POST['proj_loc']) ){ throw new Exception("Kolom <b>proj Address</b> must be filled!"); }
            if(empty($_POST['seal_no']) ){ throw new Exception("Kolom <b>Seal Nno</b> must be filled!"); }            
            if($_POST['fa']     > 9999){ throw new Exception("Max Qty FA 9999!"); }
            if($_POST['semen_a'] > 9999){ throw new Exception("Max Qty semen_a 9999!"); }
            if($_POST['semen_b'] > 9999){ throw new Exception("Max Qty semen_b 9999!"); }
            if($_POST['cementtp2'] > 9999){ throw new Exception("Max Qty cementtp2 9999!"); } 
            if($_POST['cementvas'] > 9999){ throw new Exception("Max Qty cementvas 9999!"); }                   
            if($_POST['agg1']   > 9999){ throw new Exception("Max Qty AGG1 9999!"); }
            if($_POST['agg2']   > 9999){ throw new Exception("Max Qty AGG2 9999!"); }
            if($_POST['agg3']   > 9999){ throw new Exception("Max Qty AGG3 9999!"); }            
            if($_POST['msand']  > 9999){ throw new Exception("Max Qty MSAND 9999!"); }
            if($_POST['sand']   > 9999){ throw new Exception("Max Qty SAND 9999!"); }            
            if($_POST['sandvas']   > 9999){ throw new Exception("Max Qty SANDVAS 9999!"); }            
            if($_POST['water']  > 9999){ throw new Exception("Max Qty FA 9999!"); }     
            if($_POST['agg1vas']   > 9999){ throw new Exception("Max Qty AGG1 VAS 9999!"); }
            if($_POST['agg2vas']   > 9999){ throw new Exception("Max Qty AGG2 VAS 9999!"); }
            if($_POST['agg3vas']   > 9999){ throw new Exception("Max Qty AGG3 VAS 9999!"); }                 
            if($_POST['stonedust']   > 9999){ throw new Exception("Max Qty STONEDUST 9999!"); }          
            if($_POST['stonedustvas']   > 9999){ throw new Exception("Max Qty STONEDUSTVAS 9999!"); }          
            if($_POST['rt6p']   > 9999){ throw new Exception("Max Qty RT6P 9999!"); }
            if($_POST['vis1003'] > 9999){ throw new Exception("Max Qty VIS1003 9999!"); }            
            if($_POST['flbrpf34'] > 9999){throw new Exception("Max Qty FLBRPF34 9999!"); }          
            if($_POST['vis3660lr'] > 9999){ throw new Exception("Max Qty VIS3660LR 9999!"); }          
            if($_POST['gencb10'] > 9999){ throw new Exception("Max Qty GENCB10 9999!"); }
            if($_POST['sbtconm'] > 9999){ throw new Exception("Max Qty SBTCONM 9999!"); }
            if($_POST['sbtjm9'] > 9999){ throw new Exception("Max Qty SBTJM9 9999!"); }
            if($_POST['sbtpca8s'] > 9999){ throw new Exception("Max Qty SBTPCA8S 9999!"); }
            if($_POST['p83']  > 9999){ throw new Exception("Max Qty p83 9999!"); }
            if($_POST['SIKAMENT183']   > 9999){ throw new Exception("Max Qty SIKAMENT183 9999!"); }
            if($_POST['genr8212'] > 9999){ throw new Exception("Max Qty genr8212 9999!"); }            
            if($_POST['get702'] > 9999){throw new Exception("Max Qty get702 9999!"); }             
            if($_POST['genb1714'] > 9999){ throw new Exception("Max Qty genb1714 9999!"); }   
            if($_POST['flbnf15'] > 9999){ throw new Exception("Max Qty flbnf15 9999!"); }
            if($_POST['flbpd19'] > 9999){ throw new Exception("Max Qty flbpd19 9999!"); }
            if($_POST['stldust'] > 9999){ throw new Exception("Max Qty stldust 9999!"); }              
             

            if( isset($_POST['fa']) && isset($_POST['semen_a']) && isset($_POST['semen_b']) && isset($_POST['cementtp2']) && isset($_POST['cementvas']) && isset($_POST['agg1']) && isset($_POST['agg2']) && isset($_POST['agg3']) && isset($_POST['agg1vas']) && isset($_POST['agg2vas']) && isset($_POST['agg3vas']) && isset($_POST['msand']) && isset($_POST['sand']) && isset($_POST['sandvas']) && isset($_POST['stonedustvas']) && isset($_POST['water']) && isset($_POST['stonedust']) && isset($_POST['rt6p']) && isset($_POST['vis1003']) && isset($_POST['flbrpf34']) &&  isset($_POST['vis3660lr']) && isset($_POST['gencb10']) && isset($_POST['sbtconm']) && isset($_POST['sbtjm9']) && isset($_POST['sbtpca8s']) && isset($_POST['p83']) && isset($_POST['SIKAMENT183']) && isset($_POST['genr8212']) && isset($_POST['get702']) && isset($_POST['stldust']) && isset($_POST['flbnf15']) && isset($_POST['flbpd19']) && isset($_POST['genb1714']) ){
            extract($_POST); 

            if($fa == '' ){ $fa='0'; }
            if($semen_a == ''){ $semen_a = '0'; }
            if($semen_b == ''){ $semen_b = '0'; }    
            if($cementtp2 == ''){ $cementtp2 = '0'; }
            if($cementvas == ''){ $cementvas = '0'; }                                                                       
            if($agg1 == ''){ $agg1 = '0'; }
            if($agg2 == ''){ $agg2 = '0'; }
            if($agg3 == ''){ $agg3 = '0'; }
            if($agg1vas == ''){ $agg1vas = '0'; }
            if($agg2vas == ''){ $agg2vas = '0'; } 
            if($agg3vas == ''){ $agg3vas = '0'; }            
            if($msand == ''){ $msand = '0'; }
            if($sand == ''){ $sand = '0'; }
            if($sandvas == ''){ $sandvas = '0'; }                         
            if($water == ''){ $water = '0'; }            
            if($stonedust == ''){ $stonedust = '0'; }
            if($stonedustvas == ''){ $stonedustvas = '0'; }                      
            if($rt6p == ''){ $rt6p = '0'; } 
            if($vis1003 == ''){ $vis1003 = '0'; }  
            if($flbrpf34 == ''){$flbrpf34 = '0'; }                            
            if($vis3660lr == ''){ $vis3660lr = '0'; }
            if($gencb10 == ''){ $gencb10 = '0'; }
            if($sbtconm == ''){ $sbtconm = '0'; }
            if($sbtjm9 == ''){ $sbtjm9 = '0'; } 
            if($sbtpca8s == ''){ $sbtpca8s = '0'; }                     
            if($p83 == ''){ $p83 = '0'; }
            if($SIKAMENT183 == ''){ $SIKAMENT183 = '0'; }           
            if($genr8212 == ''){ $genr8212 = '0'; }            
            if($get702 == ''){$get702 = '0'; }             
            if($genb1714 == ''){ $genb1714 = '0'; }
            if($flbnf15 == ''){$flbnf15 = '0'; }             
            if($flbpd19 == ''){ $flbpd19 = '0'; }
            if($stldust == ''){ $stldust = '0'; }                        


            
            $curedate = date("Y-m-d H:i:s");
            $curdate = date('d/m/Y');
            
                //cek Mix Designt Adj
                    $no_mix = "SELECT * FROM `mix_design` "
                                . "WHERE `product_code` = 'SELFUSAGE'";                        
                    $hasil_mix = mysqli_query($conns,$no_mix);
//                        echo $hasil_mix;                        exit();
                        if(mysqli_num_rows($hasil_mix) == 0){
                             
//                             $pd = 'ADJ1';                            
                             $insertMD = "INSERT INTO `mix_design`(`mch_code`,    `product_code`,`slump_code`,`discharge`,`description`,`specification`,`qlt_group`,`flag_code`,`max_size`,`cre_by`,`cre_date`,`upd_by`,`upd_date`)"
                                        . "VALUES (                '".PLANT_ID."','SELFUSAGE',  'SELFUSAGE','SELFUSAGE',   'SELFUSAGE', 'SELFUSAGE',   '       ',  'I',        '0',       'DEC',   '".$curedate."','DEC',   '')";   
                               // echo $insertMD;                                exit();
                             if(!mysqli_query($conns,$insertMD)){
                                    throw new Exception('Error002 : Gagal Menambahkan mix_design,   '.mysqli_error());
                                 }
                              $insertFA = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                         . "VALUES ('".PLANT_ID."','SELFUSAGE','Cement','','0000-000015','FA','".$fa."','0','KG','I')";
                              if(!mysqli_query($conns,$insertFA)){
                                    throw new Exception('Error003 : Gagal Menambahkan FA,   '.mysqli_error());
                                 }
                             $insertsemen_a = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Cement','','0000-100011','SEMEN A','".$semen_a."','0','KG','I')";
                             if(!mysqli_query($conns,$insertsemen_a)){
                                    throw new Exception('Error004 : Gagal Menambahkan Cement,   '.mysqli_error());
                                }
                            $insertsemen_b = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Cement','','0000-000029','SEMEN B','".$semen_b."','0','KG','I')";
                             if(!mysqli_query($conns,$insertsemen_a)){
                                    throw new Exception('Error004 : Gagal Menambahkan Cement,   '.mysqli_error());
                                }
                            $insertScementtp2 = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Cement','','0000-100017','SEMEN OPC TYPE 2','".$cementtp2."','0','KG','I')";
                             if(!mysqli_query($conns,$insertScementtp2)){
                                    throw new Exception('Error004 : Gagal Menambahkan Cement,   '.mysqli_error());
                                }
                            $insertcementvas = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Cement','','0000-100060','SEMEN VAS','".$cementvas."','0','KG','I')";
                             if(!mysqli_query($conns,$insertcementvas)){
                                    throw new Exception('Error004 : Gagal Menambahkan Cementvas,   '.mysqli_error());
                                }                            
                             $insertAGG1 = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100090','AGG1','".$agg1."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG1)){
                                    throw new Exception('Error005 : Gagal Menambahkan AGG1,   '.mysqli_error());
                                }
                             $insertAGG2 = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100091','AGG2','".$agg2."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG2)){
                                    throw new Exception('Error006 : Gagal Menambahkan AGG2,   '.mysqli_error());
                                }
                             $insertAGG3 = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100092','AGG3','".$agg3."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG3)){
                                    throw new Exception('Error007 : Gagal Menambahkan AGG3,   '.mysqli_error());
                                }
                              $insertAGG1vas = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100063','AGG1VAS','".$agg1vas."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG1vas)){
                                    throw new Exception('Error007 : Gagal Menambahkan AGG1VAS,   '.mysqli_error());
                                }
                             $insertAGG2vas = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100064','AGG2VAS','".$agg2vas."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG2vas)){
                                    throw new Exception('Error007 : Gagal Menambahkan AGG2VAS,   '.mysqli_error());
                                }
                             $insertAGG3vas = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100065','AGG3VAS','".$agg3vas."','0','KG','I')";
                             if(!mysqli_query($conns,$insertAGG3vas)){
                                    throw new Exception('Error007 : Gagal Menambahkan AGG3VAS,   '.mysqli_error());
                                }

                             $insertMSAND = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100089','MSAND','".$msand."','0','KG','I')";
                             if(!mysqli_query($conns,$insertMSAND)){
                                    throw new Exception('Error008 : Gagal Menambahkan MSAND,   '.mysqli_error());
                                }
                             $insertSAND = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100094','SAND','".$sand."','0','KG','I')";
                             if(!mysqli_query($conns,$insertSAND)){
                                    throw new Exception('Error009 : Gagal Menambahkan SAND,   '.mysqli_error());
                                }
                            $insertSANDVAS = "INSERT INTO `mix_design_composition`(`mch_code`,`product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES ('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100061','SAND VAS','".$sandvas."','0','KG','I')";
                             if(!mysqli_query($conns,$insertSANDVAS)){
                                    throw new Exception('Error009 : Gagal Menambahkan SANDVAS,   '.mysqli_error());
                                }
                             $insertWATER = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE',  'Water',         '',      '0000-100030',  'WATER','".$water."','0','KG','I')";
                             if(!mysqli_query($conns,$insertWATER)){
                                    throw new Exception('Error010 : Gagal Menambahkan WATER,   '.mysqli_error());
                                }
                             $insertstonedust = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE',  'Aggregate','','0000-100093',  'stonedust','".$stonedust."','0','KG',  'I')";
                             if(!mysqli_query($conns,$insertstonedust)){
                                    throw new Exception('Error011 : Gagal Menambahkan stonedust,   '.mysqli_error());
                                }
                            $insertstonedustvas = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE',  'Aggregate','','0000-100062',  'stonedustvas','".$stonedust."','0','KG',  'I')";
                             if(!mysqli_query($conns,$insertstonedustvas)){
                                    throw new Exception('Error011 : Gagal Menambahkan stonedustvas,   '.mysqli_error());
                                } 
                             $insertRT6P = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100006','RT6P','".$rt6p."','0','KG','I')";
                             if(!mysqli_query($conns,$insertRT6P)){
                                    throw new Exception('Error014 : Gagal Menambahkan RT6P,   '.mysqli_error());
                                }                            
                             $insertvis1003 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100402','VIS 1003','".$vis1003."','0','KG','I')";
                             if(!mysqli_query($conns,$insertvis1003)){
                                    throw new Exception('Error017 : Gagal Menambahkan VIS1003,   '.mysqli_error());
                                }
                             $insertflbrpf34 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100442','FLB RPF34','".$flbrpf34."','0','KG','I')";
                             if(!mysqli_query($conns,$insertflbrpf34)){
                                    throw new Exception('Error018 : Gagal Menambahkan FLB RPF34,   '.mysqli_error());
                                }
							               $insertvis3660lr = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,`mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE',  'Admixture',         '',      '0002-100430',  'vis3660lr','".$vis3660lr."','0','KG','I')";
                             if(!mysqli_query($conns,$insertvis3660lr)){
                                    throw new Exception('Error010 : Gagal Menambahkan vis3660lr,   '.mysqli_error());
                                }


                            $insertgencb10 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100019','gen cb10','".$gencb10."','0','KG','I')";
                            if(!mysqli_query($conns, $insertgencb10)){
                                    throw new Exception('Error013 : Gagal Menambahkan gencb10,   '.mysqli_error());
                                }
                              $insertsbtconm = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100020','sbtconm','".$sbtconm."','0','KG','I')";
                            if(!mysqli_query($conns, $insertsbtconm)){
                                    throw new Exception('Error013 : Gagal Menambahkan sbtconm,   '.mysqli_error());
                                }

                            $insertsbtjm9 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100202','SBT JM9','".$sbtjm9."','0','KG','I')";
                            if(!mysqli_query($conns, $insertsbtjm9)){
                                    throw new Exception('Error013 : Gagal Menambahkan sbtjm9,   '.mysqli_error());
                                }
                              $insertsbtpca8s= "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100021','SBT PCA8S','".$sbtpca8s."','0','KG','I')";
                            if(!mysqli_query($conns, $insertsbtpca8s)){
                                    throw new Exception('Error013 : Gagal Menambahkan sbtpca8s,   '.mysqli_error());
                                }


                            $insertp83 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`s`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100015','p83','".$p83."','0','KG','I')";
                            if(!mysqli_query($conns,$insertp83)){
                                    throw new Exception('Error013 : Gagal Menambahkan p83,   '.mysqli_error());
                                }
                            $insertSIKAMENT183 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100017','SIKAMENT183','".$SIKAMENT183."','0','KG','I')";
                            if(!mysqli_query($conns,$insertSIKAMENT183)){
                                    throw new Exception('Error014 : Gagal Menambahkan SIKAMENT183,   '.mysqli_error());
                                }                            
                            $insertgenr8212 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100437','genr8212','".$genr8212."','0','KG','I')";
                            if(!mysqli_query($conns,$insertgenr8212)){
                                    throw new Exception('Error017 : Gagal Menambahkan genr8212,   '.mysqli_error());
                                }
                            $insertget702 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100438','get702','".$get702."','0','KG','I')";
                            if(!mysqli_query($conns,$insertget702)){
                                    throw new Exception('Error018 : Gagal Menambahkan get702,   '.mysqli_error());
                                }
                            $insertgenb1714 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100439','genb1714','".$genb1714."','0','KG','I')";
                            if(!mysqli_query($conns,$insertgenb1714)){
                                    throw new Exception('Error019 : Gagal Menambahkan genb1714,   '.mysqli_error());
                                }
                            $insertflbnf15 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100440','flbnf15','".$flbnf15."','0','KG','I')";
                            if(!mysqli_query($conns,$insertflbnf15)){
                                    throw new Exception('Error019 : Gagal Menambahkan flbnf15,   '.mysqli_error());
                                }
                            $insertflbpd19 = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Admixture','','0002-100441','flbpd19','".$flbpd19."','0','KG','I')";
                            if(!mysqli_query($conns,$insertflbpd19)){
                                    throw new Exception('Error019 : Gagal Menambahkan flbpd19,   '.mysqli_error());
                                }
                            $insertstldust = "INSERT INTO `mix_design_composition`(`mch_code`,    `product_code`,`material_group`,`seq_no`,`material_code`,`material_name`,`mix_qty`,       `mix_qty_adj`,`unit`,`flag_code`)"
                                        . "VALUES('".PLANT_ID."','SELFUSAGE','Aggregate','','0000-100097','STEEL DUST','".$stldust."','0','KG','I')";
                            if(!mysqli_query($conns,$insertstldust)){
                                    throw new Exception('Error019 : Gagal Menambahkan Steel dust,   '.mysqli_error());
                                }
                        }
                        if(mysqli_num_rows($hasil_mix) > 0){
                            
                            $updatemd = "UPDATE `mix_design`"
                                    . "SET `upd_date` = '".$curedate."'"
                                    . "WHERE `product_code` = 'SELFUSAGE' ";
                            $UPDATEFA = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$fa."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-000015'";
//                            echo  $UPDATEFA;                            exit();            
                             if(!mysqli_query($conns,$UPDATEFA)){
                                    throw new Exception('Error003 : Gagal Update FA,  '.mysqli_error());
                                }
                            $UPDATESAND = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$sand."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100094'";
                            //echo  $UPDATE;                            exit();            
                             if(!mysqli_query($conns,$UPDATESAND)){
                                    throw new Exception('Error003 : Gagal Update SAND,  '.mysqli_error());
                                }
                            $UPDATESANDVAS = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$sandvas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100061'";
                            //echo  $UPDATE;                            exit();            
                             if(!mysqli_query($conns,$UPDATESANDVAS)){
                                    throw new Exception('Error003 : Gagal Update SANDVAS,  '.mysqli_error());
                                }
                            $UPDATEMSAND = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$msand."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100089'";
                            //echo  $UPDATE;                            exit();            
                             if(!mysqli_query($conns,$UPDATEMSAND)){
                                    throw new Exception('Error003 : Gagal Update MSAND,  '.mysqli_error());
                                }
                            $UPDATEAGG1 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg1."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100090'";
                            //echo  $UPDATE;                            exit();            
                             if(!mysqli_query($conns,$UPDATEAGG1)){
                                    throw new Exception('Error003 : Gagal Update AGG1,  '.mysqli_error());
                                }
                            $UPDATEAGG2 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg2."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100091'";
                             if(!mysqli_query($conns,$UPDATEAGG2)){
                                    throw new Exception('Error003 : Gagal Update AGG2,  '.mysqli_error());
                                }
                            $UPDATEAGG3 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg3."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100092'";
                             if(!mysqli_query($conns,$UPDATEAGG3)){
                                    throw new Exception('Error003 : Gagal Update AGG3,  '.mysqli_error());
                                }
                              $UPDATEAGG1VAS = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg1vas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100063'";
                             if(!mysqli_query($conns,$UPDATEAGG1VAS)){
                                    throw new Exception('Error003 : Gagal Update AGG1vas,  '.mysqli_error());
                                }

                            $UPDATEAGG2VAS = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg2vas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100064'";
                             if(!mysqli_query($conns,$UPDATEAGG2VAS)){
                                    throw new Exception('Error003 : Gagal Update AGG2vas,  '.mysqli_error());
                                }
                            $UPDATEAGG3VAS = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$agg3vas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100065'";
                             if(!mysqli_query($conns,$UPDATEAGG3VAS)){
                                    throw new Exception('Error003 : Gagal Update AGG3vas,  '.mysqli_error());
                                }
                             $UPDATEsemen_a = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$semen_a."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100011'";
                            //print_r($UPDATEcement);
                             if(!mysqli_query($conns,$UPDATEsemen_a)){
                                    throw new Exception('Error003 : Gagal Update Semen A,  '.mysqli_error());
                                }
                            $UPDATEsemen_b = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$semen_b."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-000029'";
                            //print_r($UPDATEcement);
                             if(!mysqli_query($conns,$UPDATEsemen_b)){
                                    throw new Exception('Error003 : Gagal Update Semen B,  '.mysqli_error());
                                }
                            $UPDATEcementtp2 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$cementtp2."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100017'";
                            //print_r($UPDATEcement);
                             if(!mysqli_query($conns,$UPDATEcementtp2)){
                                    throw new Exception('Error003 : Gagal Update cement,  '.mysqli_error());
                                }
                            $UPDATECEMENTVAS = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$cementvas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100060'";
                            //echo  $UPDATE;                            exit();            
                             if(!mysqli_query($conns,$UPDATECEMENTVAS)){
                                    throw new Exception('Error003 : Gagal Update SEMEN VAS,  '.mysqli_error());
                                }                               
                            $UPDATEwater = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$water."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100030'";
                             if(!mysqli_query($conns,$UPDATEwater)){
                                    throw new Exception('Error003 : Failed Update Water,  '.mysqli_error());
                                }
                            $UPDATEstonedust = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$stonedust."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100093'";
                             if(!mysqli_query($conns,$UPDATEstonedust)){
                                    throw new Exception('Error003 : Failed Update stonedust,  '.mysqli_error());
                                }
                            $UPDATEstonedustvas = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$stonedustvas."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0000-100062'";
                             if(!mysqli_query($conns,$UPDATEstonedustvas)){
                                    throw new Exception('Error003 : Failed Update stonedustvas,  '.mysqli_error());
                                }
                            $UPDATErt6p = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$rt6p."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0002-100006'";
                             if(!mysqli_query($conns,$UPDATErt6p)){
                                    throw new Exception('Error003 : Failed Update RT6P,  '.mysqli_error());
                                }
                            $UPDATEvis1003 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$vis1003."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0002-100402'";
                             if(!mysqli_query($conns,$UPDATEvis1003)){
                                    throw new Exception('Error003 : Failed Update VIS 1003,  '.mysqli_error());
                                }
                            $UPDATEflbrpf34 = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$flbrpf34."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0002-100442'";
                             if(!mysqli_query($conns,$UPDATEflbrpf34)){
                                    throw new Exception('Error003 : Failed Update flbrpf34,  '.mysqli_error());
                                }
							               $UPDATEvis3660lr = "UPDATE `mix_design_composition`
                                    SET `mix_qty` = '".$vis3660lr."'
                                    WHERE `mch_code` = '".PLANT_ID."'
                                        AND `product_code` = 'SELFUSAGE'
                                        AND `material_code` = '0002-100430'";
                             if(!mysqli_query($conns,$UPDATEvis3660lr)){
                                    throw new Exception('Error003 : Failed Update vis3660lr,  '.mysqli_error());
                                }
                         $UPDATEgencb10= "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$gencb10."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100019'";
                         if(!mysqli_query($conns,$UPDATEgencb10)){
                                throw new Exception('Error003 : Failed Update gen cb10,  '.mysqli_error());
                            } 

                        $UPDATEsbtconm= "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$sbtconm."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100020'";
                         if(!mysqli_query($conns,$UPDATEsbtconm)){
                                throw new Exception('Error003 : Failed Update sbtconm,  '.mysqli_error());
                            }

                        $UPDATEsbtjm9= "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$sbtjm9."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100202'";
                         if(!mysqli_query($conns,$UPDATEsbtjm9)){
                                throw new Exception('Error003 : Failed Update sbtjm9,  '.mysqli_error());
                            }
                      $UPDATEsbtpca8s= "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$sbtpca8s."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100021'";
                         if(!mysqli_query($conns,$UPDATEsbtpca8s)){
                                throw new Exception('Error003 : Failed Update sbtpca8s,  '.mysqli_error());
                            }

                        $UPDATEp83 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$p83."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100015'";
                         if(!mysqli_query($conns,$UPDATEp83)){
                                throw new Exception('Error003 : Failed Update p83,  '.mysqli_error());
                            }
                        $UPDATESIKAMENT183 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$SIKAMENT183."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100017'";
                         if(!mysqli_query($conns,$UPDATESIKAMENT183)){
                                throw new Exception('Error003 : Failed Update SIKAMENT183,  '.mysqli_error());
                            }
                        $UPDATEgenr8212 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$genr8212."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100437'";
                         if(!mysqli_query($conns,$UPDATEgenr8212)){
                                throw new Exception('Error003 : Failed Update genr8212,  '.mysqli_error());
                            }
                        $UPDATEget702 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$get702."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100438'";
                         if(!mysqli_query($conns,$UPDATEget702)){
                                throw new Exception('Error003 : Failed Update get702,  '.mysqli_error());
                            }
                        $UPDATEgenb1714 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$genb1714."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100439'";
                         if(!mysqli_query($conns,$UPDATEgenb1714)){
                                throw new Exception('Error003 : Failed Update genb1714,  '.mysqli_error());
                            }
                             $UPDATEflbnf15 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$flbnf15."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100440'";
                         if(!mysqli_query($conns,$UPDATEflbnf15)){
                                throw new Exception('Error003 : Failed Update flbnf15,  '.mysqli_error());
                            }
                             $UPDATEflbpd19 = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$flbpd19."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0002-100441'";
                         if(!mysqli_query($conns,$UPDATEflbpd19)){
                                throw new Exception('Error003 : Failed Update flbpd19,  '.mysqli_error());
                            }
                          $UPDATEstldust = "UPDATE `mix_design_composition`
                                SET `mix_qty` = '".$stldust."'
                                WHERE `mch_code` = '".PLANT_ID."'
                                    AND `product_code` = 'SELFUSAGE'
                                    AND `material_code` = '0000-100097'";
                         if(!mysqli_query($conns,$UPDATEstldust)){
                                throw new Exception('Error003 : Failed Update STEEL DUST,  '.mysqli_error());
                            }
                              
                        }
                        
                    $ku = "SELECT MAX(CAST(request_no AS UNSIGNED)) request_no FROM batch_request ORDER BY CAST(request_no AS UNSIGNED)";
                                $hasil_max = mysqli_query($conns,$ku);
                                $count = mysqli_num_rows($hasil_max);
                                if($count == 0){
                                    $request_no = 1;
                                }
                                else{
                                    $data = mysqli_fetch_array($hasil_max);
                                    $request_no = $data['request_no']+1;
                                }
                     
                    $cc = "SELECT MAX(CAST(cust_code AS UNSIGNED)) cust_code FROM batch_request ORDER BY CAST(cust_code AS UNSIGNED)";
                                $hasil_max_cc = mysqli_query($conns,$cc);
                                $count_cc = mysqli_num_rows($hasil_max_cc);
                                if($count_cc == 0){
                                    $cust_code = 1;
                                }
                                else{
                                    $data_cc = mysqli_fetch_array($hasil_max_cc);
                                    $cust_code = $data_cc['cust_code']+1;
                                }
                                
                    $no_so = "SELECT * FROM `batch_request` WHERE `so_no` LIKE '$pland-%'";            
                    $hasil_so = mysqli_query($conns,$no_so);
                    $ns = mysqli_num_rows($hasil_so);
                    if($ns == 0){
                        $sn = $pland.'-'.'1';
                                    $insertbr = "INSERT INTO `batch_request` (`request_no`,     `so_no`,             `mch_code`,     `product_code`,`batch_date`,  `vh_no`,`unit_no`,`driver_id`,`driver_name`,`user_login`,`cust_code`,     `cust_name`,     `proj_code`,     `proj_name`,     `proj_address`,     `proj_phone_no`,`batch_vol`,`remain_vol`,             `total_so_vol`,`flag_code`,`cre_by`,`cre_date`,`seal_no`)"
                                                                . "VALUES                ('".$request_no."','$sn','".PLANT_ID."','SELFUSAGE',  '".$curdate."','     ','       ','         ', '           ','         ','".$cust_code."','".$cust."',      '".$cust_code."','".$proj."','".$proj_loc."',         '',            '1',       '0',         '0', 'I',      '',     NOW(),     '".$seal_no."')";
                                    //echo $insertbr;exit();        
                                    if(!mysqli_query($conns,$insertbr)){
                                                throw new Exception('Gagal Menambahkan Batch Request, Error : '.mysqli_error());
                                    }
                    }else{
                        $sono = "SELECT MAX(CONVERT(SUBSTR(so_no,12,12), SIGNED INTEGER)) AS PC FROM `batch_request` WHERE `so_no` LIKE '$pland-%'";
                        $hasil_so = mysqli_query($conns,$sono);
                        $data = mysqli_fetch_array($hasil_so);
                        $sn = $data['PC']+1;
                        $sno = $pland.'-'.$sn;
                        $insertbr = "INSERT INTO `batch_request` (`request_no`,     `so_no`,             `mch_code`,     `product_code`,`batch_date`,  `vh_no`,`unit_no`,`driver_id`,`driver_name`,`user_login`,`cust_code`,     `cust_name`,     `proj_code`,     `proj_name`,     `proj_address`,     `proj_phone_no`,`batch_vol`,`remain_vol`,             `total_so_vol`,`flag_code`,`cre_by`,`cre_date`,`seal_no`)"
                                                                . "VALUES                ('".$request_no."','$sno','".PLANT_ID."','SELFUSAGE',  '".$curdate."','     ','       ','         ', '           ','         ','".$cust_code."','".$cust."',      '".$cust_code."','".$proj."','".$proj_loc."',         '',            '1',       '0',         '0', 'I',      '',     NOW(),     '".$seal_no."')";
                                    //echo $insertbr;exit();        
                                    if(!mysqli_query($conns,$insertbr)){
                                                throw new Exception('Gagal Menambahkan Batch Request, Error : '.mysqli_error());
                                    }
                    }
                                
                    $addbr = mysqli_query($conns,$insertbr);
                    $output = array(
                                'status'            =>  1,
                                'msg'               =>  'Berhasil Menambahkan Batch Request Self Usage Cust :'.$cust,
                                'out_volume'        =>  PLANT_ID,
                                'today_b_request'   =>  'SELFUSAGE');
                            exit(json_encode($output));
            }
            else{
                throw new Exception("Empty Field");
            }
        }
        else{
            exit("Method is denied");
        }
    } catch (Exception $exc) {
        $output = array(
            "status"    => 0,
            "msg"       =>  $exc->getMessage()
        );
        exit(json_encode($output));
    }
}
else{
    exit("Access Denied");
}