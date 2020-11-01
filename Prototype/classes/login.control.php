<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class loginControl{
    public static function filterStrings($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    public static function authenticateCredentials($conn, $user){
        $sql = "SELECT * FROM users WHERE email='$user'";
        return $conn->query($sql);
    }
    
    public static function checkAccountLocked($row){
        if($row["count"] >= 10){
            return false;
        }
        else{
            return true;
        }
    }
}