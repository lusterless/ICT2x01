<?php
include "sqlConnection.php";

$data = json_decode(file_get_contents("php://input"));

$request = $data->request;

// Add record
if($request == 1){
  $module = $data->module;
  $startdate = $data->startdate;
  $enddate = $data->enddate;
  $assessment = $data->assessments;

  $userData = mysqli_query($conn,"SELECT * FROM Module");
  if(mysqli_num_rows($userData) == 0){
    mysqli_query($conn,"INSERT INTO Module(module_id,module_name,start_date,end_date) VALUES('1','".$module."','".$startdate."','".$enddate."')");
    echo "Insert successfully";
  }else{
    echo "Did not insert.";
  }
  exit;
}
else if($request == 2){   
  $assessmentid = $data->assessmentid;
  $category = $data->category;
  $assessmentweightage = $data->assessmentweightage;
  mysqli_query($conn,"INSERT INTO assessments(assessment_id,module_id,assessment_name,assessment_weightage) VALUES('".$assessmentid."','1','".$category."','".$assessmentweightage."')");
  exit;
}
else if($request == 3){   
  $assessmentid = $data->assessmentid;
  $subassessmentname = $data->subassessmentname;
  $subassessmentweightage = $data->subassessmentweightage;
  $dataprobe = mysqli_query($conn,"SELECT * FROM subAssessments");
  $subassessmentid = mysqli_num_rows($dataprobe) + 1;
  mysqli_query($conn,"INSERT INTO subAssessments(assessment_id,subAssessment_name,subAssessment_weightage,module_id) VALUES('".$assessmentid."','".$subassessmentname."','".$subassessmentweightage."','1')");
  exit;
}
