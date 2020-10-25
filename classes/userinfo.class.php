<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class moduleInfo{
    private $nameOfMod, $startDate, $endDate, $componentInfo, $totalEnrolled;
    public function set_mod($mod){$this->nameOfMod=$mod;}
    public function set_sDate($sDate){$this->startDate=$sDate;}
    public function set_eDate($eDate){$this->endDate=$eDate;}
    //array of componentandSub
    public function set_componentInfo($component){
        $this->componentInfo = array_push($this->componentInfo,$component);
    }
    public function set_tEnrol($TE){$this->totalEnrolled=$TE;}
    public function get_mod(){return $this->nameOfMod;}
    public function get_sDate(){return $this->startDate;}
    public function get_eDate(){return $this->endDate;}
    public function get_cInfo(){return $this->componentInfo;}
    public function get_tEnroll(){return $this->totalEnrolled;}
}

class componentAndSub{
    //subComponents array of subdetails class
    private $componentName, $componentWeightage, $subComponents;
    public function setInfo($cName,$cWeight){
        $this->componentName=$cName;
        $this->componentWeightage=$cWeight;
    }
    public function push_subC($subC){
        if($subC->returnWeight() <= $componentWeightage){
            $this->subComponents= array_push($this->subComponents,$subC);
            $componentWeightage -= $subC->returnWeight();
        }
    }
    public function returnCName(){return $this->componentName;}
    public function returnWeight(){return $this->componentWeightage;}
    public function returnSub(){return $this->subComponents;}
}
class subCDetails{
    private $subComponentName, $subComponentWeightage;
    public function set_sub($subC){$this->subComponentName=$subC;}
    public function set_weightage($subW){$this->subComponentWeightage=$subW;}
    public function returnWeight(){return $this->subComponentWeightage;}
    public function returnName(){return $this->subComponentName;}
}


abstract class generalUsers{
    protected $name,$tel,$role,$user,$module;
    public function set_credentials($name,$user,$tel,$role){
        $this->name=$name;
        $this->tel=$tel;
        $this->user=$user;
        $this->role=$role;
    }
    public function get_tel(){return $this->tel;}
    public function get_name(){return $this->name;}
    public function get_user(){return $this->user;}
    public function get_role(){return $this->role;}
    //SQL connection to get all info about modules from DB using moduleInfo Class
    public function getDBModuleInfo(){/*get Module info from DB*/}
    private function set_module($moduleInfo){$this->module=$moduleInfo;}
    public function getModule(){return $module;}
}
//things a summative comments will include
class summativeComments{
    private $scores, $comments, $subComponentName;
    public function set_subC($subC){$this->subComponentName=$subC;}
    public function set_score($scores){$this->scores=$scores;}
    public function set_comments($comments){$this->comments=$comments;}
}
class perStudent extends generalUsers implements showInformation{
    //array of summativeComments class
    //array of formative comments string
    private $summativeComments, $formativeComments;
    public function getRelevantInfoFromDB(){/*Go to db and collect summative and formative, user push_summative and formative*/}
    private function push_summative($sComments){$this->summativeComments=array_push($this->summativeComments,$sComments);}
    private function push_formative($fComments){$this->formativeComments=array_push($this->formativeComments,$fComments);}
    public function returnSummative(){return $this->summativeComments;}
    public function returnFormative(){return $this->formativeComments;}
}
class perProf extends generalUsers implements showInformation{
    private $arrayOfManyStudents;
    public function getRelevantInfoFromDB(){/*get all, loop all, create student, store inside arrayOfManyStudents using push_students */ }
    public static function insertFormativeInDB($studentID, $module, $feedback){/*insert DB codes*/}
    public static function insertSummativeInDB($studentID, $module, $subComponent/*class summativeComments*/){/*insert DB Codes*/}
    private function push_students($perStudent){$this->arrayOfManyStudents= array_push($this->arrayOfManyStudents,$perStudent);}
    public function returnArray(){return $this->arrayOfManyStudents;}
}
interface showInformation{
    public function getRelevantInfoFromDB();
}

/*class PerStudentInfo{
    private $nameOfMod, $startDate, $endDate, $summativeArray, $formativeArray;
    public function set_mod($mod){$this->nameOfMod=$mod;}
    public function set_startDate($sDate){$this->startDate=$sDate;}
    public function set_endDate($eDate){$this->endDate=$eDate;}
    public function set_summativeArray($array){}
    public function set_formativeArray($array){}
    public function get_mod(){return $this->nameOfMod;}
    public function get_startDate(){return $this->startDate;}
    public function get_endDate(){return $this->endDate;}
    //public function get_summativeDict(){return $this->summativeDict;}   
    //public function get_formativeArray(){return $this->formativeArray;}      
}



abstract class user{
    protected $user, $pass, $name, $tel, $role;
    protected function setCredentials($user,$pass, $name, $tel, $role){
        $this->user=$user;
        $this->pass=$pass;
        $this->role=$role;
        $this->tel=$tel;
        $this->name=$name;
    }
    public function get_tel(){
        return $this->tel;
    }
    public function get_name(){
        return $this->name;
    }
    public function get_user(){
        return $this->user;
    }
    public function get_role(){
        return $this->role;
    }
    //retrieve, will be use for student constructor
    protected function set_summative(){}
    //retrieve, will be use for student constructor
    protected function set_formative(){}
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    

class Student extends user implements showInformation{
    private $moduleInformation;
    function __construct($user,$pass,$name, $tel, $role, $row) {
        parent::setCredentials($user,$pass,$name, $tel, $role);
        if(!is_null($row["Module"])){
            $this->moduleInformation = new PerStudentInfo();
            $this->moduleInformation->set_mod($row["Module"]);
            //startdate
            //enddate
            //summative
            //formative           
        }
    }
    public function getStudentInfo(){return $this->moduleInformation;}
}

class Professor extends user implements showInformation{
    protected $allInformation;
    function __construct($user,$pass,$name,$tel,$role) {
        parent::setCredentials($user,$pass,$name,$tel,$role);
        //for loop take all information, create PerStudentInfo and put inside an array in all information
    }
    //display student info
    public function getStudentInfo(){}
    public static function insertFormative($studentID, $feedback){}
    public static function insertSummative($studentID, $subComponent, $scores, $feedback){}
}*/ 


?>
