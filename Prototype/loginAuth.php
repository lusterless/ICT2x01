<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once "classes/users.class.php";
include_once "classes/module.class.php";
include_once "classes/usersFactory.php";
include_once "classes/ProfessorDictionaryAdapter.php";
include_once "sqlConnection.php";

//declare userCredentials Class
$username = usersFactory::filterStrings($_POST["username"]);
$password = usersFactory::filterStrings($_POST["password"]);
$errormsg = "";
if(strlen($password) < 8){
    $errormsg .= "Please enter a password of minimum 8 characters<br>";
}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["login"])){
    if ($conn->connect_error)
    {
        session_start();
        $errormsg .= $conn->connect_error;
        $_SESSION["errormsg"] = $errormsg;
        header("Location:loginPage.php");
    }
    else{
        $result = usersFactory::authenticateCredentials($conn, $username);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $status = usersFactory::checkAccountLocked($row);
            if($row["password"] == $password){
                if($status == true){
                    $user = usersFactory::createUser($row, $conn);
                    session_start();
                    if($row["role"] != "professor"){
//                        $user = usersFactory::getSummativeFeedback($conn, $user);
//                        $user = usersFactory::getFormativeFeedback($conn, $user);
                        $_SESSION["sessionInfo"]= $user;
                        header("Location:visualGame.php");
                    }else{
                        if($row["module"] != ""){
                            $studentList = usersFactory::getAllEnrollStudents($conn, $row["module"]);
                            $_SESSION["studentList"] = $studentList;
                        }
                        $_SESSION["sessionInfo"]= $user;
                        header("Location:createPageProf.php");
                    }
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
                    $errormsg .= "Incorrect Username/Password";
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
}else{
    header("Location:loginPage.php");
}
$conn->close();
die;
