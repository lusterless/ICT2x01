<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "IProfessorGateway.php";
//List of students enrolled into the module
class ProfessorDictionaryAdapter implements iProfessorGateway{
    private $studentList = []; //list of "users" type class variable
    public function Insert($student){$this->studentList[] = $student;}
    public function SelectAll(){return $this->studentList;}
    public function SelectByID($id){
        $user = "";
        foreach($this->studentList as $s){
            if($id == $s->getUser()){
                $user = $s;
                break;
            }
        }
        return $user;
    }
    public function Remove($id){
        foreach($this->studentList as $s){
            if($id == $s->getUser()){
                $key = array_search($s, $this->studentList);
                unset($this->studentList[$key]);
                break;
            }
        }
    }
}