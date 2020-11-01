<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once "classes/users.class.php";
include_once "classes/module.class.php";
include_once "classes/feedbacks.class.php";
include_once "classes/login.control.php";
include_once "sqlConnection.php";

//declare userCredentials Class
$username = loginControl::filterStrings($_POST["username"]);
$password = loginControl::filterStrings($_POST["password"]);
$errormsg = "";

if ($conn->connect_error)
{
    session_start();
    $errormsg .= $conn->connect_error;
    session_start();
    $_SESSION["errormsg"] = $errormsg;
    header("Location:loginPage.php");
}
else{
    $result = loginControl::authenticateCredentials($conn, $username);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $status = loginControl::checkAccountLocked($row);
        if($row["password"] == $password){
            if($row["role"]=="student" && $status == true){
                $student = new students($row["tel"], $row["name"], $row["studentid"], $row[ "role"], $username);
                //more extract
                if($row["module"]!=""){
                    //create module
                    $mod = $row["module"];
                    $modResult = $conn->query("SELECT * FROM Module WHERE module_id='$mod'");
                    $modRow = $modResult->fetch_assoc();
                    $modulee = new Module($modRow['module_name'], $modRow['start_date'], $modRow['end_date'], 0);
                    //create component
                    $compResult = $conn->query("SELECT * FROM assessments WHERE module_id='$mod'");
                    while($compRow = $compResult->fetch_assoc()){
                        $modulee->pushComponent($compRow['assessment_id'],$compRow['assessment_name'], $compRow['assessment_weightage']);
                    }
                    //create subcomponent
                    foreach ($modulee->getAllComponent() as $c){
                        $assID = $c->getID();
                        $subCompResult = $conn->query("SELECT * FROM subAssessments WHERE module_id='$mod' AND assessment_id='$assID'");
                        while($subCompRow = $subCompResult->fetch_assoc()){
                            $c->pushSubComponent($subCompRow["subAssessment_name"], $subCompRow["subAssessment_weightage"]);
                        }
                    }
                    //store into session variables
                    $student->setMod($modulee);
                }
                session_start();
                $_SESSION["sessionInfo"]= $student;
                //reset account counter
                $conn->query("UPDATE users SET count='0' WHERE email='$username'");
                header("Location:visualGame.php");
            }
            else if ($row["role"] == "professor" && $status == true){
                $professor = new Professor($row["tel"], $row["name"], $row["studentid"], $row["role"], $username);
                if($row["module"]!=""){
                    //create module
                    $mod = $row["module"];
                    $modResult = $conn->query("SELECT * FROM Module WHERE module_id='$mod'");
                    $modRow = $modResult->fetch_assoc();
                    $modulee = new Module($modRow['module_name'], $modRow['start_date'], $modRow['end_date'], 0);
                    //create component
                    $compResult = $conn->query("SELECT * FROM assessments WHERE module_id='$mod'");
                    while($compRow = $compResult->fetch_assoc()){
                        $modulee->pushComponent($compRow['assessment_id'],$compRow['assessment_name'], $compRow['assessment_weightage']);
                    }
                    //create subcomponent
                    foreach ($modulee->getAllComponent() as $c){
                        $assID = $c->getID();
                        $subCompResult = $conn->query("SELECT * FROM subAssessments WHERE module_id='$mod' AND assessment_id='$assID'");
                        while($subCompRow = $subCompResult->fetch_assoc()){
                            $c->pushSubComponent($subCompRow["subAssessment_name"], $subCompRow["subAssessment_weightage"]);
                        }
                    }
                    //store into session variables
                    $professor->setMod($modulee);
                }
                

                
                session_start();
                $_SESSION["sessionInfo"]=$professor;
                $conn->query("UPDATE users SET count='0' WHERE email='$username'");
                header("Location:createPageProf.php");
            }
            else{
                $errormsg .= "Account locked, Please contact your administrator";
                session_start();
                $_SESSION["errormsg"] = $errormsg;
                header("Location:loginPage.php");
            }
        }else{
            if($status == false){
                $errormsg = "Account locked, Please contact your administrator";
            }
            else{
                $count = $row["count"] += 1;
                $conn->query("UPDATE users SET count='$count' WHERE email='$username'");
                $errormsg = "Incorrect Password";
            }
            session_start();
            $_SESSION["errormsg"] = $errormsg;
            header("Location:loginPage.php");
        }
    }
    else{
        $errormsg .= "Incorrect Username/Password";
        session_start();
        $_SESSION["errormsg"] = $errormsg;
        header("Location:loginPage.php");
    }
    $result->free_result();
    unset($row);
}
$conn->close();
die;
