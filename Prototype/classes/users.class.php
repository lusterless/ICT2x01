<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

abstract class Users{
    protected $user, $name, $tel, $role, $module;
    protected function __construct($tel, $name, $user, $role, $module){
        $this->user = $user;
        $this->tel = $tel;
        $this->name = $name;
        $this->role = $role;
        $this->module=$module;
    }
    public function getMod(){return $this->module;}
    public function getTel(){return $this->tel;}
    public function getName(){return $this->name;}
    public function getUser(){return $this->user;}
    public function getRole(){return $this->role;}    
}

class students extends Users{
    private $summativeFeedbacks, $formativeFeedbacks;
    public function __construct($tel, $name, $user, $role, $module){
        parent::__construct($tel, $name, $user, $role, $module);
    }
    public function setSummative($summativeFeedback){
        $this->summativeFeedbacks=array_push($this->summativeFeedbacks,$summativeFeedback);
    }
    public function setFormative($formativeFeedback){
        $this->formativeFeedbacks=array_push($this->formativeFeedbacks,$formativeFeedback);
    }
    public function getSummative(){return $this->summativeFeedbacks;}
    public function getFormative(){return $this->formativeFeedbacks;}
}

class Professor extends Users{
    private $student;
    public function __construct($tel, $name, $user, $role, $module){
        parent::__construct($tel, $name, $user, $role, $module);
    }
    public function studentPush($student){$this->student= array_push($this->student,$student);}
    public function getStudents(){return $this->student;}
}

