<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//dictionary of students of type users
class ProfessorDictionaryAdapter implements iProfessorGateway{
    private $studentList = [];
    public function Insert($student){$this->studentList[] = $student;}
    public function SelectAll(){return $this->$studentList;}
}