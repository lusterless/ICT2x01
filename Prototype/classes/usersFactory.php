<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class usersFactory{
    public static function createUser($row, $conn){
        $role = $row["role"];
        $username = $row["email"];
        if($role == "professor"){
            $studentList = "";
            $professor = new users($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["email"]); //create object variable  
            if($row["module"]!=""){
                //store into session variables
                $modulee = self::getModuleInfo($conn,$row["module"]);
                $studentList = self::getAllEnrollStudents($conn, $row["module"]);
                $professor->setMod($modulee);
            }
            $conn->query("UPDATE users SET count='0' WHERE email='$username'");
            return array($professor, $studentList); //return object variable 
        }else{
            $student = new users($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["email"]); //create object variable  
            if($row["module"]!=""){
                //store into session variables
                $modulee = self::getModuleInfo($conn,$row["module"]);
                $student->setMod($modulee);
            }
            $conn->query("UPDATE users SET count='0' WHERE email='$username'");
            $user = usersFactory::getSummativeFeedback($conn, $student);
            $user = usersFactory::getFormativeFeedback($conn, $student);
            return $student; //return object variable             
        }
    }
    //get module info for module table
    public static function getModuleInfo($conn,$mod){
        $totalEnrolResult = $conn->query("SELECT * FROM users WHERE module='$mod'");
        $modResult = $conn->query("SELECT * FROM Module WHERE module_id='$mod'");
        $modRow = $modResult->fetch_assoc();
        $modulee = new Module($modRow['module_name'], $modRow['start_date'], $modRow['end_date'], $totalEnrolResult->num_rows, $mod);
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
    //get all enrolled students, function for professor (Adaptor)
    public static function getAllEnrollStudents($conn,$mod){
        $studentsList = new ProfessorDictionaryAdapter();
        $totalEnrolResult = $conn->query("SELECT * FROM users WHERE module='$mod'");
        while($totalEnrolRow = $totalEnrolResult->fetch_assoc()){
            if($totalEnrolRow["role"] != "professor"){
                $user = self::createUser($totalEnrolRow, $conn);
                $user = self::getSummativeFeedback($conn, $user);
                $user = self::getFormativeFeedback($conn, $user);
                $studentsList->Insert($user);
            }
        }
        return $studentsList;
    }
    
    //getFeedbacks for Students
    public static function getSummativeFeedback($conn, $user){
        $id = $user->getUser();
        $summativeComments = $conn->query("SELECT * FROM userSummative WHERE studentid='$id'");
        if($summativeComments->num_rows > 0){
            while($summativeCrows = $summativeComments->fetch_assoc()){
                foreach($user->getMod()->getAllComponent() as $c){
                    foreach($c->getSub() as $f){
                        $subName = $f->getName();
                        if($subName ==  $summativeCrows["subAssessment_name"]){
                            $f->giveSummativeFeedback($summativeCrows["summative_feedback"], $summativeCrows["summative_score"], $summativeCrows["seen"]);
                        }
                    }
                }
            }
        }
        return $user;
    }
    //give formative
    public static function getFormativeFeedback($conn, $user){
        $id = $user->getUser();
        $formativeComments = $conn->query("SELECT * FROM userFormative WHERE studentid='$id'");
        if($formativeComments->num_rows > 0){
            while($formativeRows = $formativeComments->fetch_assoc()){
                $module = $user->getMod();
                $module->giveFormativeFeedback($formativeRows["formative_feedback"]);
            }
        }
        return $user;
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
}