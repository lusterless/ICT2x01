<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "classes/users.class.php";
include "classes/module.class.php";
include "classes/deleteFactory.php";
include_once "classes/ProfessorDictionaryAdapter.php";
include_once "sqlConnection.php";

session_start();
$Details = "";
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "professor" || $Details->getMod() == ""){
        header("Location:loginPage.php");
    }else{
        if ($conn->connect_error)
        {
            session_start();   
            //insert error msg here
            header("Location:manageModule.php");
        }else{
            $studentList = $_SESSION["studentList"];
            deleteFactory::clearAll($conn, $Details->getMod()->getNumber(), $studentList->SelectAll());
            //delete module from session = delete components = delete subcomponents
            $Details->setMod("");
            header("Location:createPageProf.php");
        }
    }
}
?>