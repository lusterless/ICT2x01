<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class summativeFeedbacks{
    private $scores, $feedback;
    public function __construct($score,$fb){
        $this->scores = $score;
        $this->feedback=$fb;
    }
    public function returnScores(){return $this->scores;}
    public function returnFB(){return $this->feedback;}
}

class formativeFeedbacks{
    private $feedback;
    public function __construct($fb){
        $this->feedback=$fb;
    }
    public function returnFB(){return $this->feedback;}
}