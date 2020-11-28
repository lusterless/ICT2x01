<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php
include "classes/users.class.php";
include "classes/module.class.php";
include "classes/ProfessorDictionaryAdapter.php";

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
?>

<html lang="en">
  <style>table {
  border-collapse: collapse;
}</style>
  <head>
    <title>Create a module</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="css/moduleTable.css">
    <link rel="stylesheet" type="text/css" href="css/createPageProf.css">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  </head>
  <body>
    <?php
        include "navBar.php";
    
        if($Details->getMod() == "")
        {
           echo '<form id="app" class="container" action="success.php" method="post" @submit="submit">
              <!-- Error Banner -->
              <p v-if="errors.length">
                <b>Please correct the following error(s):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
              </p>
              <!-- Create a Module -->
              <div v-if="step === 1" class="form-style-6">
                <h1>Create a Module</h1>
                
                <br>Module Name: <input type="text" placeholder="Module Name" v-model="module" required />
                <br>Module Start date: <input type="date" v-model="startdate" required />
                <br>Module End date: <input type="date" v-model="enddate" required />
                <button type="button" @click="nextStep">Next</button>
              </div>
              <!-- Add Assessment for Module -->
              <div v-if="step === 2" class="form-style-6">
                <h1>Add Assessment for Module {{ module }}</h1>
                <div v-for="(assessment, index) in assessments">
                  <div>
                    <h2>Assessment {{ index + 1 }}</h2>
                    <select v-model="assessment.category">
                      <option disabled value="">Please select one</option>
                      <option v-for="category in categories">{{ category }}</option>
                    </select>
                    <input type="text" v-model="assessment.weightage" required />
                    <button type="button"v-show="assessments.length > 1" @click="removeAssessment(index)">
                      Remove Assessment
                    </button>
					
                  </div>
                  <div>

                    <h3>Sub-assessments for Assessment {{ index + 1 }}</h3>
                    <div v-for="(subAssessment, subIndex) in assessment.subAssessments">
                      <input type="text" v-model="subAssessment.name" required />
                      <input type="text" v-model="subAssessment.weightage" required />
                      <button type="button" @click="addSubAssessment(index, subIndex)">Add Subassessment</button>
						<p></p>
                      <button type="button" v-show="assessment.subAssessments.length > 1" @click="removeSubAssessment(index, subIndex)">
                        Remove Subassessment
                      </button>
						<p></p>
                    </div>
                  </div>
                </div>
                <button type="button" id="add" @click="addAssessment">
                  Add Assessment
                </button>
				<p></p>
                <div>
                  <button type="button" @click="prevStep">Go Back</button>
                  <button type="button" @click="nextStep">Next</button>
                </div>
              </div>
              <!-- Add Students -->
              <div v-if="step === 3">
                <div>
                  <h1>Add Students to Module</h1>
                  <br>
                    <input type="file" v-on:change="handleUpload" accept=".csv" required />
                    <div v-if="students.length != 0" v-for="student in students">
                        id: {{ student}}
                    </div>
                  <button type="button" @click="prevStep">Go Back</button>
                  <button type="button" @click="nextStep">Next</button>
                </div>
              </div>
              <!-- Confirmation -->
      <div v-if="step === 4" class="form-style-6">
        <div>
            <h1>Confirmation page</h1>
            <br>
            <h5>Module Name: {{ module }}</h5>
            <h5>Module Start Date: {{ startdate }}</h5>
            <h5>Module End Date: {{ enddate }}</h5>
        <div v-for="(assessment, index) in assessments">
          <div>
            <h5>Assessment {{ index + 1 }} : {{ assessment.category }}</h5>
            <h5>Assessment {{ index + 1 }} Weightage : {{ assessment.weightage}}</h5>
            <div v-for="(subAssessment, subIndex) in assessment.subAssessments">
                <h5>Sub Assessment {{ subIndex + 1 }} : {{ subAssessment.name }} : {{ subAssessment.weightage }}</h5>
            </div>
          </div>
        </div>
          <button type="button" @click="prevStep">Go Back</button>
          <button type="button" @click="nextStep();addModule();">Confirm</button>';
            if($Details->getMod() != ""){
                header("Location:createPageProf.php");
            }
        echo '</div>';
        echo ' </div>';
        echo '<div v-if="step === 5">
                <div>
                <h2>Success</h2>';
                  echo '<button type="submit">Back to dashboard</button>
                </div>
              </div>';
            echo '</form>';
        }else{
             $module = $Details->getMod();
             $studentList = $_SESSION["studentList"];
             echo "
                 <div class='container' id='widgetC'>
                 <table style='width: 100%;' class='modTab'>
                 <tr>
                    <th colspan='3' style='text-align: center;'>Module: ". $module->getMod()."</th>                            
                 </tr>
                 <tr>
                    <th colspan='3' style='text-align: center;'>Total Enrolled: ". (string)((int)$module->getTotalEnroll() - 1)."</th>                            
                 </tr>                 
                 <tr>
                     <th>Component</th>
                     <th>Sub-Component</th>
                     <th>Weight in %</th>
               </tr>";
             foreach ($module->getAllComponent() as $f){
                 foreach($f -> getSub() as $g){
                     echo "<tr>";
                     echo "<td>".$f->getName()."</td>";
                     echo "<td>".$g->getName()."</td>";
                     echo "<td>".$g->getWeight()."</td>";
                     echo "</tr>";
                 }
             }
             echo "</td>";
             echo "</tr></table></div>";
             echo "<div class='container' id='studentC'>
                    <table style='width: 100%;' class='modTab'>
                         <tr>
                             <th colspan='8' style='text-align: center;'>Enrolled Students</th>                            
                         </tr>
                         <tr>
                             <th>ID</th>
                             <th>Name</th>
                             <th>Email</th>
                             <th>Phone No.</th>
                             <th>Formative Feedbacks</th>
                             <th>Subject</th>
                             <th>Scores</th>
                             <th>Summative Feedback</th>
                       </tr>";
             //students
            foreach($studentList->SelectAll() as $f){
                echo "<tr>";
                echo "<td>".$f->getUser()."</td>";
                echo "<td>".$f->getName()."</td>";
                echo "<td>".$f->getEmail()."</td>";
                echo "<td>".$f->getTel()."</td>";
                echo "<td><select class='form-control' name='test' readonly>";
                if(count($f->getMod()->getFormativeFeedback()) > 0){
                    foreach($f->getMod()->getFormativeFeedback() as $s){
                        echo "<option>".$s."</option>";
                    }
                }
                else{
                    echo "<option>No Feedbacks Given</option>";
                }
                echo "</select></td>";
                //subcomponent
                echo "<td colspan='3'><select class='form-control' name='test' readonly>";
                foreach($f->getMod()->getAllComponent() as $comp){
                    foreach($comp->getSub() as $cs){
                        echo "<option>";
                        echo $cs->getName();
                        if($cs->getScores() != NULL){
                            echo ", ", $cs->getScores() . ", ";
                            echo $cs->getSummativeFeedback();
                        }else{
                            echo ", No Scores, No Feedbacks";
                        }
                        echo "</option>";
                    }
                }
                echo "</select></td>";

            }
            echo"   </table>
                </div>";
         }
        include "footer.php";
    ?>  
    <script src="js/createPageProf.js"></script>
  </body>
</html>

