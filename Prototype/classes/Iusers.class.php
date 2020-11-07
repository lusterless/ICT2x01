<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

interface iusers{
    public function getMod(): Module;
    public function getTel(): string;
    public function getName(): string;
    public function getUser(): string;
    public function getRole() : string;
    public function getEmail(): string;   
    public function setMod($mod) :void;
}