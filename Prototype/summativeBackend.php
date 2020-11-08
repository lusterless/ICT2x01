<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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

//summativeFeedback
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sub"]) && (isset($_POST["score"]) && $_POST["score"] >= 0 && $_POST["score"] <= 100) && isset($_POST["studentList"]) && isset($_POST["feedback"]) && isset($_POST["summativePage"])) {
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $studentChosen = $fb = $sub = $score = ""; //set variables
        $studentChosen = $_POST["studentList"];
        $fb = usersFactory::filterStrings($_POST["feedback"]);
        $sub = usersFactory::filterStrings($_POST["sub"]);
        $score = $_POST["score"];
        //adapter
        $studentList = $_SESSION["studentList"];
        foreach($studentChosen as $sc){
            $sc = usersFactory::filterStrings($sc);
            $conn->query("UPDATE userSummative SET summative_score='".$score."', summative_feedback='".$fb."' WHERE studentid='".$sc."' AND subAssessment_name='".$sub."'");
            foreach($studentList->SelectByID($sc)->getMod()->getAllComponent() as $c){
                foreach($c->getSub() as $s){
                    if($s->getName() == $sub ){
                        $s->giveSummativeFeedback($fb, $score);
                    }
                }
            }  
        } 
        $msg .= "Feedbacks added successfully";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addSummative.php");
}else{
    if(!isset($_POST["studentList"])){
        $msg .= "Please choose at least 1 students.\n";
    }
    if(!isset($_POST["feedback"])){
        $msg .= "Please enter a valid feedback.\n";
    }
    if(!isset($_POST["score"])){
        $msg .= "Please enter a valid score.\n";
    }
    if($_POST["score"] < 0 || $_POST["score"] > 100){
        $msg .= "Score entered Out of Range.\n";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addSummative.php");
}

