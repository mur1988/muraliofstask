<?php
	require_once("../db/dbcontroller.php");
	$db_handle = new DBController();
	$status = "";
	$message = "";
    //session_start();
    

    $db_handle->updateMany(array(),array('$set'=>array('isRegional'=>'N')),"contentdetails");

    $db_handle->updateMany(array("language"=>"KollyWood"),array('$set'=>array('regionallanguage'=>'Tamil')),"languagemasters");
    $db_handle->updateMany(array("language"=>"Tollywood"),array('$set'=>array('regionallanguage'=>'Telugu')),"languagemasters");
    $db_handle->updateMany(array("language"=>"BollyWood"),array('$set'=>array('regionallanguage'=>'Hindi')),"languagemasters");
    $db_handle->updateMany(array("language"=>"SandalWood"),array('$set'=>array('regionallanguage'=>'Kannada')),"languagemasters");
    $db_handle->updateMany(array("language"=>"MollyWood"),array('$set'=>array('regionallanguage'=>'Malayalam')),"languagemasters");
    $db_handle->updateMany(array("language"=>"HollyWood"),array('$set'=>array('regionallanguage'=>'English')),"languagemasters");
    
    echo "exce";
	
?>
