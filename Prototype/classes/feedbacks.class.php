<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class summativeFeedbacks implements ifeedback{
    private $scores, $feedback;
    public function __construct($feedback, $scores){
        self::giveSummativeFeedback($feedback,$scores);
    }
    public function getScores(){return $this->scores;}
    public function giveScores($scores){$this->scores = $scores;}
    public function giveSummativeFeedback($feedback, $scores){
        $this->feedback = $feedback;
        self::giveScores($scores);
    }
    public function getSummativeFeedback(){return $this->feedback;}
    public function giveFormativeFeedback($feedback){throw new Exception("Not implemented");}
    public function getFormativeFeedback(){throw new Exception("Not implemented");}
}

class formativeFeedbacks implements ifeedback{
    private $feedback;
    public function __construct($feedback){
        self::giveFormativeFeedback($feedback);
    }
    public function getScores(){throw new Exception("Not implemented");}
    public function giveScores($scores){throw new Exception("Not implemented");}
    public function giveSummativeFeedback($feedback, $scores){throw new Exception("Not implemented");}
    public function getSummativeFeedback(){throw new Exception("Not implemented");}
    public function giveFormativeFeedback($feedback){$this->feedback = $feedback;}
    public function getFormativeFeedback(){return $this->feedback;}
}