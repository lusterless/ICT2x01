<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once "classes/users.class.php";
include_once "sqlConnection.php";

//declare userCredentials Class
$username = $_POST["username"];
$password = $_POST["password"];

if ($conn->connect_error)
{
    session_start();
    $_SESSION['dberror']=$conn->connect_error;
    echo "<h1>".$_SESSION['dberror']."</h1>";
}
else{
    $sql = "SELECT * FROM users WHERE email='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($row["role"]=="student"){
            $student = new students($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["module"], $username);
            //more extract
            session_start();
            $_SESSION["sessionInfo"]= $student;
            header("Location:visualGame.php");
        }
        else{
            $professor = new Professor($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["module"], $username);
            session_start();
            //get start end date
            //add enrolled students
            $_SESSION["sessionInfo"]=$professor;
            header("Location:createPageProf.php");
        }
    }
    else{
        header("Location:loginPage.php");
    }
    $result->free_result();
    unset($row);
}
$conn->close();
die;
