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
#$module="";
// Add record
if ($request == 1) {
    $modulename = $data->module;
    $startdate = $data->startdate;
    $enddate = $data->enddate;
    $assessment = $data->assessments;
    $moduleid=$data->moduleno;
#$module = new Module($modulename,$startdate,$enddate,'10','1');
#$Details->setMod($module);
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
} else if ($request == 2) {
#$oldmod = $Details->getMod();
    $assessmentid = $data->assessmentid;
    $moduleid=$data->moduleno;
    $category = $data->category;
    $assessmentweightage = $data->assessmentweightage;
    #$result = mysqli_query($conn, "SELECT module FROM users WHERE name='".$name."'");
    #$row = mysqli_fetch_row($result);
    #$moduleid=$row[0];
#$oldmod->pushComponent($assessmentid, $category, $assessmentweightage);
#$Details->setMod($oldmod);
    $assessmentid = $moduleid *3 + $assessmentid - 1;
    mysqli_query($conn, "INSERT INTO assessments(assessment_id,module_id,assessment_name,assessment_weightage) VALUES('" . $assessmentid . "','".$moduleid."','" . $category . "','" . $assessmentweightage . "')");
    exit;
} else if ($request == 3) {
    $assessmentid = $data->assessmentid;
    $subassessmentname = $data->subassessmentname;
    $subassessmentweightage = $data->subassessmentweightage;
    $dataprobe = mysqli_query($conn, "SELECT * FROM subAssessments");
    $subassessmentid = mysqli_num_rows($dataprobe) + 1;
    $moduleid=$data->moduleno;
    $assessmentid = ($moduleid * 3) + $assessmentid - 1;
    #$result = mysqli_query($conn, "SELECT module FROM users WHERE name='".$name."'");
    #$row = mysqli_fetch_row($result);
    #$moduleid=$row[0];
#$oldmod = $Details->getMod();
#$tempComp = $oldmod->getComponent($assessmentid);
#$tempComp->pushSubComponent($subassessmentname, $subassessmentweightage);
    mysqli_query($conn, "INSERT INTO subAssessments(assessment_id,subAssessment_name,subAssessment_weightage,module_id) VALUES('" . $assessmentid  . "','" . $subassessmentname . "','" . $subassessmentweightage . "','".$moduleid."')");
#$Details->setMod($module);
    exit;
} else if ($request == 4) {
    $student = $data->student;
    $moduleid=$data->moduleno;
    #$result = mysqli_query($conn, "SELECT module FROM users WHERE name='".$name."'");
    #$row = mysqli_fetch_row($result);
   # $moduleid=$row[0];
    mysqli_query($conn, "UPDATE users SET module='".$moduleid."' WHERE studentid='" . $student . "'");
    exit;
} else if ($request == 5) {
    $assessmentid = $data->assessmentid;
    $subassessmentname = $data->subassessmentname;
    $student = $data->student;
    $dataprobe = mysqli_query($conn, "SELECT * FROM userSummative");
    $summativeid = mysqli_num_rows($dataprobe) + 1;
    mysqli_query($conn, "INSERT INTO userSummative(summativeid, studentid,subAssessment_name) VALUES('" . $summativeid . "','" . $student . "','" . $subassessmentname . "')");
#$Details->setMod($module);
    exit;
} else if ($request == 6) {
    $studentids = $data->studentids;
    $queryString = "";
    for ($i = 0; $i < count($studentids); $i++) {
        $idString = "\"${studentids[$i]}\"";
        $queryString = "{$queryString}{$idString}";
        if ($i != count($studentids) - 1) {
            $queryString = "${queryString},";
        }
    }
    $result = mysqli_query($conn, "SELECT studentid FROM users WHERE users.studentid IN (" . $queryString . ")");
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        echo $num_rows;
    }
    exit;
}

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