<?php

include "sqlConnection.php";

include "classes/users.class.php";
include "classes/module.class.php";
include "classes/usersFactory.php";
include "classes/ProfessorDictionaryAdapter.php";
session_start();
$Details = "";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location:createPageProf.php");
    exit;
}

if (!isset($_SESSION['sessionInfo'])) {
    header("Location:loginPage.php");
    exit;
} else {
    $Details = $_SESSION['sessionInfo'];
    if ($Details->getRole() != "professor") {
        header("Location:loginPage.php");
    } else {
        if ($conn->connect_error) {
            $errormsg .= $conn->connect_error;
            $_SESSION["errormsg"] = $errormsg;
            header("Location:loginPage.php");
        } else {
            echo '<h2>success</h2>';
            $name = $Details->getName();
            $result = mysqli_query($conn, "SELECT module FROM users WHERE name='".$name."'");
            $row = mysqli_fetch_row($result);
            $moduleid=$row[0];
            $module = usersFactory::getModuleInfo($conn, $moduleid);
            $Details->setMod($module);
            $studentList = usersFactory::getAllEnrollStudents($conn, $moduleid);
            $_SESSION["studentList"] = $studentList;
            header("Location:createPageProf.php");
            echo "<script src='" . js / createPageProf . js . "'></script>";
        }
    }
}
?>