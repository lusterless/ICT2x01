<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

class feedbackFactory{
     public static function createFeedback($feedbacktype, $feedback, $scores, $seen = 0){
         if($feedbacktype == "summative"){
             return new summativeFeedbacks($feedback, $scores, $seen);
         }elseif($feedbacktype == "formative"){
             return new formativeFeedbacks($feedback);
         }
     }
}
