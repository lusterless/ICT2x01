<?php
// Include config file
include "sqlConnection.php";
include "classes/users.class.php";
include "classes/module.class.php";
include "classes/ProfessorDictionaryAdapter.php";

session_start();
$Details = "";
$studentList = $_SESSION["studentList"];
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "professor" || $Details->getMod() == ""){
        header("Location:loginPage.php");
    }
}

$module = $Details->getMod();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="css/feedbacks.css">    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    <script src="js/addSummative.js" type="text/javascript"></script>    
</head>
    <?php include('navBar.php');?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Summative Feedback</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="summativeBackend.php" method="post">
                        <div class="form-group">
                            <label>Choose Students</label>
                            <div class="scrollableList">
                            <?php
                                foreach($studentList->SelectAll() as $eachStudent){
                                    echo "<input type='checkbox' name='studentList[]' value='".$eachStudent->getUser()."'/>";
                                    echo "<label for='".$eachStudent->getUser()."'>".strtolower($eachStudent->getName())."</label><br>";
                                }
                            ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <?php
                            echo "<select class='form-control' name='sub' required>";
                            foreach($module->getAllComponent() as $f){
                                foreach($f->getSub() as $g){
                                    echo "<option value='".$g->getName()."'>".$g->getName()."</option>";
                                }
                            }
                            echo "</select>";
                            ?>
                        </div>
                        <div class="form-group">
                            <label>Score</label>
                            <input type="number" name="score" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Feedback</label>
                            <textarea name="feedback" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                        </div>
                        <div class="form-group">
                            <label>Import Feedback</label>
                            <input type="file" onchange="return fileValidation()" id="summativeFile" name="summativeFile" accept=".xls,.xlsx"/>
                        </div>
                        <input type="hidden" id="sarrayFeedback" name="sarrayFeedback" value="">
                        <input type='hidden' name='summativePage' value='summativePage'>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="manageModule.php" class="btn btn-default">Cancel</a>
                    </form>
                    <?php
                        if(isset($_SESSION["msg"])){
                            $msg = $_SESSION["msg"];
                            echo "<p>".$msg."</p";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>


<?php
unset($_SESSION["msg"]);
?>