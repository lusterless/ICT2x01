<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include "IFeedbackComponent.php";
include "feedbacks.class.php";
/*Example codes
 * $module = new Module(ICT2x01, 17/10/20, 20/12/20, 50)
 * $module->pushComponent(Exam, 40)
 * $tempComp = $module->getComponent(0)
 * $tempComp->pushSubComponent(Exam1, 20)
 * $tempComp->pushSubComponent(Exam2, 20)
 * ######Composition Code########
 * 
 *  */
class Module implements ifeedback{
    private $modID, $startDate, $endDate, $totalEnrol, $modNo;
    private $formativeFeedback = [];
    private $components = [];
    public function __construct($modID, $startDate, $endDate, $enrol, $modNo) {
        $this->modID = $modID;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalEnrol = $enrol;
        $this->modNo = $modNo;
    }
    public function pushComponent($componentID, $componentName, $componentWeight){
        $comp = new Component($componentID, $componentName, $componentWeight);
        $this->components[] = $comp;
    }
    public function getNumber(){return $this->modNo;}
    public function getMod(){return $this->modID;}
    public function getStart(){return $this->startDate;}
    public function getEnd(){return $this->endDate;}
    public function getTotalEnroll(){return $this->totalEnrol;}
    public function getAllComponent(){return $this->components;}
    //ifeedback interface
    public function getScores(){throw new Exception("Not implemented");}
    public function giveScores($scores){throw new Exception("Not implemented");}
    public function giveSummativeFeedback($feedback, $scores){throw new Exception("Not implemented");}
    public function getSummativeFeedback(){throw new Exception("Not implemented");}
    public function giveFormativeFeedback($feedback){
        $fb = new formativeFeedbacks($feedback);
        $this->formativeFeedback[] = $fb;
    }
    /*composite getFormativefeedback(). The leaf is the formativefeedback class itself which implementes ifeedback interface class.
    This module class also implements ifeedback interface class. Performs recursive formativeFeedback retrieval as 1 user can have
    more than 1 feedback*/
    public function getFormativeFeedback(){
        $allFormative[] = "";
        foreach($this->formativeFeedback as $f){
            $allFormative[] = $f->getFormativeFeedback() . "\n";
        }
        return $allFormative;
    }
}

class Component{
    private $componentID, $componentName, $componentWeight;
    private $subComponent = [];
    public function __construct($componentID, $componentName, $componentWeight){
        $this->componentID = $componentID;
        $this->componentWeight = $componentWeight;
        $this->componentName = $componentName;
    }
    public function pushSubComponent($name, $weight){
        $sub = new subComponent($name, $weight);
        //$this->subComponent = array_push($this->subComponent, (object) $sub);
        $this->subComponent[] = $sub;
    }
    public function getID(){return $this->componentID;}
    public function getWeight(){return $this->componentWeight;}
    public function getSub(){return $this->subComponent;}
    public function getName(){return $this->componentName;}
}

class subComponent implements ifeedback{
    private $subComponentName, $subComponentWeight, $summativeFeedback;
    public function __construct($name, $weight){
        $this->subComponentName = $name;
        $this->subComponentWeight = $weight;
    }
    public function getName(){return $this->subComponentName;}
    public function getWeight(){return $this->subComponentWeight;}
    //ifeedback
    public function giveSummativeFeedback($feedback, $scores){
        $summative = new summativeFeedbacks($feedback, $scores);
        $this->summativeFeedback = $summative;
    }
    public function getSummativeFeedback(){return $this->summativeFeedback->getSummativeFeedback();}
    public function getScores(){return $this->summativeFeedback->getScores();}
    public function giveFormativeFeedback($feedback){throw new Exception("Not implemented");}
    public function getFormativeFeedback(){throw new Exception("Not implemented");}
    public function giveScores($scores){throw new Exception("Not implemented");}
}