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
include "classes/feedbackFactory.php";

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
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["sub"]) && (isset($_POST["score"]) && $_POST["score"] >= 0 && $_POST["score"] <= 100) && isset($_POST["studentList"]) && (isset($_POST["feedback"]) && $_POST["feedback"]) != "" && isset($_POST["summative"])) {
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $studentChosen = $fb = $sub = $score = ""; //set variables
        $studentChosen = $_POST["studentList"];
        $fb = usersFactory::filterStrings($_POST["feedback"]);
        $sub = usersFactory::filterStrings($_POST["sub"]);
        $score = $_POST["score"];
        $studentList = $_SESSION["studentList"];
        $feedbackType = $_POST["summative"];
        foreach($studentChosen as $sc){
            $sc = usersFactory::filterStrings($sc);
            $summativeFeedback = feedbackFactory::createFeedback($feedbackType, $fb, $score);
            $conn->query("UPDATE userSummative SET summative_score='".$summativeFeedback->getScores()."', summative_feedback='".$summativeFeedback->getSummativeFeedback()."', seen=0 WHERE studentid='".$sc."' AND subAssessment_name='".$sub."'");
            foreach($studentList->SelectByID($sc)->getMod()->getAllComponent() as $c){
                foreach($c->getSub() as $s){
                    if($s->getName() == $sub ){
                        $s->giveSummativeFeedback($summativeFeedback->getSummativeFeedback(), $summativeFeedback->getScores(), 0);
                    }
                }
            }  
        } 
        $msg .= "Feedbacks added successfully";
    }
    $_SESSION["msg"] = $msg;
    header("Location:addSummative.php");
}elseif($_SERVER['REQUEST_METHOD'] == 'POST' && isset ($_POST['sarrayFeedback']) && $_POST['sarrayFeedback']!="" && isset($_POST["summative"])&& isset($_POST["sub"]) ){
    //import via files
    $checkerror = false;
    if ($conn->connect_error){
        $msg .= "Database Error\n";
    }else{
        $information = json_decode($_POST['sarrayFeedback'], true);
        //adapter
        $sub = usersFactory::filterStrings($_POST["sub"]);
        $studentList = $_SESSION["studentList"];
        //check for errors
        $feedbackType = $_POST["summative"];
        foreach($information as $sf){
            $parafirst = $sf[0]; //id
            $parasec = $sf[1];    //fb
            $parathird = $sf[2];  //score
            $summativeFeedback = feedbackFactory::createFeedback($feedbackType, $parasec, $parathird);
            if($studentList->SelectByID($parafirst) == false){
                $checkerror = true;
                $msg .= "<p>ID ". $parafirst . " does not exist or is not enrolled in this module currently</p>";
            }else{
                if($parasec == null){
                    $checkerror = true;
                    $msg .= "<p>ID ". $parafirst . " cannot have empty feedback</p>";
                }elseif(is_string($parasec) == false){
                    $checkerror = true;
                    $msg .= "<p>ID ". $parafirst . " has no valid feedback</p>";
                }
                if($parathird == null){
                    $checkerror = true;
                    $msg .= "<p>ID ". $parafirst . " score cannot be empty</p>";
                }elseif(is_integer($parathird) == false){
                    $checkerror = true;
                    $msg .= "<p>Score in ID ". $parafirst . " in not an integer</p>";
                }elseif($parathird < 0 || $parathird > 100){
                    $checkerror = true;
                    $msg .= "<p>Score in ID ". $parafirst . " is out of range</p>";
                }
            }
        }
        //insert into db
        if($checkerror == false){
            foreach($information as $sf){
                $parafirst = usersFactory::filterStrings($sf[0]); //id
                $parasec = usersFactory::filterStrings($sf[1]);    //fb
                $parathird = usersFactory::filterStrings($sf[2]);  //score
                //var_dump($parafirst);
                $conn->query("UPDATE userSummative SET summative_score='".$parathird."', summative_feedback='".$parasec."', seen=0 WHERE studentid='".$parafirst."' AND subAssessment_name='".$sub."'");
                foreach($studentList->SelectByID($parafirst)->getMod()->getAllComponent() as $c){
                    foreach($c->getSub() as $s){
                        if($s->getName() == $sub ){
                            $s->giveSummativeFeedback($parasec, $parathird, 0);
                        }
                    }
                }      
            }
            $msg .= "Files Feedbacks added successfully";
        }
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

