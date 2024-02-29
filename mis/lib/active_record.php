<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of active_record
 *
 * @author ilham.dinov
 */
class active_record {
    public $host;
    public $user;
    public $pass;
    public $perintah;
    public $database;
    public $koneksi;

    function __construct($host_in,$user_in,$pass_in,$db_in)
    {
        $this->host     = $host_in;
        $this->user     = $user_in;
        $this->pass     = $pass_in;
        $this->database = $db_in;

//        $this->koneksi = mysqli_connect($this->host,$this->user,$this->pass);
        $this->koneksi = new mysqli($this->host,$this->user,$this->pass);
        if(!$this->koneksi)
        {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        $q = $this->koneksi->select_db($this->database);
        if(!$q)
        {
            echo "Database tidak ditemukan";
        }
    }

    public function query($q)
    {   
        $result = $this->koneksi->query($q);
        return $result;
    }

    public function insert($tbl,$arr)
    {
        $string = "INSERT INTO `".$tbl."` (`".implode('`,`',array_keys($arr))."`) VALUES ('".implode('\',\'',array_values($arr))."')";
        $q = $this->koneksi->query($string);
        if($q === TRUE){
            return TRUE;
        }
        else{return FALSE;}
    }
    
    public function update($tbl,$arr,$where)
    {
        $q = $this->query("UPDATE ".$tbl." SET ".implode(',',array_keys($arr))."='".implode(',',array_values($arr))."' WHERE ".$where." ");
        $q = $this->koneksi->query($string);
        if($q === TRUE){
            return TRUE;
        }
        else{return FALSE;}
    }
    
    public function delete($tbl,$arr)
    {
        $q = $this->query("DELETE FROM ".$tbl." WHERE ".implode(',',array_keys($arr))."='".implode(',',array_values($arr))."'");
        return $q;
    }

    public function select($tbl,$kol)
    {
        $q = $this->query("select ".$kol." from ".$tbl."");
        return $q;
    }
    
    public function trans_begin(){
        $this->koneksi->autocommit(FALSE);
    }
    
    public function trans_commit(){
        $this->koneksi->commit();
    }
    
    public function trans_rollback(){
        $this->koneksi->rollback();
//        $q = mysqli_rollback($this->koneksi);
    }
            
    function __destruct()
    {
        $this->koneksi->close();
//        $q = mysqli_close($this->koneksi);
//        return $q;
    }
}
