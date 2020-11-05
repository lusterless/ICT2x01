<?php
// Include config file
include "sqlConnection.php";
include "classes/users.class.php";
include "classes/module.class.php";

session_start();
$Details = "";
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
                    <form action="formativeBackend.php" method="post">
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
                            <input type="number" name="score" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Feedback</label>
                            <textarea name="feedback" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="manageModule.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>