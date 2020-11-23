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
include "classes/usersFactory.php";
include "classes/ProfessorDictionaryAdapter.php";

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

//formativeFeedback
$msg = "";
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["studentList"]) && isset($_POST["feedback"]) && isset($_POST["formativePage"]) && $_POST["feedback"] != "") {
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $studentChosen = $fb = ""; //set variables
        $studentChosen = $_POST["studentList"];
        $fb = usersFactory::filterStrings($_POST["feedback"]);
        //adapter
        $studentList = $_SESSION["studentList"];
        //insert into db
        foreach($studentChosen as $sc){
            $sc = usersFactory::filterStrings($sc);
            $conn->query("INSERT INTO userFormative(studentid,formative_feedback) VALUES ('".$sc."','".$fb."')");
            $studentList->SelectByID($sc)->getMod()->giveFormativeFeedback($fb); //giveFormativefeedback;
        }
        $msg .= "Feedbacks added successfully";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addFormative.php");
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset ($_POST['arrayFeedback']) && $_POST['arrayFeedback']!="" && isset($_POST["formativePage"])) {
    //import via files
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $information = json_decode($_POST['arrayFeedback'], true);
        //adapter
        $studentList = $_SESSION["studentList"];
        //insert into db
        foreach($information as $sf){
            $parafirst = usersFactory::filterStrings($sf[0]);
            $parasec = usersFactory::filterStrings($sf[1]);    
            $conn->query("INSERT INTO userFormative(studentid,formative_feedback) VALUES ('".$parafirst."','".$parasec."')");
            $studentList->SelectByID($parafirst)->getMod()->giveFormativeFeedback($parasec); //giveFormativefeedback;            
        }
        $msg .= "Files Feedbacks added successfully";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addFormative.php");
}
else{
    if(!isset($_POST["studentList"])){
        $msg .= "<p>Please choose at least 1 students.<p>";
    }
    if($_POST["feedback"] == ""){
        $msg .= "<p>Please enter a valid feedback.</p>";
    }
    
    if(!isset($_POST['arrayFeedback']) || $_POST['arrayFeedback']==""){
        $msg .= "<p>Alternatively, you may insert a file.</p>";
    }
    
    $_SESSION["msg"] = $msg;
    header("Location:addFormative.php");
}
