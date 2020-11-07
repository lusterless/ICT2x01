<?php

include "sqlConnection.php";

include "classes/users.class.php";
include "classes/module.class.php";
include "classes/usersFactory.php";
include "classes/ProfessorDictionaryAdapter.php";
session_start();
$Details = "";

if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "professor"){
        header("Location:loginPage.php");
    }else{
        if ($conn->connect_error)
        {
            $errormsg .= $conn->connect_error;
            $_SESSION["errormsg"] = $errormsg;
            header("Location:loginPage.php");
        }
        else{
            echo '<h2>success</h2>';
            $module = usersFactory::getModuleInfo($conn, '1');
            $Details->setMod($module);
            $studentList = usersFactory::getAllEnrollStudents($conn, '1');
            $_SESSION["studentList"] = $studentList;
            header("Location:createPageProf.php");
            echo "<script src='".js/createPageProf.js."'></script>";
        }
    }
}

//if ($conn->connect_error)
//{
//    $errormsg .= $conn->connect_error;
//    $_SESSION["errormsg"] = $errormsg;
//    header("Location:loginPage.php");
//}
//else{
//   #$module = usersFactory::getModuleInfo($conn, '1');
//   #$Details->setMod($module);
//    if($Details->getRole() == "professor"){
//   
//    }
//    else{
//       echo '<h2>noo success</h2>'; 
//    }
//}
?>







