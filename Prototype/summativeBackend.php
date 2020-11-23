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

$msg = "";
//summativeFeedback
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sub"]) && (isset($_POST["score"]) && $_POST["score"] >= 0 && $_POST["score"] <= 100) && isset($_POST["studentList"]) && (isset($_POST["feedback"]) && $_POST["feedback"]) != "" && isset($_POST["summativePage"])) {
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
            $conn->query("UPDATE userSummative SET summative_score='".$score."', summative_feedback='".$fb."', seen='0' WHERE studentid='".$sc."' AND subAssessment_name='".$sub."'");
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
}elseif($_SERVER['REQUEST_METHOD'] == 'POST' && isset ($_POST['sarrayFeedback']) && $_POST['sarrayFeedback']!="" && isset($_POST["summativePage"])&& isset($_POST["sub"]) ){
    //import via files
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $information = json_decode($_POST['sarrayFeedback'], true);
        //adapter
        $sub = usersFactory::filterStrings($_POST["sub"]);
        $studentList = $_SESSION["studentList"];
        //insert into db
        foreach($information as $sf){
            $parafirst = usersFactory::filterStrings($sf[0]); //id
            $parasec = usersFactory::filterStrings($sf[1]);    //fb
            $parathird = usersFactory::filterStrings($sf[2]);  //score
            var_dump($parafirst);
            $conn->query("UPDATE userSummative SET summative_score='".$parathird."', summative_feedback='".$parasec."', seen='0' WHERE studentid='".$parafirst."' AND subAssessment_name='".$sub."'");
            foreach($studentList->SelectByID($parafirst)->getMod()->getAllComponent() as $c){
                foreach($c->getSub() as $s){
                    if($s->getName() == $sub ){
                        $s->giveSummativeFeedback($parasec, $parathird);
                    }
                }
            } 
            
        }
        $msg .= "Files Feedbacks added successfully";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addSummative.php");
}
else{
    if(!isset($_POST["studentList"])){
        $msg .= "<p>Please choose at least 1 students.</p>";
    }
    if($_POST["feedback"] == ""){
        $msg .= "<p>Please enter a valid feedback.</p>";
    }
    if(!isset($_POST["score"]) || $_POST["score"] == ""){
        $msg .= "<p>Please enter a valid score.</p>";
    }
    if($_POST["score"] < 0 || $_POST["score"] > 100){
        $msg .= "<p>Score entered Out of Range.</p>";
    }
    
    if(!isset($_POST['sarrayFeedback']) || $_POST['sarrayFeedback']==""){
        $msg .= "<p>Alternatively, you may insert a file.</p>";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addSummative.php");
}

