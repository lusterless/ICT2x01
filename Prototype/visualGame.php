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

if($Details->getMod() != ""){
    $module = $Details->getMod();
    $oldStartDate = explode('-',$module->getStart());
    $newStartDate = $oldStartDate[1] . '/' . $oldStartDate[2] . '/' . $oldStartDate[0];
    $oldEndDate = explode('-', $module->getEnd());
    $newEndDate = $oldEndDate[1] . '/' . $oldEndDate[2] . '/' . $oldEndDate[0];   
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
                $module = $Details->getMod();
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
                        <th>Weight</th>
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
                  <h2>Grades & Comments <span class="glyphicon glyphicon-user"></span> </h2>
                </div>
                <div class="modal-body">
                  Test
                </div>
                <div class="modal-footer">
                  <h3>Modal Footer</h3>
                </div>
            </div>
        </div>
        <div id="formativeModal" class="modal" style="width:600px; height: 600px; margin: auto;">
            <div class="modal-content">
                <div class="modal-header">
                  <span class="close">&times;</span>
                  <h2>Comments <span class="glyphicon glyphicon-user"></span> </h2>
                </div>
                <div class="modal-body">
                  Test
                </div>
                <div class="modal-footer">
                  <h3>Modal Footer</h3>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript"> 
        startDate = <?php echo json_encode($newStartDate); ?>;
        endDate = <?php echo json_encode($newEndDate); ?>;
    </script>
    <script src="js/visualGame.js" type="text/javascript"> </script>
</html>
