<?php
include "sqlConnection.php";

include "classes/users.class.php";
include "classes/module.class.php";
include_once "classes/usersFactory.php";

session_start();
$Details = "";
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "professor"){
        header("Location:loginPage.php");
    }
}

$data = json_decode(file_get_contents("php://input"));

$request = $data->request;
$name= $Details->getName();
#$module="";

// Add record
if($request == 1){
  $modulename = $data->module;
  $startdate = $data->startdate;
  $enddate = $data->enddate;
  $assessment = $data->assessments;
  #$module = new Module($modulename,$startdate,$enddate,'10','1');
  #$Details->setMod($module);
  $userData = mysqli_query($conn,"SELECT * FROM Module");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($conn,"INSERT INTO Module(module_id,module_name,start_date,end_date) VALUES('1','".$modulename."','".$startdate."','".$enddate."')");
    mysqli_query($conn,"UPDATE users SET module='1' WHERE name='".$name."'");
    echo "Insert successfully";
  }else{
    echo "Did not insert.";
  }
  exit;
}
else if($request == 2){   
  #$oldmod = $Details->getMod();
  $assessmentid = $data->assessmentid;
  $category = $data->category;
  $assessmentweightage = $data->assessmentweightage;
  #$oldmod->pushComponent($assessmentid, $category, $assessmentweightage);
  #$Details->setMod($oldmod);
  mysqli_query($conn,"INSERT INTO assessments(assessment_id,module_id,assessment_name,assessment_weightage) VALUES('".$assessmentid."','1','".$category."','".$assessmentweightage."')");
  exit;
}
else if($request == 3){   
  $assessmentid = $data->assessmentid;
  $subassessmentname = $data->subassessmentname;
  $subassessmentweightage = $data->subassessmentweightage;
  $dataprobe = mysqli_query($conn,"SELECT * FROM subAssessments");
  $subassessmentid = mysqli_num_rows($dataprobe) + 1;
  #$oldmod = $Details->getMod();
  #$tempComp = $oldmod->getComponent($assessmentid);
  #$tempComp->pushSubComponent($subassessmentname, $subassessmentweightage);
  mysqli_query($conn,"INSERT INTO subAssessments(assessment_id,subAssessment_name,subAssessment_weightage,module_id) VALUES('".$assessmentid."','".$subassessmentname."','".$subassessmentweightage."','1')");
  
  #$Details->setMod($module);
  exit;
}
else if($request == 4){
  $student = $data->student;
  mysqli_query($conn,"UPDATE users SET module='1' WHERE studentid='".$student."'");
  exit;
}
else if($request == 5){
    $assessmentid = $data->assessmentid;
    $subassessmentname = $data->subassessmentname;
    $student = $data-> student;
    $dataprobe = mysqli_query($conn,"SELECT * FROM userSummative");
    $summativeid = mysqli_num_rows($dataprobe) + 1;
    mysqli_query($conn,"INSERT INTO userSummative(summativeid, studentid,subAssessment_name) VALUES('".$summativeid."','".$student."','".$subassessmentname."')");
    #$Details->setMod($module);
    exit;
} else if ($request == 6) 
{    
    $studentids = $data->studentids;
    $queryString = "";
    for ($i = 0; $i < count($studentids); $i++) {
        $idString = "\"${studentids[$i]}\"";
        $queryString = "{$queryString}{$idString}";
        if ($i != count($studentids) - 1) {
            $queryString = "${queryString},";
        }
    }
    $result = mysqli_query($conn, "SELECT studentid FROM users WHERE users.studentid IN (". $queryString .")");
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        echo $num_rows;
    }
    exit;
}

?>