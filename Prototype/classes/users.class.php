<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

include "Iusers.class.php";
include "studentsComposite.php";
//abstract class Users{
//
//    public function getMod(){return $this->module;}
//    public function getTel(){return $this->tel;}
//    public function getName(){return $this->name;}
//    public function getUser(){return $this->user;}
//    public function getRole(){return $this->role;}  
//    public function getEmail(){return $this->email;}     
//    public function setMod($mod){$this->module = $mod;}
//}

class students implements iusers, studentsComposite{
    private $user, $name, $tel, $role, $module, $email;
    public function __construct($tel, $name, $user, $role, $email){
        $this->user = $user;
        $this->tel = $tel;
        $this->name = $name;
        $this->role = $role;
        $this->email=$email;
    }
    //iusers-
    public function getMod(){return $this->module;}
    public function getTel(){return $this->tel;}
    public function getName(){return $this->name;}
    public function getUser(){return $this->user;}
    public function getRole(){return $this->role;}  
    public function getEmail(){return $this->email;}     
    public function setMod($mod){$this->module = $mod;}
    //students composite, recursive get info
    public function getStudents() {
        return self::getMod();
    }
    public function studentPush($student) {
        throw new Exception("Not implemented");
    }
}

class Professor implements iusers, studentsComposite{
    private $student = [];
    private $user, $name, $tel, $role, $module, $email;
    public function __construct($tel, $name, $user, $role, $email){
        $this->user = $user;
        $this->tel = $tel;
        $this->name = $name;
        $this->role = $role;
        $this->email=$email;
    }
    //students composite
    public function studentPush($student){$this->student[]= $student;}
    public function getStudents(){
        foreach($this->student as $s){
            $s->getStudents();
        } 
    }
    //iusers
    public function getMod(){return $this->module;}
    public function getTel(){return $this->tel;}
    public function getName(){return $this->name;}
    public function getUser(){return $this->user;}
    public function getRole(){return $this->role;}  
    public function getEmail(){return $this->email;}     
    public function setMod($mod){$this->module = $mod;}
}
