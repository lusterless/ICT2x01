<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface iusers{
    public function getMod();
    public function getTel();
    public function getName();
    public function getUser();
    public function getRole();
    public function getEmail();   
    public function setMod($mod);
}
