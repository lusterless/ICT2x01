<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class deleteFactory{
    public static function clearAll($conn, $modID){
        $conn->query("UPDATE users SET module=NULL WHERE module='$modID';");
        //delete everything from module table
        $conn->query("DELETE FROM Module WHERE module_id='$modID';");
        //delete everything from assessments
        $conn->query("DELETE FROM assessments WHERE module_id='$modID';");
        //delete everything from subAssessments
        $conn->query("DELETE FROM subAssessments WHERE module_id='$modID';"); 
        //delete module from session
        $conn->query("DELETE FROM userSummative;");
        $conn->query("DELETE FROM userFormative;");
    }
    
}