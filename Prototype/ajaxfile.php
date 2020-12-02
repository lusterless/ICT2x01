<?php

include "sqlConnection.php";

include "classes/users.class.php";
include "classes/module.class.php";
include_once "classes/usersFactory.php";

session_start();
$Details = "";
if (!isset($_SESSION['sessionInfo'])) {
    header("Location:loginPage.php");
} else {
    $Details = $_SESSION['sessionInfo'];
    if ($Details->getRole() != "professor") {
        header("Location:loginPage.php");
    }
}

$data = json_decode(file_get_contents("php://input"));

$request = $data->request;
$name = $Details->getName();

// Add record to users table and module table
if ($request == 1) {
    $modulename = $data->module;
    $startdate = $data->startdate;
    $enddate = $data->enddate;
    $assessment = $data->assessments;
    $moduleid=$data->moduleno;
    $userData = mysqli_query($conn, "SELECT * FROM Module");
    if (mysqli_num_rows($userData) == 0) {
        mysqli_query($conn, "INSERT INTO Module(module_id,module_name,start_date,end_date) VALUES('1','" . $modulename . "','" . $startdate . "','" . $enddate . "')");
        mysqli_query($conn, "UPDATE users SET module='1' WHERE name='" . $name . "'");
        echo "Insert successfully";
    } else {
        mysqli_query($conn, "INSERT INTO Module(module_id,module_name,start_date,end_date) VALUES('".$moduleid."','" . $modulename . "','" . $startdate . "','" . $enddate . "')");
        mysqli_query($conn, "UPDATE users SET module='".$moduleid."' WHERE name='" . $name . "'");
    }
    exit;
     
} 

//Add record to Assessment table, sets assessmentid as moduleid*3 + assessmentid-1
//moduleid * 3 because there is a maximum of 3 assessments in a module, to ensure that the id is unique
//assessmentid is based on assessment id, so it can only be 1,2,3. -1 to fit the numbers in nicely to make it unique
else if ($request == 2) {
    $assessmentid = $data->assessmentid;
    $moduleid=$data->moduleno;
    $category = $data->category;
    $assessmentweightage = $data->assessmentweightage;
    $assessmentid = $moduleid *3 + $assessmentid - 1;
    mysqli_query($conn, "INSERT INTO assessments(assessment_id,module_id,assessment_name,assessment_weightage) VALUES('" . $assessmentid . "','".$moduleid."','" . $category . "','" . $assessmentweightage . "')");
    exit;
} 

//Add record to subAssessments table
else if ($request == 3) {
    $assessmentid = $data->assessmentid;
    $subassessmentname = $data->subassessmentname;
    $subassessmentweightage = $data->subassessmentweightage;
    $dataprobe = mysqli_query($conn, "SELECT * FROM subAssessments");
    $subassessmentid = mysqli_num_rows($dataprobe) + 1;
    $moduleid=$data->moduleno;
    $assessmentid = ($moduleid * 3) + $assessmentid - 1;
    mysqli_query($conn, "INSERT INTO subAssessments(assessment_id,subAssessment_name,subAssessment_weightage,module_id) VALUES('" . $assessmentid  . "','" . $subassessmentname . "','" . $subassessmentweightage . "','".$moduleid."')");
    exit;
} 
//setting moduleid to students in users table
else if ($request == 4) {
    $student = $data->student;
    $moduleid=$data->moduleno;
    mysqli_query($conn, "UPDATE users SET module='".$moduleid."' WHERE studentid='" . $student . "'");
    exit;
} 
//creating summative feedbacks per student
else if ($request == 5) {
    $assessmentid = $data->assessmentid;
    $subassessmentname = $data->subassessmentname;
    $student = $data->student;
    mysqli_query($conn, "INSERT INTO userSummative(studentid,subAssessment_name) VALUES('" . $student . "','" . $subassessmentname . "')");
    exit;
} 
//ensuring that the students do not have a module tagged to them before assigning them to a module
else if ($request == 6) {
    $studentids = $data->studentids;
    $queryString = "";
    for ($i = 0; $i < count($studentids); $i++) {
        $idString = "\"${studentids[$i]}\"";
        $queryString = "{$queryString}{$idString}";
        if ($i != count($studentids) - 1) {
            $queryString = "${queryString},";
        }
    }
    $result = mysqli_query($conn, "SELECT studentid FROM users WHERE users.studentid IN (" . $queryString . ") AND module IS NULL");
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        echo $num_rows;
    }
    exit;
}
//ensuring that 3 in a row assessmentid is empty, so it will not have any collision
else if ($request == 7) {
    $counter = True;
    $result = mysqli_query($conn, "SELECT * FROM Module");
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        $num_rows = $num_rows + 1;
        while($counter == True){
            $result2 = mysqli_query($conn, "SELECT module_id FROM Module WHERE module_id = '".$num_rows."'");
            if(mysqli_num_rows($result2) != 0) {
                $num_rows = $num_rows + 1;
            }
            else {
               $result3 = mysqli_query($conn, "SELECT assessment_id FROM assessments WHERE assessment_id = '".($num_rows * 3)."'");
               if (mysqli_num_rows($result3) != 0) {
                   $num_rows = $num_rows + 1;
               }
               else {
                    $counter=False;
               }
            }
        }
        echo $num_rows;
    }
    else {
        echo '1';
    }
    exit;
}
?>