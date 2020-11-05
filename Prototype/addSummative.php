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
// Define variables and initialize with empty values
$subComponent = $subScore = $subFeedback = "";
$subComponent_err = $subScore_err = $subFeedback_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["add"]) && !empty($_POST["add"])){ //this is needed to check based on the update at the bottom of the form
    // Get hidden input value
    $nameID = $_POST["update"];
    
    // Validate name
    $input_subName = trim($_POST["subComponent"]);
    if(empty($input_subName)){
        $subComponent_err = "Please enter a Component Name.";
    } else{
        $subComponent = $input_subName;
    }
    
    // Validate address address
    $input_subScore = trim($_POST["subScore"]);
    if(empty($input_subScore)){
        $subScore_err = "Please enter a value.";     
    }elseif(!ctype_digit($input_subScore)){
        $subFeedback_err = "Please enter a positive integer value.";
    }
    else{
        $subScore = $input_subScore;
    }
    
    // Validate salary
    $input_feedback = trim($_POST["subFeedback"]);
    if(empty($input_feedback)){
        $subFeedback_err = "Please enter Feedback.";     
    } else{
        $subFeedback = $input_feedback;
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
    <?php include('navBar.php');?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Add Summative Feedback</h2>
                    </div>
                   
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($subComponent_err)) ? 'has-error' : ''; ?>">
                            <label>Sub Component Name</label>
                            <input type="text" name="subComponent" class="form-control" value="<?php echo $subComponent; ?>">
                            <span class="help-block"><?php echo $subComponent_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($subScore_err)) ? 'has-error' : ''; ?>">
                            <label>Score</label>
                            <textarea name="subScore" class="form-control"><?php echo $subScore; ?></textarea>
                            <span class="help-block"><?php echo $subScore_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($subFeedback_err)) ? 'has-error' : ''; ?>">
                            <label>Feedback</label>
                            <input type="text" name="subFeedback" class="form-control" value="<?php echo $subFeedback; ?>">
                            <span class="help-block"><?php echo $subFeedback_err;?></span>
                        </div>
                        <input type="hidden" name="add" value="<?php echo $subComponent; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="manageModule.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>