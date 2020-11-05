<?php

include "sqlConnection.php";

include "classes/users.class.php";
include "classes/module.class.php";
include_once "classes/usersFactory.php";
session_start();
$Details = "";

if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    
}

if ($conn->connect_error)
{
    $errormsg .= $conn->connect_error;
    $_SESSION["errormsg"] = $errormsg;
    header("Location:loginPage.php");
}
else{
   #$module = usersFactory::getModuleInfo($conn, '1');
   #$Details->setMod($module);
    if($Details->getRole() == "professor"){
   echo '<h2>success</h2>
       
 ';
   $module = usersFactory::getModuleInfo($conn, '1');
   $Details->setMod($module);
   $_SESSION["sessionInfo"] = $Details;
   header("Location:createPageProf.php");
    }
    else{
       echo '<h2>noo success</h2>'; 
    }
}
?>


<script src="js/createPageProf.js"></script>




