<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
class feedbackFactory{
    public static function addFormative($conn, $id, $feedback) : void{
        $conn->query("INSERT INTO userFormative (studentid, formative_feedback) VALUES ('".$id."','".$feedback."');");
    }
    public static function addSummative($conn, $id, $feedback, $subAss, $score) : void{
        $conn->query("INSERT INTO userSummative (studentid, summative_score, summative_feedback, subAssessment_name) VALUES ('".$id."','".$score."','".$feedback."','".$subAss."');");        
    }
}
