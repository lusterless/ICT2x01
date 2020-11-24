<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    if($Details->getRole() != "student"){
        header("Location:loginPage.php");
    } 
} 

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sub"]) && isset($_POST["studentid"])){
    $studentid = $_POST['studentid'];
    $sub = $_POST['sub'];
    $query = "UPDATE userSummative SET seen=1 WHERE studentid='".$studentid."' AND subAssessment_name='".$sub."'";
    if ($conn->connect_error){
        echo "Database Error\n";
    }else{
        $conn->query($query);
        foreach($Details->getMod()->getAllComponent() as $com){
            foreach($com->getSub() as $comsub){
                if($comsub->getName() == $sub){
                    $comsub->giveSeen(1);
                }
            }
        }
    }
}


