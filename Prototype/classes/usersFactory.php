<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class usersFactory{
    public static function createUser($role, $row, $conn){
        $username = $row["email"];
//        if($role == "professor"){
        $user = new users($row["tel"], $row["name"], $row["studentid"], $row[ "role"], $row["email"]); //create object variable  
        if($row["module"]!=""){
            //store into session variables
            $modulee = self::getModuleInfo($conn,$row["module"]);
            $user->setMod($modulee);
        }
        $conn->query("UPDATE users SET count='0' WHERE email='$username'");
        return $user; //return object variable 
//        }else{
//            $student = new students($row["tel"], $row["name"], $row["studentid"], $row[ "role"], $row["email"]); //create object variable    
//            if($row["module"]!=""){
//                //store into session variables
//                $modulee = self::getModuleInfo($conn,$row["module"]);
//                $student->setMod($modulee);
//            }
//            //reset account counter
//            $conn->query("UPDATE users SET count='0' WHERE email='$username'");
//            return $student; //return object variable           
//        }
    }
    
    public static function filterStrings($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    public static function authenticateCredentials($conn, $user){
        $sql = "SELECT * FROM users WHERE email='$user'";
        return $conn->query($sql);
    }
    
    public static function checkAccountLocked($row){
        if($row["count"] >= 10){
            return false;
        }
        else{
            return true;
        }
    }
    
    public static function getModuleInfo($conn,$mod){
        $modResult = $conn->query("SELECT * FROM Module WHERE module_id='$mod'");
        $modRow = $modResult->fetch_assoc();
        $modulee = new Module($modRow['module_name'], $modRow['start_date'], $modRow['end_date'], 0, $mod);
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
        return $modulee;
    }
}