/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



statusModalOpen = false;

//current Date for tracking
var n =  new Date(); //current date
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();

//Temporary generate date MM-DD-YYYY
var x = new Date(startDate);  //start module date
var y = new Date(endDate);  //end module date
//var x = new Date(module.getStart());
//var y = new Date(module.getEnd());
const diffTime = Math.abs(y - x);
const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
//calculate difference = how many pixels
var portion = Math.floor(820 / diffDays);

//calculate number of days from original start date of module: current date - start date
const timediff = Math.abs(n - x);
const daysdiff = Math.ceil(timediff / (1000 * 60 * 60 * 24));


//DECLARATIoN
let img = new Image();
img.src = 'images/Lane1.png';       
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


//canvas
let canvas = document.querySelector('canvas');
let ctx = canvas.getContext('2d');
var c = document.getElementById("interactiveCanvas");
var elements = [] //All elements in canvas
var modal = document.getElementById("summativeModal");
var fmodal = document.getElementById("formativeModal");


//span function close modal
var span = document.getElementsByClassName("close")[1];
var span1 = document.getElementsByClassName("close")[2];
span.onclick = function() {
    modal.style.display = "none";  
    statusModalOpen = false;
    const summativeBody = document.getElementById("summativeBody");
    while (summativeBody.firstChild) {
      summativeBody.removeChild(summativeBody.lastChild);
    }
    console.log(1);
 }
span1.onclick = function() {
    fmodal.style.display = "none";   
    statusModalOpen = false;
 }

canvas.addEventListener('click', function(e) {
    var x = e.pageX - c.offsetLeft;
    var y = e.pageY - c.offsetTop;

    elements.forEach(function(element){
        if((x <= element.x + element.sizex) && (x >= element.x) && (y >= element.y) && y <= element.y + element.sizey){
            if(statusModalOpen == false){
                element.clicked();
                statusModalOpen = true;
            }
        }
    }); 
});


//INITIALIZE IMAGE

function imginit() {
  // future animation code goes here
  //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
  ctx.drawImage(img, 0, 170);
}

function treasureinit(sub, weight, score, feedback) {
  // future animation code goes here
  var no = Math.floor(Math.random()*820) + 1;
  ctx.drawImage(treasure, no , 130);
    elements.push({
        x: no,
        y: 130,
        sizex: 80,
        sizey: 60,
        clicked: function(){
            showSummative(sub, weight, score, feedback);
        }
    })
}

function streasureinit() {
  // future animation code goes here
  ctx.drawImage(streasure, 130, 110);
}
/*
function bininit(){
    ctx.drawImage(bin, 0, 0, 600, 60, 10, 100, 1200 , 120);
}*/

function dinoinit() {
  // future animation code goes here
  if(n >= y){
    ctx.drawImage(completed, 350, 60, 200,100); 
    ctx.drawImage(dino, 820, 110);
    elements.push({
        x: 820,
        y: 110,
        sizex: 80,
        sizey: 80,
        clicked: function(){
            showFormative();
        }
    })
  }
  else{
    ctx.drawImage(dino, daysdiff * portion, 110);
    elements.push({
        x: daysdiff * portion,
        y: 110,
        sizex: 80,
        sizey: 80,
        clicked: function(){
            showFormative();
        }
    })
  }
}

function showSummative(sub, weight, score, feedback){
    modal.style.display = "block";
    var summativeBody = document.getElementById("summativeBody");
    var tag = document.createElement("p");
    var text = document.createTextNode(sub);
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
    var tag = document.createElement("p");
    var text = document.createTextNode(weight);
    tag.appendChild(text);  
    summativeBody.appendChild(tag);     
    var tag = document.createElement("p");
    var text = document.createTextNode(score);
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
    var tag = document.createElement("p");
    var text = document.createTextNode(feedback);
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
}

function showFormative(){
    fmodal.style.display = "block";
//    var formativeBody = document.getElementById("formativeBody");
//    for (var i = 0; i < formativeArray.length; i++) {
//        var tag = document.createElement("p");
//        var text = document.createTextNode(formativeArray[i]);
//        tag.appendChild(text);  
//        formativeBody.appendChild(tag);
//    }
}


document.getElementById("gameBody").onload=function(){loadPixel()};
function loadPixel(){
    imginit();
    //generate treasure
    for(var i = 0; i < summativeArray.length; i++){
        treasureinit(summativeArray[i][0],summativeArray[i][1],summativeArray[i][2],summativeArray[i][3]);
    }
    //streasureinit();
    dinoinit();
    //bininit();
}