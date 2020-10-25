<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define("DBHOST", "localhost");
define("DBNAME", "users");
define("DBUSER", "root");
define("DBPASS", "");

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);