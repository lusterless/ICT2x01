<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

include "Iusers.class.php";
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

//every user can be a student or Professor
//extra professor methods will be use with an adapter design pattern
class users implements iusers{
    private $user, $name, $tel, $role, $module = "", $email;
    public function __construct($tel, $name, $user, $role, $email){
        $this->user = $user;
        $this->tel = $tel;
        $this->name = $name;
        $this->role = $role;
        $this->email=$email;
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
