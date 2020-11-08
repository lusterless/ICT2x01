<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface ifeedback{
    public function getScores();
    public function giveScores($scores);
    public function giveFormativeFeedback($feedback);
    public function getFormativeFeedback();
    public function giveSummativeFeedback($feedback, $score);
    public function getSummativeFeedback();
}