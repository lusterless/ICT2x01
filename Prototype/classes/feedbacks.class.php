<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class summativeFeedbacks implements ifeedback{
    private $scores, $feedback, $seen;
    public function __construct($feedback, $scores, $seen){
        self::giveSummativeFeedback($feedback,$scores, $seen);
    }
    public function getSeen(){return $this->seen;}
    public function giveSeen($seen){$this->seen = $seen;}
    public function getScores(){return $this->scores;}
    public function giveScores($scores){$this->scores = $scores;}
    public function giveSummativeFeedback($feedback, $scores, $seen){
        $this->feedback = $feedback;
        self::giveScores($scores);
        self::giveSeen($seen);
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
    public function getSeen(){throw new Exception("Not implemented");}
    public function giveSeen($seen){throw new Exception("Not implemented");}
    public function getScores(){throw new Exception("Not implemented");}
    public function giveScores($scores){throw new Exception("Not implemented");}
    public function giveSummativeFeedback($feedback, $scores, $seen){throw new Exception("Not implemented");}
    public function getSummativeFeedback(){throw new Exception("Not implemented");}
    public function giveFormativeFeedback($feedback){$this->feedback = $feedback;}
    public function getFormativeFeedback(){return $this->feedback;}
}