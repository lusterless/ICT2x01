<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<a href="feedbacks.class.php"></a>
<?php

include "classes/feedbacks.class.php";

/*Example codes
 * $module = new Module(ICT2x01, 17/10/20, 20/12/20, 50)
 * $module->pushComponent(Exam, 40)
 * $tempComp = $module->getComponent(0)
 * $tempComp->pushSubComponent(Exam1, 20)
 * $tempComp->pushSubComponent(Exam2, 20)
 * ######Composition Code########
 * 
 *  */
class Module{
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
        //$this->components = array_push($this->components, (object) $comp);
        $this->components[] = $comp;
    }
    public function giveFormativeFeedback($feedback){
        $fb = new formativeFeedback($feedback);
        //$this->formativeFeedback = array_push($this->formativeFeedback, (object) $fb);
        $this->formativeFeedback[] = $fb;
    }
    public function getNumber(){return $this->modNo;}
    public function getMod(){return $this->modID;}
    public function getStart(){return $this->startDate;}
    public function getEnd(){return $this->endDate;}
    public function getAllComponent(){return $this->components;}
    //public function getComponent($number){return $this->components[$number];
    #eg; component[0]
    //}
    public function getTotalEnroll(){return $this->totalEnrol;}
    public function getFormativeFeedbacks(){return $this->formativeFeedback;}
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



class subComponent{
    private $subComponentName, $subComponentWeight, $summativeFeedback;
    public function __construct($name, $weight){
        $this->subComponentName = $name;
        $this->subComponentWeight = $weight;
    }
    public function giveSummativeFeedback($score,$fb){
        #summativeFeedbacks unlikes formativefeedback is not an array because each subcomponent only have 1 feedback,
        # but each component can have 4 subcomponents which will have up to 4 comments
        $this->summativeFeedback = new summativeFeedbacks($score,$fb);
    }
    public function getName(){return $this->subComponentName;}
    public function getWeight(){return $this->subComponentWeight;}
}