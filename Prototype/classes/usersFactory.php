<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class usersFactory{
    public static function createUser($row, $conn, $accountType, $moduleStatus){
        $username = $row["email"]; //username
        if($accountType == "professor"){ // account type is professor
            $studentList = "";
            $professor = new users($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["email"]); //create object variable  
            if($moduleStatus!=""){ //if accountType == professor && account enrolled into module
                $modulee = self::getModuleInfo($conn,$row["module"]); //get module information to be displayed
                $studentList = self::getAllEnrollStudents($conn, $row["module"]); //get every student information and return it as a variable
                $professor->setMod($modulee); //set all the module information inside the class variable to be return
            }
            $conn->query("UPDATE users SET count='0' WHERE email='$username'"); //reset login count
            return array($professor, $studentList); //return object variable 
        }else{ //account type is student
            $student = new users($row["tel"], $row["name"], $row["studentid"], $row["role"], $row["email"]); //create object variable  
            if($moduleStatus!=""){ //if accountType == student && account enrolled into module
                $modulee = self::getModuleInfo($conn,$row["module"]); //get module information to be displayed
                $student->setMod($modulee); //set all the module information inside the class variable to be return
                $student = usersFactory::getSummativeFeedback($conn, $student); //get summative feedback of particular student
                $student = usersFactory::getFormativeFeedback($conn, $student); //get formative feedback of particular student
            }
            $conn->query("UPDATE users SET count='0' WHERE email='$username'"); //reset login count
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
                $user = self::createUser($totalEnrolRow, $conn, $totalEnrolRow["role"], $totalEnrolRow["module"]);
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