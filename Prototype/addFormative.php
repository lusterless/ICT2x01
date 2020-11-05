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
$formativeFB  = $studID = "";
$formativeFB_err  = $studID_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["update"]) && !empty($_POST["update"])){
    // Get hidden input value
    $studID = $_POST["update"];
    echo $studID;
    $input_feedback = trim($_POST["formativeFB"]);
    echo $input_feedback;
    if(empty($input_feedback)){
        $subFeedback_err = "Please enter Feedback.";     
    } else{
        $formativeFB = $input_feedback;
    }
    
    // Check input errors before inserting in database
    if(empty($formativeFB_err)  && empty($studID_err)){
        // Prepare an update statement
        $sql = "UPDATE userFormative SET formative_feedback=? WHERE studentid=? AND role = 'student'";
 
        if ($stmt = $conn->prepare($sql)){
            $stmt->bind_param('si',$param_forFB,$param_studID);
            
            $param_forFB = $formativeFB;
            $param_studID = $studID;
            if ($stmt->execute()){
                header('location:ManageModule.php');
                exit();
            }else{
                echo'Wrong';
            }
        }
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($studID_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="studID" class="form-control" value="<?php echo $studID; ?>" readonly>
                            <span class="help-block"><?php echo $studID_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($formativeFB_err)) ? 'has-error' : ''; ?>">
                            <label>Feedback</label>
                            <textarea name="formativeFB" class="form-control"><?php echo $formativeFB; ?></textarea>
                            <span class="help-block"><?php echo $formativeFB_err;?></span>
                        </div>
                        <input type="hidden" name="update" value="<?php echo $studID; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="manageModule.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>