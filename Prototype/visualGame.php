<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include "classes/users.class.php";

session_start();
$Details = "";
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "student"){
        header("Location:loginPage.php");
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/userGame.css">
        <link rel="stylesheet" type="text/css" href="css/footer.css">
        <!-- For Navbar -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">      
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <title></title>
    </head>
    <body id="gameBody">
        <?php
            include "navBar.php";
        ?>
        <canvas id="interactiveCanvas" width="900" height="230"></canvas>
        <?php
            include "footer.php";
        ?>
    </body>
    <script>
        //current Date for tracking
        var n =  new Date(); //current date
        y = n.getFullYear();
        m = n.getMonth() + 1;
        d = n.getDate();
        
        //Temporary generate date MM-DD-YYYY
        var x = new Date('9/04/2020');  //start module date
        var y = new Date('12/25/2020');  //end module date
        const diffTime = Math.abs(y - x);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        //calculate difference = how many pixels
        var portion = Math.floor(820 / diffDays);
        
        //calculate number of days from original start date of module: current date - start date
        const timediff = Math.abs(n - x);
        const daysdiff = Math.ceil(timediff / (1000 * 60 * 60 * 24));
        let img = new Image();
        img.src = 'images/Lane1.png';
     //   img.onload = function() {
      //    imginit();
       // };

        let treasure = new Image();
        treasure.src='images/Chest1.png';
        let streasure = new Image();
        streasure.src='images/sparklechest1.png';
        let completed = new Image();
        completed.src='images/complete.png';
        let dino = new Image();
        dino.src='images/Dino1.png';
        let bin = new Image();
        bin.src='images/Bin.png';
        //canvase
        let canvas = document.querySelector('canvas');
        let ctx = canvas.getContext('2d');

        function imginit() {
          // future animation code goes here
          //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
          //ctx.drawImage(img, 0, 0, 600, 60, 0, 100, 1200 , 120);
          ctx.drawImage(img, 0, 170);
        }
        
        function treasureinit() {
          // future animation code goes here
          //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
          //ctx.drawImage(treasure, 0, 0, 600, 60, 360, 100, 1200 , 120);
          //ctx.drawImage(treasure, 0, 0, 600, 60, 730, 100, 1200 , 120);
          ctx.drawImage(treasure, 50, 130);
        }
        
        function streasureinit() {
          // future animation code goes here
          //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
          ctx.drawImage(streasure, 130, 110);
        }
        /*
        function bininit(){
            ctx.drawImage(bin, 0, 0, 600, 60, 10, 100, 1200 , 120);
        }*/
        
        function dinoinit() {
          // future animation code goes here
          //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
          if(n >= y){
            //ctx.drawImage(completed, 0, 0, 600, 60, 380, 60, 1400 , 130); 
            ctx.drawImage(completed, 350, 60, 200,100); 
            //ctx.drawImage(dino, 0, 0, 600, 60, 840, 100, 1200 , 120);
            ctx.drawImage(dino, 820, 110);
          }
          else{
            ctx.drawImage(dino, daysdiff * portion, 110);  
            //ctx.drawImage(dino, 0, 0, 600, 60, daysdiff * portion, 100, 1200 , 120);
          }
        }
        
        
        document.getElementById("gameBody").onload=function(){loadPixel()};
        
        function loadPixel(){
            imginit();
            treasureinit();
            streasureinit();
            dinoinit();
            //bininit();
        }
        
        
    </script>
</html>
