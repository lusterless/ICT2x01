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
                    //store into session variables
                    $modulee = loginControl::getModuleInfo($conn,$row["module"]);
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
                    //store into session variables
                    $modulee = loginControl::getModuleInfo($conn,$row["module"]);
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
