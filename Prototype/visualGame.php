<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include "classes/users.class.php";
include "classes/module.class.php";

session_start();
$Details = "";
if(!isset($_SESSION['sessionInfo'])){
    header("Location:loginPage.php");
}
else{
    $Details = $_SESSION['sessionInfo'];
    if($Details->getRole() != "student"){
        header("Location:loginPage.php");
    } 
}    

$module = $oldStartDate = $newStartDate = $oldEndDate = $newEndDate = "";
$summativeArray = [];
if($Details->getMod() != ""){
    $module = $Details->getMod();
    $oldStartDate = explode('-',$module->getStart());
    $newStartDate = $oldStartDate[1] . '/' . $oldStartDate[2] . '/' . $oldStartDate[0];
    $oldEndDate = explode('-', $module->getEnd());
    $newEndDate = $oldEndDate[1] . '/' . $oldEndDate[2] . '/' . $oldEndDate[0];
    foreach ($module->getAllComponent() as $f){
        foreach($f -> getSub() as $g){
            if($g->getScores() != null && $g->getSummativeFeedback() != null){
                $tempArray = [];
                $tempArray[] = $g->getName();
                $tempArray[] = $g->getWeight();
                $tempArray[] = $g->getScores();
                $tempArray[] = $g->getSummativeFeedback();
                $tempArray[] = $g->getSeen();
                $tempArray[] = $Details->getUser();
                $summativeArray[] = $tempArray;
            }
        }
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="css/userGame.css"> 
        <link rel="stylesheet" type="text/css" href="css/moduleTable.css">     
        <link rel="stylesheet" type="text/css" href="css/manageModule.css">          
        <title></title>
    </head>
    <body id="gameBody">
        <?php
            include "navBar.php";
            if($Details->getMod() == ""){
                echo "<h1>No Module Enrolled</h1>";
            }
            else{
                echo "
                    <div class='container' id='widgetC'>
                    <table style='width: 100%;' class='modTab'>
                    <tr>
                       <th colspan='3' style='text-align: center;'>Module: ". $module->getMod()."</th>                            
                    </tr>
                    <table style='width: 100%;' class='modTab'>
                    <tr>
                        <th>Component</th>
                        <th>Sub-Component</th>
                        <th>Weight in %</th>
                  </tr>";
                foreach ($module->getAllComponent() as $f){
                    foreach($f -> getSub() as $g){
                        echo "<tr>";
                        echo "<td>".$f->getName()."</th>";
                        echo "<td>".$g->getName()."</th>";
                        echo "<td>".$g->getWeight()."</th>";
                        echo "</tr>";
                    }
                }
                echo "</td>";
                echo "</tr></table></div>";
                echo '<canvas id="interactiveCanvas" width="900px" height="230px"></canvas>';
            }
            include "footer.php";
        ?>
        <!-- The Modal -->
        <div id="summativeModal" class="modal" style="width:600px; height: 600px; margin: auto;">
            <div class="modal-content">
                <div class="modal-header">
                  <span class="close">&times;</span>
                  <h2>Summative Grades</h2>
                </div>
                <div class="modal-body" id='summativeBody'>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <p>© 2013 - Singapore Institute of Technology</p>
                </div>
            </div>
        </div>
        <div id="formativeModal" class="modal" style="width:600px; height: 600px; margin: auto;">
            <div class="modal-content">
                <div class="modal-header">
                  <span class="close">&times;</span>
                  <h2>Formative Feedback</h2>
                </div>
                <div class="modal-body" id='formativeBody'>
                    <?php
                        $formativeArray = $module->getFormativeFeedback();
                        if(count($formativeArray) > 0){
                            $counter = 1;
                            foreach($formativeArray as $fb){
                                echo "<p>".$counter.") ".$fb."</p>";
                                $counter += 1;
                            }
                        }else{
                            echo "<p>No Feedbacks Given</p>";
                        }
                    ?>
                </div>
                <div class="modal-footer" style="text-align: center;">
                  <p>© 2013 - Singapore Institute of Technology</p>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript"> 
        summativeArray = <?php echo json_encode($summativeArray); ?>;
        startDate = <?php echo json_encode($newStartDate); ?>;
        endDate = <?php echo json_encode($newEndDate); ?>;
    </script>
    <script src="js/visualGame.js" type="text/javascript"></script>
</html>
