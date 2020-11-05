<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include "sqlConnection.php";
include "classes/users.class.php";
include "classes/module.class.php";

session_start();
$Details = "";
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "professor" || $Details->getMod() == ""){
        header("Location:loginPage.php");
    }
}

$id = $_POST["studID"];
$feedback = $_POST["feedback"];
if(isset($_POST["sub"])){
    $subAss = $_POST["sub"];
    $score = $_POST["subScore"];
}

if (!$conn->connect_error)
{
    if(isset($_POST["sub"])){
        feedbackFactory::addSummative($conn, $id, $feedback, $subAss, $score);
    }else{    
        feedbackFactory::addFormative($conn, $id, $feedback);
    }
}



