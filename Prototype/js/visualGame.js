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
var daysdiff = 0;
var portion = 0;

if(n > x){
    //var x = new Date(module.getStart());
    //var y = new Date(module.getEnd());
    const diffTime = Math.abs(y - x);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    //calculate difference = how many pixels
    portion = Math.floor(820 / diffDays);

    //calculate number of days from original start date of module: current date - start date
    const timediff = Math.abs(n - x);
    daysdiff = Math.ceil(timediff / (1000 * 60 * 60 * 24));
}



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
    location.reload(true);
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
  //drawImage(image, sx, sy, sWidth, sHeight, dx, dy, dWidth, dHeight)
  ctx.drawImage(img, 0, 170);
}

function treasureinit(sub, weight, score, feedback, no, studentid) {
  // future animation code goes here
  ctx.drawImage(treasure, no , 130);
    elements.push({
        x: no,
        y: 130,
        sizex: 80,
        sizey: 60,
        clicked: function(){
            showSummative(sub, weight, score, feedback, studentid);
        }
    })
}

function streasureinit(sub, weight, score, feedback, no, studentid) {
  // future animation code goes here
  // future animation code goes here
  ctx.drawImage(streasure, no , 110);
    elements.push({
        x: no,
        y: 130,
        sizex: 80,
        sizey: 60,
        clicked: function(){
            showSummative(sub, weight, score, feedback, studentid);
        }
    })
}

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

function showSummative(sub, weight, score, feedback, studentid){
    //prompt modal
    modal.style.display = "block";
    var summativeBody = document.getElementById("summativeBody");
    var tag = document.createElement("p");
    var text = document.createTextNode("Subject: " + sub);
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
    var tag = document.createElement("p");
    var text = document.createTextNode("Weightage: " + weight + "%");
    tag.appendChild(text);  
    summativeBody.appendChild(tag);     
    var tag = document.createElement("p");
    var newScore = Math.floor((score/100) * weight);
    var text = document.createTextNode("Score: " + newScore + "%");
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
    var tag = document.createElement("p");
    var text = document.createTextNode("Feedback: " + feedback);
    tag.appendChild(text);  
    summativeBody.appendChild(tag); 
    
    //update database value
    $.ajax({
        url: 'updateSeen.php',
        type: 'POST',
        data: {studentid: studentid, sub: sub},
        success: function(data){
            //update array seen value
        }
    });
}

function showFormative(){
    fmodal.style.display = "block";
}


document.getElementById("gameBody").onload=function(){loadPixel()};

function loadPixel(){
    imginit();
    //generate treasure
    var distance = 805 / summativeArray.length;
    for(var i = 1; i <= summativeArray.length; i++){
        //if unseen
        if(summativeArray[i-1][4] == 0){
            streasureinit(summativeArray[i-1][0],summativeArray[i-1][1],summativeArray[i-1][2],summativeArray[i-1][3], distance * i, summativeArray[i-1][5]);
        }else{
            treasureinit(summativeArray[i-1][0],summativeArray[i-1][1],summativeArray[i-1][2],summativeArray[i-1][3], distance * i, summativeArray[i-1][5]);
        }
    }
    dinoinit();
}